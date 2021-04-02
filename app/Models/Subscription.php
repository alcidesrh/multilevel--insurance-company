<?php

namespace App\Models;

use App\Models\Role;
use App\Models\User;
// use App\Models\Company;
use App\Models\ModelBase;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Builder;

class Subscription extends ModelBase
{
    use Notifiable;

    protected $table = 'subscriptions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = ['id', 'title', 'description', 'role_id', 'id', 'price', 'duration', 'active', 'type'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'created_at', 'updated_at',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_subscription');
    }
}
