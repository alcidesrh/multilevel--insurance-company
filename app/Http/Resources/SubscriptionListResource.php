<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Resources\Json\JsonResource;

class SubscriptionListResource extends JsonResource
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
            'title' => $this->title,
            'description' => $this->description,
            'price' => $this->price,
            'active' => $this->active,
            'role' => (string) $this->role,
            'duration' => $this->duration,
            'type' => $this->type,
            'users' => DB::table('users')->where('subscription_id', $this->id)->count()
        ];
    }
}
