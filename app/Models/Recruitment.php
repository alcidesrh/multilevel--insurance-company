<?php

/**
 * Created by Reliese Model.
 * Date: Fri, 09 Aug 2019 17:18:03 +0000.
 */

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

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
class Recruitment extends Model
{
    // use \Illuminate\Database\Eloquent\SoftDeletes;
    protected $table = 'recruitment';

    

    protected $fillable = [
        'id',
        'first_name',
        'last_name',
        'email',
        'phone',
        'address',
        'city',
        'zip_code',
        'citizenship',
        'profession',
        'knowfrom',
        'likejob',
        'dislikejob',
        'desireincome',
        'attractivestatus',
        'bigachievement',
        'interested_us',
        'licenses',
        'monday',
        'tuesday',
        'wednesday',
        'thursday',
        'friday',
        'saturday',
        'second_interview_date',
        'second_interview_assisted',
        'hired',
        'first_training_date',
        'first_training_assisted',
        'oxygen',
        'second_training_date',
        'second_training_assisted',
        'referred_one_name',
        'referred_one_email',
        'referred_one_phone',
        'referred_two_name',
        'referred_two_email',
        'referred_two_phone',
        'referred_three_name',
        'referred_three_email',
        'referred_three_phone',
        'interview_reminder',
    ];

    public function referredBy()
    {
        return $this->belongsTo(User::class, 'referred_by');
	}
	
	protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('order', function (Builder $builder) {
            $builder->orderBy('created_at', 'desc');
        });
    }
}
