<?php

namespace App\Http\Resources;

use Illuminate\Support\Facades\DB;
use Alcidesrh\Generic\GenericResource;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{

    private $userPay;

    public function __construct($query, $userPay = false)
    {
        $this->userPay = $userPay;
        parent::__construct($query);
    }
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
            'name' => $this->name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'phone'=> $this->phone,
            'image' => $this->image ? new GenericResource($this->image, ['id', 'name', 'url']) : null,
            'role' => $this->role->slug,
            'company_id' => $this->company->id ?? null,
        ];
    }
}
