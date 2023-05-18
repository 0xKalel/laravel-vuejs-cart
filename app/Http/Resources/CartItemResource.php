<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CartItemResource extends JsonResource
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
            'cart_id' => $this->cart_id,
            'selectedQuantity' => $this->quantity,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            $this->mergeWhen($this->whenLoaded('product'), [
                'name' => $this->product->name,
                'description' => $this->product->description,
                'quantity' => $this->product->quantity,
                'price' => $this->product->price,
                'id' => $this->product_id,
            ]),
        ];
    }
}