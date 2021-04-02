<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RecruitmentResource extends JsonResource
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
            'created_at' => $this->created_at,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'phone'=> $this->phone,
            'address' => $this->address,
            'city' => $this->city,
            'zip_code' => $this->zip_code,
            'citizenship' => $this->citizenship,
            'profession' => $this->profession,
            'knowfrom' => $this->knowfrom,
            'likejob' => $this->likejob,
            'dislikejob' => $this->dislikejob,
            'desireincome' => $this->desireincome,
            'attractivestatus' => $this->attractivestatus,
            'bigachievement' => $this->bigachievement,
            'interested_us' => $this->interested_us,
            'licenses' => $this->licenses,
            'second_interview_date' => $this->second_interview_date,
            'second_interview_assisted' => $this->second_interview_assisted,
            'hired' => $this->hired,
            'first_training_date' => $this->first_training_date,
            'first_training_assisted' => $this->first_training_assisted,
            'oxygen' => $this->oxygen,
            'second_training_date' => $this->second_training_date,
            'second_training_assisted' => $this->second_training_assisted,
            'referred_one_name' => $this->referred_one_name,
            'referred_one_email' => $this->referred_one_email,
            'referred_one_phone' => $this->referred_one_phone,
            'referred_two_name' => $this->referred_two_name,
            'referred_two_email' => $this->referred_two_email,
            'referred_two_phone' => $this->referred_two_phone,
            'referred_three_name' => $this->referred_three_name,
            'referred_three_email' => $this->referred_three_email,
            'referred_three_phone' => $this->referred_three_phone,
            'interview_reminder' => $this->interview_reminder,
            'referrer' => $this->referredBy ? $this->referredBy->name : null,
            'number' => $this->referredBy ? $this->referredBy->number_account : false
        ];
        
        foreach (['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'] as $value) {
            if($this->{$value}){
                $aux = \explode('-', $this->{$value});
                if(isset($aux[0])) {
                    $aux[0] = trim($aux[0]);
                    $return[$value.'1'] = trim($aux[0]);
                }
                if(isset($aux[1])) {
                    $aux[1] = trim($aux[1]);
                    $return[$value.'2'] = trim($aux[1]);
                }      
            }
            else list($return[$value.'1'], $return[$value.'2']) = [null, null];  
        }
        return $return;
    }
}
