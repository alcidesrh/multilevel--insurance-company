<?php

/**
 * Created by Reliese Model.
 * Date: Fri, 09 Aug 2019 17:18:03 +0000.
 */

namespace App\Models;

use App\Models\ModelBase;
use App\Models\Insurance\Dependent;

/**
 *
 * @package App\Models
 */
class Client extends ModelBase
{
	// use \Illuminate\Database\Eloquent\SoftDeletes;
	protected $table = 'client';

	public function getFullNameAttribute()
    {
        return $this->name . ' ' . $this->last_name;
    }

    public function dependentsList(){
		return $this->hasMany(Dependent::class, 'client_id');
	}
}
