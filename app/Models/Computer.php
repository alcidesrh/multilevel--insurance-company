<?php

/**
 * Created by Reliese Model.
 * Date: Fri, 09 Aug 2019 17:18:03 +0000.
 */

namespace App\Models;

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
class Computer extends ModelBase
{
	// use \Illuminate\Database\Eloquent\SoftDeletes;
	protected $table = 'computer_reservation';

	protected $fillable = [
		'computer',
		'user_id',
		'day',
		'turn',
	];

	public function user() {
        return $this->belongsTo(Category::class, 'user_id');
	}
	
}
