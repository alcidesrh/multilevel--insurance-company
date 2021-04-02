<?php

namespace App\Models;

use App\Models\User;
use App\Models\ModelBase;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Builder;

class License extends ModelBase
{
    use Notifiable;

    protected $table = 'licenses';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = ['id', 'title'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'created_at', 'updated_at',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
