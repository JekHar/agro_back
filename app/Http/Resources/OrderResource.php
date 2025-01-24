<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'orderNumber' => $this->order_number,
            'totalHectares' => $this->total_hectares,
            'totalAmount' => $this->total_amount,
            'status' => $this->status,
            'scheduledDate' => $this->scheduled_date,
            'service' => [
                'id' => $this->service->id,
                'name' => $this->service->name,
                'description' => $this->service->description,
                'pricePerHectare' => $this->service->price_per_hectare,
            ],
            'client' => [
                'id' => $this->client->id,
                'businessName' => $this->client->business_name,
                'mainActivity' => $this->client->main_activity,
                'fiscalNumber' => $this->client->fiscal_number,
                'email' => $this->client->email,
                'phone' => $this->client->phone,
            ],
            'tenant' => [
                'id' => $this->tenant->id,
                'businessName' => $this->tenant->business_name,
            ],
            'aircraft' => [
                'id' => $this->aircraft->id,
                'models' => $this->aircraft->models,
                'brand' => $this->aircraft->brand,
            ],
            'pilot' => [
                'id' => $this->pilot->id,
                'name' => $this->pilot->name,
            ],
            'groundSupport' => [
                'id' => $this->groundsupport->id,
                'name' => $this->groundsupport->name,
            ],
            'flights' => $this->flights->map(function ($flight) {
                return [
                    'id' => $flight->id,
                    'orderId' => $flight->order_id,
                    'flightNumber' => $flight->flight_number,
                    'totalHectares' => $flight->total_hectares,
                    'status' => $flight->status,
                    'startedAt' => $flight->started_at,
                    'completedAt' => $flight->completed_at,
                    'observations' => $flight->observations,
                    'flightProducts' => $flight->flightProducts->map(function ($product) {
                        return [
                            'id' => $product->id,
                            'flightId' => $product->flight_id,
                            'productId' => $product->product_id,
                            'quantity' => $product->quantity,
                            'product' => [
                                'id' => $product->product->id,
                                'name' => $product->product->name,
                            ],
                        ];
                    }),
                    'flightLots' => $flight->flightLots->map(function ($lot) {
                        return [
                            'id' => $lot->id,
                            'flightId' => $lot->flight_id,
                            'lotId' => $lot->lot_id,
                            'hectaresToApply' => $lot->hectares_to_apply,
                            'lot' => [
                                'id' => $lot->lot->id,
                                'number' => $lot->lot->number,
                                'hectares' => $lot->lot->hectares,
                            ],
                        ];
                    }),
                ];
            }),
            'orderProducts' => $this->orderProducts->map(function ($orderProduct) {
                return [
                    'id' => $orderProduct->id,
                    'orderId' => $orderProduct->order_id,
                    'productId' => $orderProduct->product_id,
                    'clientProvidedQuantity' => $orderProduct->client_provided_quantity,
                    'totalQuantityToUse' => $orderProduct->total_quantity_to_use,
                    'product' => [
                        'id' => $orderProduct->product->id,
                        'name' => $orderProduct->product->name,
                    ],
                ];
            }),
            'orderLots' => $this->orderLots->map(function ($orderLot) {
                return [
                    'id' => $orderLot->id,
                    'orderId' => $orderLot->order_id,
                    'lotId' => $orderLot->lot_id,
                    'hectares' => $orderLot->hectares,
                    'lot' => [
                        'id' => $orderLot->lot->id,
                        'number' => $orderLot->lot->number,
                        'hectares' => $orderLot->lot->hectares,
                    ],
                ];
            }),
        ];
    }
}
