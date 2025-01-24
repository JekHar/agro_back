<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderApiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
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
            'client_id'
        ])->with([
            'service:id,name',
            'client:id,business_name'
        ]);

        if ($request->has('status')) {
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

        $orders = $query->orderBy('scheduled_date', 'desc')
            ->paginate($request->get('per_page', 15));

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
                'orders' => $orders->items(),
                'total' => $orders->total(),
                'current_page' => $orders->currentPage(),
                'per_page' => $orders->perPage()
            ]
        ]);
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
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
            'aircraft:id,models,brand',
            'pilot:id,name',
            'groundsupport:id,name',
            'flights' => function ($query) {
                $query->select([
                    'id',
                    'order_id',
                    'flight_number',
                    'total_hectares',
                    'status',
                    'started_at',
                    'completed_at',
                    'observations'
                ])->with([
                    'flightProducts' => function ($q) {
                        $q->select(['id', 'flight_id', 'product_id', 'quantity'])
                            ->with('product:id,name');
                    },
                    'flightLots' => function ($q) {
                        $q->select(['id', 'flight_id', 'lot_id', 'hectares_to_apply'])
                            ->with('lot:id,number,hectares');
                    }
                ]);
            },
            'orderProducts' => function ($query) {
                $query->select([
                    'id',
                    'order_id',
                    'product_id',
                    'client_provided_quantity',
                    'total_quantity_to_use'
                ])->with('product:id,name');
            },
            'orderLots' => function ($query) {
                $query->select(['id', 'order_id', 'lot_id', 'hectares'])
                    ->with('lot:id,number,hectares');
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
            'data' => $order
        ]);
    }
}
