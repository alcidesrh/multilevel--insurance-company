<?php

namespace App\Http\Resources;

use App\Models\User;
use App\Http\Resources\UserResource;
use Alcidesrh\Generic\GenericResource;
use Illuminate\Http\Resources\Json\JsonResource;

class CompanyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
         $owner = $this->owner();
         $image = $this->image;
         
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'address' => $this->address,
            'phone' => $this->phone,
            'code' => $this->code,
            'owner' => $owner instanceof User ? new UserResource($owner) : null,
            'staff' => User::where('company_id', $this->id)->count(),
            'image' => $image ? new GenericResource($image, ['id', 'name', 'url']) : null


        ];
    }
}
