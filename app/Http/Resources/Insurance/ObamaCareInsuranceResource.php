<?php

namespace App\Http\Resources\Insurance;

use Alcidesrh\Generic\GenericResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Alcidesrh\Generic\GenericResourceCollection;

class ObamaCareInsuranceResource extends JsonResource
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
            'type' => $this->insurance ?? 'Nuevo seguro',
            'created_at' => $this->created_at,
            'members' => $this->members 
        ];
    }
}
