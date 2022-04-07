<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PromoCode extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'code' => $this->code,
            'discount_amount' => $this->discount_amount,
            'description' => $this->description,
            'radius' => $this->radius,
            'radius_unit' => $this->radius_unit,
            'start_at' => $this->start_at,
            'end_at' => $this->end_at,
            'is_used' => $this->is_used,
            'is_active' => $this->is_active,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
        ];
    }
}
