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
            'totalHectares' => (double) $this->total_hectares,
            'totalAmount' => (double) $this->total_amount,
            'status' => $this->status,
            'scheduledDate' => $this->scheduled_date,
            'service' => [
                'id' => $this->service->id,
                'name' => $this->service->name,
                'description' => $this->service->description,
                'pricePerHectare' => (double) $this->service->price_per_hectare,
            ],
            'client' => [
                'id' => $this->client->id,
                'businessName' => $this->client->business_name,
                'mainActivity' => $this->client->main_activity,
                'fiscalNumber' => (double) $this->client->fiscal_number,
                'email' => $this->client->email,
                'phone' => $this->client->phone,
            ],
            'tenant' => [
                'id' => $this->tenant->id,
                'businessName' => $this->tenant->business_name,
            ],
            'aircraft' => [
                'id' => $this->aircraft->id,
                'model' => $this->aircraft->models,
                'brand' => $this->aircraft->brand,
                'workingWidth' => (double) $this->aircraft->working_width,
            ],
            'pilot' => [
                'id' => $this->pilot->id,
                'name' => $this->pilot->name,
                'email' => $this->pilot->email,
            ],
            'groundSupport' => [
                'id' => (int) $this->groundsupport->id,
                'name' => $this->groundsupport->name,
                'email' => $this->groundsupport->email,
            ],
            'flights' => $this->flights->map(function ($flight) {
                return [
                    'id' => (int) $flight->id,
                    'orderId' => (int) $flight->order_id,
                    'flightNumber' => (int) $flight->flight_number,
                    'totalHectares' => (double) $flight->total_hectares,
                    'status' => $flight->status,
                    'weatherConditions' => $flight->weather_conditions,
                    'observations' => $flight->observations,
                    'products' => $flight->flightProducts->map(function ($product) {
                        return [
                            'id' => $product->id,
                            'flightId' => $product->flight_id,
                            'productId' => $product->product_id,
                            'quantity' => (double) $product->quantity,
                            'product' => [
                                'id' => $product->product->id,
                                'name' => $product->product->name,
                                'categoryId' => $product->product->category_id,
                                'merchantId' => $product->product->merchant_id,
                                'concentration' => (double) $product->product->concentration,
                                'recommendedDosagePerHectare' => (double) $product->product->dosage_per_hectare,
                                'recommendedApplicationVolumePerHectare' => (double) $product->product->application_volume_per_hectare,
                                'stock' => (double) $product->product->stock,
                            ],
                        ];
                    }),
                    'lots' => $flight->flightLots->map(function ($lot) {
                        return [
                            'id' => $lot->id,
                            'flightId' => $lot->flight_id,
                            'lotId' => $lot->lot_id,
                            'hectaresToApply' => (double) $lot->hectares_to_apply,
                            'lotTotalHectares' => (double) $lot->lot_total_hectares,
                            'lot' => [
                                'id' => $lot->lot->id,
                                'numbering' => $lot->lot->number,
                                'hectares' => (double) $lot->lot->hectares,
                                'merchantId' => $lot->lot->merchant_id,
                                'coordinates' => $lot->lot->coordinates->map(function ($coordinate) {
                                    return [
                                        'id' => $coordinate->id,
                                        'latitude' => (double) $coordinate->latitude,
                                        'longitude' => (double) $coordinate->longitude,
                                    ];
                                }),
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
                    'clientProvidedQuantity' => (double) $orderProduct->client_provided_quantity,
                    'totalQuantityToUse' => (double) $orderProduct->total_quantity_to_use,
                    'manualTotalQuantity' => (double) $orderProduct->manual_total_quantity,
                    'calculatedDosage' => (double) $orderProduct->calculated_dosage,
                    'productDifference' => (double) $orderProduct->product_difference,
                    'differenceObservation' => $orderProduct->difference_observation,
                    'manualDosagePerHectare' => (double) $orderProduct->manual_dosage_per_hectare,
                    'product' => [
                        'id' => $orderProduct->product->id,
                        'name' => $orderProduct->product->name,
                        'categoryId' => $orderProduct->product->category_id,
                        'merchantId' => $orderProduct->product->merchant_id,
                        'concentration' => (double) $orderProduct->product->concentration,
                        'recommendedDosagePerHectare' => (double) $orderProduct->product->dosage_per_hectare,
                        'recommendedApplicationVolumePerHectare' => (double) $orderProduct->product->application_volume_per_hectare,
                        'stock' => (double) $orderProduct->product->stock,
                    ],
                ];
            }),
            'orderLots' => $this->orderLots->map(function ($orderLot) {
                return [
                    'id' => $orderLot->id,
                    'orderId' => $orderLot->order_id,
                    'lotId' => $orderLot->lot_id,
                    'hectares' => (double) $orderLot->hectares,
                    'lot' => [
                        'id' => $orderLot->lot->id,
                        'numbering' => $orderLot->lot->number,
                        'hectares' => (double) $orderLot->lot->hectares,
                        'merchantId' => $orderLot->lot->merchant_id,
                        'coordinates' => $orderLot->lot->coordinates->map(function ($coordinate) {
                            return [
                                'id' => $coordinate->id,
                                'latitude' => (double) $coordinate->latitude,
                                'longitude' => (double) $coordinate->longitude,
                            ];
                        }),
                    ],
                ];
            }),
        ];
    }
}
