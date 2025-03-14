<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TrackingRoute;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TrackingController extends Controller
{
    public function store(Request $request)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'orderLotId' => 'required|exists:order_lots,id',
            'route' => 'required|array',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors(),
                'status' => 'error'
            ], 422);
        }

        // Determine the start and end times from the route data
        $startTime = null;
        $endTime = null;

        if (!empty($request->route)) {
            // Sort the route points by timestamp
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

        // Create the tracking route
        $trackingRoute = TrackingRoute::create([
            'order_lot_id' => $request->orderLotId,
            'user_id' => Auth::id(),
            'route_data' => $request->route,
            'started_at' => $startTime,
            'finished_at' => $endTime,
        ]);

        return response()->json([
            'message' => 'Tracking data saved successfully',
            'data' => $trackingRoute,
            'status' => 'success'
        ], 201);
    }

    public function getRoutesByOrderLot($orderLotId)
    {
        $routes = TrackingRoute::where('order_lot_id', $orderLotId)
            ->with('user:id,name')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'data' => $routes,
            'status' => 'success'
        ]);
    }
}