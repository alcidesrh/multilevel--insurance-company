<?php

namespace App\Models;

use App\Models\User;
use App\Models\ModelBase;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Builder;

class Payment extends ModelBase
{
    use Notifiable;

    protected $table = 'payment';

    protected $fillable = [
		'payment_id',
		'amount',
		'currency',
		'status',
		'order_id',
		'user_id',
	];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    

    public function remitent() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function user() {
        return $this->belongsTo(User::class, 'user_target');
    }
    
}
