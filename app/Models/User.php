<?php

namespace App\Models;

// use App\Models\Company;
use App\Models\License;
use App\Models\Role;
use App\Models\Subscription;
use Illuminate\Auth\Passwords\CanResetPassword as CanResetPasswordTrait;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable implements CanResetPassword
{
    use Notifiable, CanResetPasswordTrait;

    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = ['root_id','subscription_id', 'salary', 'parent_id', 'role_id', 'id', 'founder', 'password', 'address', 'birthday', 'commission_rate', 'email', 'name', 'last_name', 'phone', 'number_account', 'title', 'active'];
    
    // protected $fillable = ['root_id','subscription_id', 'salary', 'parent_id', 'company_id', 'role_id', 'id', 'founder', 'password', 'address', 'birthday', 'commission_rate', 'email', 'name', 'last_name', 'phone', 'number_account', 'title', 'active'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getFullNameAttribute()
    {
        return $this->name . ' ' . $this->last_name;
    }

    public function getFullNameAndAcodeAttribute()
    {
        return $this->name . ' ' . $this->last_name . ' (' . $this->number_account . ')';
    }

    public function getRootIdAttribute($value)
    {
        return $this->is('agency') ? $this->id : $value;
    }

    public function getOriginalAttribute($field){
        return $this->attributes[$field];
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function subscription()
    {
        return $this->belongsTo(Subscription::class, 'subscription_id');
    }

    public function licenses()
    {
        return $this->belongsToMany(License::class, 'license_user');
    }

    public function parent()
    {
        return $this->belongsTo(User::class, 'parent_id');
    }

    public function root()
    {
        return $this->belongsTo(User::class, 'root_id');
    }

    public function getCompanyNameAttribute(){

        if($this->is('agency')) return $this->company->name ?? $this->root->company->name ?? $this->name;
        
        return $this->root->company->name ?? $this->company->name ?? $this->root->name ?? null;
    }

    public function children()
    {
        return $this->hasMany(User::class, 'parent_id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function scopeOwner($query)
    {
        return $query->where('role_id', self::getRole('agency'))->first();
    }

    public function scopeElite($query)
    {
        return $query->where('role_id', 3);

    }

    public function scopeBroker($query)
    {
        return $query->where('role_id', 4);
    }    

    public function is($role)
    {
        if (\is_array($role)) {
            foreach ($role as $value) {
                if ($this->role_id == self::getRole($value)) {
                    return true;
                }
            }
            return false;
        }
        return $this->role_id == self::getRole($role);
    }

    public static function getRole($param)
    {

        $roles = [
            'admin' => 1,
            'agency' => 2,
            'elite' => 3,
            'broker' => 4,
            'staff' => 5,
            'client' => 6,
        ];
        return is_numeric($param) ? array_search($param, $roles) : $roles[$param];
    }

    public static function generateNumberAccount()
    {
        $userNumber = DB::table('user_number')->first();
        if ($userNumber->number < 999) {
            DB::table('user_number')->where('id', $userNumber->id)->update(['number' => $userNumber->number + 1]);
            switch (\strlen($userNumber->number)) {
                case 1:return "{$userNumber->letter}000{$userNumber->number}";
                case 2:return "{$userNumber->letter}00{$userNumber->number}";
                case 3:return "{$userNumber->letter}0{$userNumber->number}";
            }
        } else {
            $letters = range('D', 'Z');
            for ($i = 0; $i < count($letters); $i++) {
                if ($letters[$i] == $userNumber->letter) {
                    $userNumber->letter = $letters[$i + 1];
                    DB::table('user_number')->where('id', $userNumber->id)->update(['letter' => $letters[$i + 1], 'number' => 1]);
                    return $letters[$i + 1] . "01";
                }
            }
        }
    }

    public function image()
    {
        return $this->morphOne('App\Models\File', 'fileable');
    }

    public function delete()
    {
        if ($img = $this->image) {
            $img->delete();
        }
        DB::table('reset_password')->where('email', $this->email)->delete();

        return parent::delete();
    }

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('order', function (Builder $builder) {
            $builder->orderBy('users.created_at', 'desc');
        });
    }
}
