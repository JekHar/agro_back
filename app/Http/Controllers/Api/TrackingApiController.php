<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TrackingRoute;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TrackingApiController extends Controller
{
    public function store(Request $request)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'orderLotId' => 'required|exists:order_lots,id',
            'userId' => 'required|exists:users,id',
            'route' => 'required|array',
            'route.*.latitude' => 'required|numeric',
            'route.*.longitude' => 'required|numeric',
            'route.*.timestamp' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'status' => 'error'
            ], 422);
        }

        $startTime = null;
        $endTime = null;

        if (!empty($request->route)) {
            $sortedPoints = collect($request->route)->sortBy(function ($point) {
                return isset($point['timestamp']) ? $point['timestamp'] : null;
            });

            if ($sortedPoints->count() > 0) {
                $firstPoint = $sortedPoints->first();
                $lastPoint = $sortedPoints->last();
                
                $startTime = isset($firstPoint['timestamp']) ? $firstPoint['timestamp'] : now();
                $endTime = isset($lastPoint['timestamp']) ? $lastPoint['timestamp'] : now();
            }
        }

        try {
            $trackingRoute = TrackingRoute::create([
                'order_lot_id' => $request->orderLotId,
                'user_id' => $request->userId,
                'route_data' => $request->route,
                'started_at' => $startTime,
                'finished_at' => $endTime,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Tracking data saved successfully',
                'data' => $trackingRoute,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error saving tracking data',
                'status' => 'error'
            ], 500);
        }
    }

    public function getRoutesByOrderLot($orderLotId)
    {
        try {
            $routes = TrackingRoute::where('order_lot_id', $orderLotId)
                ->with('user:id,name')
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'message' => 'Tracking routes retrieved successfully',
                'data' => $routes,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving tracking routes',
                'status' => 'error'
            ], 500);
        }
    }
}