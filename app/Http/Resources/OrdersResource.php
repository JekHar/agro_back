<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrdersResource extends JsonResource
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
            ],
            'client' => [
                'id' => $this->client->id,
                'businessName' => $this->client->business_name,
            ],
        ];
    }
}
