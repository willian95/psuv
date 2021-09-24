<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $return = [
            "id" => $this->id,
            "total" =>  $this->total,
            "order_status_id" => $this->order_status_id,
            "created_at" =>  $this->created_at->format('d-m-Y g:i a'),
            "updated_at" =>  $this->updated_at->format('d-m-Y g:i a'),
            "status"=>$this->when($this->relationLoaded("status"),$this->status),
            "payment_method_id" => $this->payment_method_id,
        ];
       
        return $return;
    }
}
