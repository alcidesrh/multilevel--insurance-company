<?php

/**
 * Created by Reliese Model.
 * Date: Fri, 09 Aug 2019 17:18:03 +0000.
 */

namespace App\Models\Insurance;

use App\Models\User;
use App\Models\Client;
use App\Models\ModelBase;

/**
 * Class Role
 * 
 * @property int $id
 * @property string $name
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $created_at
 * @property string $deleted_at
 * 
 * @property \Illuminate\Database\Eloquent\Collection $users
 *
 * @package App\Models
 */
class InsuranceBase extends ModelBase
{
	protected $guarded = []; 
	// use \Illuminate\Database\Eloquent\SoftDeletes;
	protected $table = 'insurance_car';

	public function files() {
        return $this->morphMany('App\Models\File', 'fileable');
	}
	
	public function user() {
        return $this->belongsTo(User::class, 'user_id');
	}
	
	public function client() {
        return $this->belongsTo(Client::class, 'client_id');
	}

	public function delete(array $data = [])
    {

        $this->client->delete();

        foreach ($this->files as $file) {
            $file->delete(['disk' => 'files']);
        }

        
        return parent::delete();
    }	
}
