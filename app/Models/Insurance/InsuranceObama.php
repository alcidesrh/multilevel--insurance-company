<?php

/**
 * Created by Reliese Model.
 * Date: Fri, 09 Aug 2019 17:18:03 +0000.
 */

namespace App\Models\Insurance;

use App\Models\Insurance\InsuranceBase;
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
class InsuranceObama extends InsuranceBase
{
	protected $guarded = []; 
	// use \Illuminate\Database\Eloquent\SoftDeletes;
    protected $table = 'insurance_product';	
    

	// protected static function boot()
    // {
    //     parent::boot();

    //     static::addGlobalScope('order', function (Builder $builder) {
    //         $builder->orderBy('insurance_product.created_at', 'desc');
    //     });
    // }
    
}
