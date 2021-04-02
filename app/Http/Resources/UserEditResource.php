<?php

namespace App\Http\Resources;

use Alcidesrh\Generic\GenericResource;
use Alcidesrh\Generic\GenericResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;

class UserEditResource extends JsonResource
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
            'company_name' => $this->is('agency') && $this->company ? $this->company->name : null,
            'name' => $this->name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'phone' => $this->phone,
            'image' => $this->image ? new GenericResource($this->image, ['id', 'name', 'url']) : null,
            'children' => $this->children ? new GenericResourceCollection($this->children, ['id', 'name', 'image']) : null,
            'parent' => $this->parent ? new GenericResource($this->parent, ['id', 'name', 'image']) : null,
            'address' => $this->address,
            'birthday' => $this->birthday,
            'title' => $this->title,
            'active' => $this->active,
            'licensed' => $this->licensed,
            'subscription_id' => $this->subscription_id,
            'commission_rate' => $this->commission_rate,
            'role' => new GenericResource($this->role, ['id', 'name', 'slug']),
            'company_id' => $this->company->id ?? null,
            'salary' => $this->salary,
            'licenses' => $this->licenses->map(function ($item) {
                return $item->id;
            }),

        ];
    }
}
