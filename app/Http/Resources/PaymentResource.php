<?php

namespace App\Http\Resources;

use App\Models\User;
use Alcidesrh\Generic\GenericResource;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentResource extends JsonResource
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
            'created_at' => $this->created_at,
            'id' => $this->payment_id,
            'status' => $this->status,
            'amount' => $this->amount,
            'type' => $this->type,
            'action' => $this->action,
            'user' => new GenericResource(User::find($this->user_id), ['name', ['image' => ['url']], ['role' => ['name']]]),
            'user2' => $this->user_target ? new GenericResource(User::find($this->user_target), ['name', ['image' => ['url']], ['role' => ['name']]]) : null,
        ];
    }
}
