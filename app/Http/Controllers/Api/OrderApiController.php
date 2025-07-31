<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Resources\OrdersResource;
use App\Http\Resources\OrderResource;
use App\Models\OrderLot;

class OrderApiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $query = Order::select([
            'id',
            'order_number',
            'total_hectares',
            'total_amount',
            'status',
            'scheduled_date',
            'service_id',
            'client_id',
            /* 'pilot_id',
            'ground_support_id' */
        ])->with([
            'service:id,name',
            'client:id,business_name'
        ]);

        /* if (auth()->user()->hasRole('Pilot')) {
            $query->where('pilot_id', auth()->user()->id);
        }

        if (auth()->user()->hasRole('Ground Support')) {
            $query->where('ground_support_id', auth()->user()->id);
        } */

        if (auth()->user()->hasAnyRole('Tenant', 'Pilot', 'Ground Support') ) {
            $query->where('tenant_id', auth()->user()->merchant_id);
        }

        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->has(['start_date', 'end_date'])) {
            $query->whereBetween('scheduled_date', [
                $request->start_date,
                $request->end_date
            ]);
        }

        if ($request->has('service_id')) {
            $query->where('service_id', $request->service_id);
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%$search%")
                    ->orWhereHas('service', function ($query) use ($search) {
                        $query->where('name', 'like', "%$search%");
                    })
                    ->orWhereHas('client', function ($query) use ($search) {
                        $query->where('business_name', 'like', "%$search%");
                    });
            });
        }

        $orders = $query->orderBy('scheduled_date', 'desc')->get();

        if ($orders->isEmpty()) {
            return response()->json([
                'success' => true,
                'message' => 'No hay órdenes disponibles.',
                'data' => []
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Listado de órdenes recuperado exitosamente.',
            'data' => [
                'orders' => OrdersResource::collection($orders)
            ]
        ]);
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $order = Order::select([
            'id',
            'order_number',
            'total_hectares',
            'total_amount',
            'status',
            'scheduled_date',
            'service_id',
            'client_id',
            'tenant_id',
            'aircraft_id',
            'pilot_id',
            'ground_support_id',
        ])->with([
            'service:id,name,description,price_per_hectare',
            'client:id,business_name,main_activity,fiscal_number,email,phone',
            'tenant:id,business_name',
            'aircraft:id,models,brand,working_width',
            'pilot:id,name,email',
            'groundsupport:id,name,email',
            'flights' => function ($query) {
                $query->select([
                    'id',
                    'order_id',
                    'flight_number',
                    'total_hectares',
                    'status',
                    'weather_conditions',
                    'observations'
                ])->with([
                    'flightProducts' => function ($q) {
                        $q->select(['id', 'flight_id', 'product_id', 'quantity'])
                            ->with('product:id,name,category_id,merchant_id,concentration,dosage_per_hectare,application_volume_per_hectare,stock');
                    },
                    'flightLots' => function ($q) {
                        $q->select(['id', 'flight_id', 'lot_id', 'hectares_to_apply', 'lot_total_hectares'])
                            ->with([
                                'lot:id,number,name_lot,hectares,merchant_id',
                                'lot.coordinates:id,lot_id,latitude,longitude,sequence_number,is_hole,hole_group'
                            ]);
                    }
                ]);
            },
            'orderProducts' => function ($query) {
                $query->select([
                    'id',
                    'order_id',
                    'product_id',
                    'client_provided_quantity',
                    'total_quantity_to_use',
                    'manual_total_quantity',
                    'calculated_dosage',
                    'product_difference',
                    'difference_observation',
                    'manual_dosage_per_hectare',

                ])->with('product:id,name,category_id,merchant_id,concentration,dosage_per_hectare,application_volume_per_hectare,stock');
            },
            'orderLots' => function ($query) {
                $query->select(['id', 'order_id', 'lot_id', 'hectares', 'status'])
                    ->with([
                        'lot:id,number,name_lot,hectares,merchant_id',
                        'lot.coordinates:id,lot_id,latitude,longitude,sequence_number,is_hole,hole_group'
                    ]);
            }
        ])->find($id);

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Orden no encontrada.',
                'data' => null
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Orden encontrada exitosamente.',
            'data' => new OrderResource($order),
        ]);
    }

    public function updateStatus(Request $request, $orderId)
    {
        try {

            $order = Order::find($orderId);

            if (!$order) {
                return response()->json([
                    'success' => false,
                    'message' => 'Orden no encontrada.',
                    'data' => null
                ], 404);
            }

            $order->status = $request->status;
            $order->update();

            return response()->json([
                'success' => true,
                'message' => 'Estado de la orden actualizado exitosamente.',
                'data' => [
                    'status' => $order->status
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar el estado de la orden.',
                'data' => null
            ], 500);
        }
    }

    public function updateOrderLot(Request $request, $orderId)
    {
        try {

            $order = OrderLot::find($orderId);


            if (!$order) {
                return response()->json([
                    'success' => false,
                    'message' => 'Orden no encontrada.',
                    'data' => null
                ], 404);
            }

            $order->status = $request->status;
            $order->update();

            return response()->json([
                'success' => true,
                'message' => 'Estado de la orden actualizado exitosamente.',
                'data' => [
                    'status' => $order->status
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar el estado de la orden.',
                'data' => null
            ], 500);
        }
    }
}
