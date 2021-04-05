<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Client;
use App\Models\Subscription;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Models\Insurance\InsuranceCar;
use App\Models\Insurance\InsuranceHome;
use App\Models\Insurance\InsuranceObama;
use App\Models\Insurance\InsuranceBusiness;
use App\Mail\Insurance\ObamaCareInsuranceEmail;

class Util extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:util';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    public $ids;

    public function getRoot($user){
        if(\in_array($user->id, $this->ids))return null;
        $this->ids[] = $user->id;
        if($user->is('agency') && !$user->parent) return $user;
        elseif($user->parent) return $this->getRoot($user->parent);
        return null;
    }
    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $password = Hash::make('123');
        return;
        $this->info((now())->format('d/m/y'));
        $this->info(InsuranceObama::whereBetween('created_at', [(now())->sub('5 days')->startOfDay(), (now())->sub('5 days')->endOfDay()])->count());
        $cont = 1;

        foreach (InsuranceObama::whereBetween('created_at', [(now())->sub('5 days')->startOfDay(), (now())->sub('5 days')->endOfDay()])->get() as $value) {
            $this->info($cont++);
            try {
                Mail::to(true ? ['appobama@dnfinsurance.com', 'aplications@aygsalas.com'] : ['appobama@dnfinsurance.com', 'aplications@aygsalas.com'])->send(new ObamaCareInsuranceEmail($value));
            } catch (\Exception $e) {

                return $this->info($e->getMessage());
            }
        }
        $this->info('Done!!');

        // DB::table('dependent')->where('earn_type', 'BB2')->update(['earn_type' => 'W2']);
        // DB::table('insurance_product')->where('spouse_earn_type', 'BB2')->update(['spouse_earn_type' => 'W2']);
        // DB::table('client')->where('earn_type', 'BB2')->update(['earn_type' => 'W2']);
        // foreach (User::where('role_id', '!=', User::getRole('agency'))
        // ->where('role_id', '!=', User::getRole('admin'))->get() as $key => $value) {
        //     $this->ids = [];
        //     if($root = $this->getRoot($value)){
        //         if($value->root_id == $root->id)continue;
        //         $value->root_id = $root->id;
        //         $value->save();
        //     }
        // }
        // $user = User::where('email', 'osvaldo.tellez.almirall@gmail.com')->first();
        // $user2 = User::where('email', 'globalimperii@dnakamafaction.com')->first();

        // foreach (InsuranceObama::where('user_id', $user->id)->get()->concat(
        //     InsuranceCar::where('user_id', $user->id)->get()->concat(
        //     InsuranceHome::where('user_id', $user->id)->get() 
        // )) as $value) {
        //     $this->info($value->client->fullName);
        // }

        // $this->info($user->root->company->name);
        // $this->info($user2->company->name);

        // $ids = [];
        // foreach (User::where('role_id', User::getRole('agency'))->get() as $value) {
        //     if ($value->company_id && \in_array($value->company_id, $ids)) {
        //         $company = $value->company->replicate();
        //         $company->save();
        //         $value->company_id = $company->id;
        //         $value->save();
        //     }
        //     elseif($value->company_id) $ids[] = $value->company_id;
        // }

        // $i = InsuranceObama::find(51);
        // Client::find($i->client_id)->delete();
        // $i->delete();
        // \DB::statement('alter table client modify parol_number varchar(50) null;');
        

        // \DB::statement('alter table users modify birthday datetime null;');
        // InsuranceCar::find(2)->delete();
        // if($user = User::where('email', 'm_yaima@yahoo.com')->first()){
            
        //     $subscription = $user->subscription;
        //     if($subscription->duration){
        //         $user->subscription_renew = (new Carbon())->addMonths($subscription->duration);
    
        //         $user->save();

        //     }
        //     else $this->info('Subscription with 0 duration: id='.$subscription->id);

        // }
        // foreach (Client::get() as $client) {
        //     if(InsuranceCar::where('client_id', $client->id)->first()){
        //         $client->insurance = 'car';
        //         $client->save();
        //     }
        //     else if(InsuranceObama::where('client_id', $client->id)->first()){
        //         $client->insurance = 'obama-care';
        //         $client->save();
        //     }
        //     else if(InsuranceHome::where('client_id', $client->id)->first()){
        //         $client->insurance = 'home';
        //         $client->save();
        //     }
        //     else if(InsuranceBusiness::where('client_id', $client->id)->first()){
        //         $client->insurance = 'business';
        //         $client->save();
        //     }
        // }
        // DB::table('reset_password')->whereRaw("(
        //     email = 'alain.asegurateconmigo@gmail.com'
        // )")->delete();
        // foreach (User::whereRaw("(
        //     email = 'alain.asegurateconmigo@gmail.com'
        // )")->get() as $value) {

        //     $subscription = Subscription::find($value->subscription_id);

        //     $value->subscription_renew = (new Carbon())->addMonths($subscription->duration);

        //     $value->save();

        //     if($parent = $value->parent){
        //         $aux = $parent;
        //         if(!$aux->is('agency') && !($aux->root_id)){
        //             while(true){
        //                 if(!$aux->parent)break;
        //                 $aux = $aux->parent;
        //                 if($aux->root_id)break;
        //             }
        //         }
        //         $value->root_id = $aux->root_id ?? $aux->id;
        //         $value->save();
        //     }
        // }

        
        $this->info('Done!!');
    }
}
