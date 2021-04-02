<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Alcidesrh\Generic\GenericResource;
use Illuminate\Http\Resources\Json\JsonResource;

class UserChildrenResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $user = Auth::user();

        $return = [
            'id' => $this->id,
            'name' => $this->name,
            'image' => $this->image ? new GenericResource($this->image, ['id', 'name', 'url']) : null
        ];
        if($user->role_id == User::getRole('agency') || $user->role_id == User::getRole('admin')){
            $return['children'] = UserChildrenResource::collection($this->children);
        }

        return $return;
    }
}
