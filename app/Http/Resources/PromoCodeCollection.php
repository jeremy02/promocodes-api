<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class PromoCodeCollection extends ResourceCollection {
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'data' => $this->collection, // the data retrieved for the respecting page
            'meta' => [ // these are the pagination metadata
                'current_page' => $this->currentPage(),
                'total' => $this->total(),
                'count' => $this->count(),
                'per_page' => $this->perPage(),
                'total_pages' => $this->lastPage()
            ],
        ];
    }
}
