<?php
namespace App\Models;

use App\Models\User;
use App\Models\Product;
use App\Models\ModelBase;
use Illuminate\Database\Eloquent\Builder;

class Company extends ModelBase
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'companies';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'parent_id', 'address', 'email', 'name', 'phone', 'active', 'code'];

    // Relationships
    public function owner()
    {
        return $this->hasOne(User::class, 'company_id')->owner();
    }
    public function brokers()
    {
        return $this->hasMany(User::class, 'company_id')->broker();
    }
    public function elites()
    {
        return $this->hasMany(User::class, 'company_id')->elite();
    }
    // public function clients() {
    //     return $this->hasMany(User::class)->client();
    // }
    // public function payments() {
    //     return $this->hasManyThrough(Payment::class, User::class)->nonChargeback();
    // }
    // public function policies() {
    //     return $this->hasManyThrough(Policy::class, Product::class);
    // }
    public function products()
    {
        return $this->belongsToMany(Product::class);
    }
    // public function reminders() {
    //     return $this->hasMany(Reminder::class);
    // }
    // public function staff() {
    //     return $this->hasMany(User::class)->staff();
    // }
    public function users()
    {
        return $this->hasMany(User::class);
    }
    public function parent()
    {
        return $this->belongsTo(Company::class, 'parent_id');
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
        return parent::delete();
    }

    //Commission
    public function totalCommission()
    {
        $total_commission = 0;
        $policies = $this->policies()->get();
        foreach ($policies as $policy) {
            $each_commission = ($policy->old_commission_rate / 100) * $policy->premium;
            $total_commission += $each_commission;
        }
        return $total_commission;
    }

    public function totalCommissionYear($year)
    {
        $y = $year;
        $total_commission = 0;
        $policies = $this->policies()->createdIn('year', $y)->get(); //->createdIn('month', '01')
        foreach ($policies as $policy) {
            $each_commission = ($policy->old_commission_rate / 100) * $policy->premium;
            $total_commission += $each_commission;
        }
        return $total_commission;
    }

    public function totalCommissionMonth($month)
    {
        $m = $month; //date('m',strtotime('0000-'.($month+1).'-00'));
        $total_commission = 0;
        $policies = $this->policies()->createdIn('month', $m)->get(); //->createdIn('month', '01')
        foreach ($policies as $policy) {
            $each_commission = ($policy->old_commission_rate / 100) * $policy->premium;
            $total_commission += $each_commission;
        }
        return $total_commission;
    }

    //Sales
    public function totalSalesMonth($month)
    {
        $m = $month; //date('m',strtotime('0000-'.($month+1).'-00'));
        $sales = 0;
        $policies = $this->policies()->createdIn('month', $m)->get(); //->createdIn('month', '01')
        foreach ($policies as $policy) {
            $sales += $policy->premium;
        }
        return $sales;
    }

    //Conversion
    public function conversionCommissionMonth($month)
    {
        $m = $month; //date('m',strtotime('0000-'.($month+1).'-00'));
        $commission = 0;
        $policies = $this->policies()->createdIn('month', $m)->get();
        foreach ($policies as $policy) {
            foreach ($policy->payments as $payment) {

                if ($payment->amount > 0) {
                    $commission += ($policy->old_commission_rate / 100) * $payment->amount;
                }
            }
        }

        return $commission;
    }

    //Payments
    public function totalPaymentsMonth($month)
    {
        $m = date('m', strtotime('0000-' . ($month + 1) . '-00'));
        $paid = 0;
        $payments = $this->payments()->madeWithin('month', $m)->get(); //->madeWithin('month', '01')
        foreach ($payments as $payment) {

            if ($payment->amount > 0) {
                $paid += $payment->amount;
            }
            return $paid;
        }
    }

    public function totalPayments()
    {
        $paid = 0;
        $policies = $this->policies()->get();
        foreach ($policies as $policy) {
            foreach ($policy->payments as $payment) {
                if ($payment->amount > 0) {
                    $paid += $payment->amount;
                }

            }
        }
        return $paid;
    }

    //Chargeback
    public function chargeBackYear($year)
    {
        $y = $year; //date('m',strtotime('0000-'.($month+1).'-00'));
        $total_chargeback = 0;
        $policies = $this->policies()->createdIn('year', $y)->get();
        foreach ($policies as $policy) {
            foreach ($policy->chargebacks as $chargeback) {

                $total_chargeback += $chargeback->amount;

            }
        }
        return abs($total_chargeback);
    }

    public function chargeBack()
    {
        $total_chargeback = 0;
        $policies = $this->policies()->get();
        foreach ($policies as $policy) {
            foreach ($policy->chargebacks as $chargeback) {

                $total_chargeback += $chargeback->amount;

            }
        }
        return abs($total_chargeback);
    }

}

//Backup
//Not the right results
/* public function conversionCommissionMonth($month) {
$m = $month;//date('m',strtotime('0000-'.($month+1).'-00'));
$commission = 0;
$payments = $this->payments()->madeWithin('month', $m)->get();//->madeWithin('month', '01')
foreach ($payments as $payment){

if ($payment->amount > 0)
{
$commission += ($payment->policy->old_commission_rate /100) * $payment->amount;
}
}
return $commission;
}*/
