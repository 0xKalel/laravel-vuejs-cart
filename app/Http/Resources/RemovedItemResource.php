<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RemovedItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'session_id' => $this->session_id,
            'cart_id' => $this->cart_id,
            'product_id' => $this->product_id,
            'removed_at' => $this->removed_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}