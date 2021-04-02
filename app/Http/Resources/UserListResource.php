<?php

namespace App\Http\Resources;

use Alcidesrh\Generic\GenericResource;
use Alcidesrh\Generic\GenericResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;

class UserListResource extends JsonResource
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
            'name' => $this->is('agency') && $this->company ? $this->company->name : $this->name,
            'fullName' => $this->fullName,
            'email' => $this->email,
            'phone'=> $this->phone,
            'image' => $this->image ? new GenericResource($this->image, ['id', 'name', 'url']) : null,
            'number_account' => $this->number_account,
            // 'company' => $this->company->name ?? null,
            // 'company_id' => $this->company->id ?? null,
            'parent' => $this->parent->name ?? null,
            'parent_id' => $this->parent->id ?? null,
            'parent_number' => $this->parent->number_account ?? null,
            'role' => $this->role->slug,
            'root_id' => $this->getOriginalAttribute('root_id'),
            'parent_data' => new GenericResource($this->parent, ['licenses', 'id', ['subscription' => ['id']]])


        ];
    }
}
