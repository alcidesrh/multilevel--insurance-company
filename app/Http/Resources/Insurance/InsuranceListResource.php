<?php

namespace App\Http\Resources\Insurance;

use Alcidesrh\Generic\GenericResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Alcidesrh\Generic\GenericResourceCollection;

class InsuranceListResource extends JsonResource
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
            'client' => new GenericResource($this->client, ['id', 'name', 'last_name', 'email', 'phone']),
            'files' => new GenericResourceCollection($this->files, ['id', 'name', 'path']),
            'created_at' => $this->created_at,
            'sent' => $this->sent
        ];
    }
}
