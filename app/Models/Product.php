<?php

/**
 * Created by Reliese Model.
 * Date: Fri, 09 Aug 2019 17:18:03 +0000.
 */

namespace App\Models;

// use App\Models\Company;
use App\Models\Category;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

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
class Product extends Model
{
	// use \Illuminate\Database\Eloquent\SoftDeletes;
	protected $table = 'products';

	protected $fillable = [
		'name',
		'insurer'
	];

	public function categories()
    {
        return $this->belongsToMany(Category::class);
	}
	
	// public function companies()
    // {
    //     return $this->belongsToMany(Company::class);
	// }
	
	protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('order', function (Builder $builder) {
            $builder->orderBy('created_at', 'desc');
        });
    }
}
