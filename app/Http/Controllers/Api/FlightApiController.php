<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Flight;
use App\Models\Order;
use App\Models\OrderLot;
use App\Enums\OrderLotStatus;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class FlightApiController extends Controller
{
    /**
     * Update flight status
     *
     * @param Request $request
     * @param int $flightId
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateStatus(Request $request, $flightId)
    {
        try {
            // Validate request
            $request->validate([
                'status' => ['required', 'string', Rule::in(['in_process', 'finished'])]
            ]);

            // Find flight
            $flight = Flight::with(['order', 'flightLots.lot'])->find($flightId);

            if (!$flight) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vuelo no encontrado.',
                    'data' => null
                ], 404);
            }

            $status = $request->status;

            if ($status === 'in_process') {
                $this->handleFlightStart($flight);
            } elseif ($status === 'finished') {
                $this->handleFlightCompletion($flight);
            }

            return response()->json([
                'success' => true,
                'message' => 'Estado del vuelo actualizado exitosamente.',
                'data' => [
                    'flight_id' => $flight->id,
                    'status' => $flight->status,
                    'order_status' => $flight->order->status
                ]
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Datos de entrada inválidos.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Error updating flight status: ' . $e->getMessage(), [
                'flight_id' => $flightId,
                'status' => $request->status,
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar el estado del vuelo.',
                'data' => null
            ], 500);
        }
    }

    /**
     * Handle flight start logic
     *
     * @param Flight $flight
     * @return void
     */
    private function handleFlightStart(Flight $flight)
    {
        // Update flight status
        $flight->update([
            'status' => 'in_progress',
            'started_at' => now()
        ]);

        // Update parent order status
        $flight->order->update(['status' => 'in_progress']);

        // Update all OrderLots related to this Order to "in_process"
        $flight->flightLots->each(function ($flightLot) use ($flight) {
            $orderLot = OrderLot::where('order_id', $flight->order_id)
                ->where('lot_id', $flightLot->lot_id)
                ->first();

            if ($orderLot) {
                $orderLot->update(['status' => OrderLotStatus::IN_PROCESS]);
            }
        });
    }

    /**
     * Handle flight completion logic
     *
     * @param Flight $flight
     * @return void
     */
    private function handleFlightCompletion(Flight $flight)
    {
        // Update flight status
        $flight->update([
            'status' => 'completed',
            'completed_at' => now()
        ]);

        // Check if this is the last remaining flight for the Order
        $this->updateOrderStatusBasedOnFlights($flight->order);

        // Update OrderLots based on flight coverage
        $this->updateOrderLotsBasedOnFlightCoverage($flight);
    }

    /**
     * Update order status based on flight completion status
     *
     * @param Order $order
     * @return void
     */
    private function updateOrderStatusBasedOnFlights(Order $order)
    {
        $flights = Flight::where('order_id', $order->id)->get();

        $allFinished = $flights->every(function ($flight) {
            return $flight->status === 'completed';
        });

        if ($allFinished) {
            $order->update([
                'status' => 'completed',
                'completed_at' => now()
            ]);
        } else {
            $order->update(['status' => 'in_progress']);
        }
    }

    /**
     * Update OrderLots based on flight coverage
     *
     * @param Flight $flight
     * @return void
     */
    private function updateOrderLotsBasedOnFlightCoverage(Flight $flight)
    {
        foreach ($flight->flightLots as $flightLot) {
            $orderLot = OrderLot::where('order_id', $flight->order_id)
                ->where('lot_id', $flightLot->lot_id)
                ->first();

            if (!$orderLot) {
                continue;
            }

            // Calculate coverage percentage
            $totalHectaresInOrder = $orderLot->hectares;
            $hectaresAppliedInFlight = $flightLot->hectares_to_apply;

            // Get total hectares already applied in previous flights for this lot
            $totalAppliedSoFar = $this->getTotalHectaresAppliedToLot($flight->order_id, $flightLot->lot_id);

            if ($totalAppliedSoFar >= $totalHectaresInOrder) {
                // Flight covered OrderLot completely
                $orderLot->update(['status' => OrderLotStatus::FINISHED]);
            } else {
                // Flight covered OrderLot partially
                $orderLot->update(['status' => OrderLotStatus::IN_PROCESS]);
            }
        }
    }

    /**
     * Get total hectares applied to a specific lot across all finished flights
     *
     * @param int $orderId
     * @param int $lotId
     * @return float
     */
    private function getTotalHectaresAppliedToLot($orderId, $lotId)
    {
        return Flight::where('order_id', $orderId)
            ->where('status', 'completed')
            ->whereHas('flightLots', function ($query) use ($lotId) {
                $query->where('lot_id', $lotId);
            })
            ->with(['flightLots' => function ($query) use ($lotId) {
                $query->where('lot_id', $lotId);
            }])
            ->get()
            ->sum(function ($flight) {
                return $flight->flightLots->sum('hectares_to_apply');
            });
    }
}
