<?php

namespace App\Http\Resources;

use App\Models\Client;
use Alcidesrh\Generic\GenericResource;
use Alcidesrh\Generic\GenericResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;

class EditInsuranceResource extends JsonResource
{

    public function __construct($query)
    {
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

        $array = \get_object_vars($this)['resource']->toArray();

        // if(isset($array['user_id']) && !empty($array['user_id'])){
        //     $array['user'] = new GenericResource(User::find($array['user_id']), ['id', 'name', 'image']);
        //     unset($array['user_id']);
        // }

        if (isset($array['client_id']) && !empty($array['client_id'])) {
            $client = Client::find($array['client_id']);
            $array['client'] = new GenericResource($client);

            $array['client']['dependents'] = 0;
            $array['client']['dependents_list'] = [];
            
            if ($number = $client->dependentsList()->count()) {
                $array['client']['dependents'] = $number;
                $array['client']['dependents_list'] = new GenericResourceCollection($client->dependentsList, 'original');
            }
            unset($array['client_id']);
        } else {
            $array['client'] = [];
        }

        // if(isset($array['company']) && !empty($array['company'])){
        //     $array['company'] = new GenericResource(DB::table('companies')->find($array['company']), ['id', 'name']);
        // }

        unset($array['created_at'], $array['updated_at']);

        $array['files'] = new GenericResourceCollection($this->files, ['id', 'name', 'size']);

        return $array;
    }
}
