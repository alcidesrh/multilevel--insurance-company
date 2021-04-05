<?php

namespace App\Http\Resources;

use Illuminate\Support\Facades\DB;
use App\Models\Insurance\InsuranceCar;
use Alcidesrh\Generic\GenericResource;
use App\Models\Insurance\InsuranceObama;
use Illuminate\Http\Resources\Json\JsonResource;

class UserLoginResource extends JsonResource
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
        
        $return = [
            'id' => $this->id,
            'name' => $this->name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'phone'=> $this->phone,
            'image' => $this->image ? new GenericResource($this->image, ['id', 'name', 'url']) : null,
            'role' => $this->role->slug,
            'company_id' => $this->company->id ?? null,
            'renew' => false,//!$this->is('admin') && !((bool) $this->subscription_renew),
            'userPay' => false//(bool)DB::table('reset_password')->where('email', $this->email)->where('user_pay', 1)->count()
        ];

        // if($this->is('agency')){
        //     $return['notification'] = [
        //         'insurance' => [
        //         'obama' => InsuranceObama::select('insurance_product.*')->join('users', 'users.id', '=', 'insurance_product.user_id')
        //         ->where('users.root_id', $this->id)->whereNull('sent')->count(),
        //         'car' => InsuranceCar::select('insurance_car.*')->join('users', 'users.id', '=', 'insurance_car.user_id')
        //         ->where('users.root_id', $this->id)->whereNull('sent')->count()
        //         ]
        //     ];            
        // }

        return $return;
    }
}
