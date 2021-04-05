<?php

namespace App\Http\Resources;

use App\Models\User;
use Alcidesrh\Generic\GenericResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ComputerReservationResource extends JsonResource
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
            'computer' => $this->computer,
            'day' => $this->day,
            'turn' => $this->turn,
            'user' => new GenericResource(User::find($this->user_id), ['name', ['image' => ['url']], ['role' => ['name']]]),

        ];
    }
}
