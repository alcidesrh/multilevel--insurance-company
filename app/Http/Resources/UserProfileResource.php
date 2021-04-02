<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Alcidesrh\Generic\GenericResource;
use App\Http\Resources\UserChildrenResource;
use Illuminate\Http\Resources\Json\JsonResource;

class UserProfileResource extends JsonResource
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
            'id' => $this->id,
            'full_name' => $this->name . ' ' . $this->last_name,
            'email' => $this->email,
            'phone'=> $this->phone,
            'image' => $this->image ? new GenericResource($this->image, ['id', 'name', 'url']) : null,            
            'children' => UserChildrenResource::collection($this->children),
            'birthday' => $this->birthday,
            'active' => $this->active,
            'role' => $this->role->slug,
            'parent_id' => $this->parent->id ?? null,
            'company_id' => $this->company->id ?? null,

        ];

        $user = Auth::user();

        if($user->role_id == User::getRole('admin')){
            $return['parent'] = $this->parent ? ['name' => $this->parent->name, 'image' => $this->parent->image, 'id' => $this->parent->id] : null;
        }
        return $return;
    }
}
