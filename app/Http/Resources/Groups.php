<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Groups extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        /*
        return [
            'id' => $this->id,
            'message' => $this->message
        ];
        */
        return parent::toArray($request);
    }
}
