<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Models\Company;
use App\Models\Product;
use App\Models\Recruitment;
use App\Models\Subscription;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class MigrateOldSite extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:migrate';

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

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //Migrate companies

        $items = DB::connection('admin_newworld')->select('select * from companies');

        foreach ($items as $value) {
            $item = new Company();
            $item->id = $value->id;
            $item->address = $value->address;
            $item->email = $value->email;
            $item->name = $value->name;
            $item->phone = $value->phone;
            $item->active = $value->active;

            if ($value->parent_id) {
                $item->parent_id = $value->parent_id;
            }

            $item->save();
        }

        //End migrate companies

        //Migrate product

        $items = DB::connection('admin_newworld')->select('select * from products');

        foreach ($items as $value) {
            $item = new Product();
            $item->id = $value->id;
            $item->name = $value->name;
            $item->insurer = $value->insurer;
            $item->save();

            if ($value->company_id) {
                $item->companies()->attach(Company::where('id', $value->company_id)->first());

            }
            if ($value->category) {
                if (!($category = Category::where('name', $value->category)->first())) {
                    $category = Category::create(['name' => $value->category]);
                    $category->save();
                    $item->categories()->attach($category);
                }
                if ($value->sub_category) {
                    $subcategory = Category::where('name', $value->sub_category)->first() ?? Category::create(['name' => $value->sub_category]);
                    $subcategory->parent_id = $category->id;
                    $subcategory->save();
                    $item->categories()->attach($subcategory);
                }
            }
            $item->save();
        }
        //End migrate product

        //Migrate user

        DB::table('roles')->insert([
            ['name' => 'Admin', 'slug' => 'admin'],
            ['name' => 'Agencia', 'slug' => 'agency'],
            ['name' => 'Elite', 'slug' => 'elite'],
            ['name' => 'Representante', 'slug' => 'broker'],
            ['name' => 'Staff', 'slug' => 'staff'],
            ['name' => 'Cliente', 'slug' => 'client'],

        ]);

        DB::table('user_number')->insert([
            ['letter' => 'D', 'number' => 0],
        ]);

        $items = DB::connection('admin_newworld')->select('select * from users order by isfounder DESC');

        foreach ($items as $value) {

            $item = new User();
            $item->id = $value->id;
            $item->name = $value->first_name;
            $item->last_name = $value->last_name;
            $item->address = $value->address;
            if ($value->birthday) {
                list($year, $month, $day) = \explode('-', $value->birthday);
                $item->birthday = new Carbon((int) $year, (int) $month, (int) $day);
            } else {
                $item->birthday = null;
            }

            $item->commission_rate = $value->commission_rate;
            $item->email = $value->email;
            $item->phone = $value->phone;
            $item->active = $value->active;
            $item->title = $value->title;
            $item->number_account = User::generateNumberAccount();
            $item->password = $value->password;
            $item->founder = $value->isfounder;
            $item->role_id = User::getRole($value->role == 'admin' ? 'agency' : ($value->role == 'super' ? 'admin' : $value->role));
            if ($value->company_id) {
                $item->company_id = $value->company_id;
                if ($item->role_id == User::getRole('agency')) {
                    Company::find($value->company_id)->update(['code' => $item->number_account]);
                }

            }
            $item->parent_id = $value->inviter_id;

            $item->save();

            // if ($value->profile_image_filename) {
            //     $image = new File();
            //     $image->save(['name' => $value->profile_image_filename]);
            //     $item->image()->save($image);
            // }
        }

        $items = DB::connection('admin_newworld')->select('select * from recruitment');

        foreach ($items as $value) {

            $item = new Recruitment();
            $item->id = $value->id;
            $item->first_name = $value->first_name;
            $item->last_name = $value->last_name;
            $item->address = $value->address;
            $item->email = $value->email;
            $item->phone = $value->phone;
            $item->city = $value->city;
            $item->zip_code = $value->zip_code;
            $item->citizenship = $value->citizenship;
            $item->profession = $value->profession;
            $item->likejob = $value->likejob;
            $item->dislikejob = $value->dislikejob;
            $item->desireincome = $value->desireincome;
            $item->attractivestatus = !$value->attractivestatus ? null : ($value->attractivestatus == "businessowner" ? 1 : 2);
            $item->bigachievement = $value->bigachievement;
            $item->knowfrom = $value->knowfrom;
            $item->interested_us = !$value->interested_us ? null : ($value->interested_us == 'team' ? 2 : 1);
            $item->licenses = $value->licenses;
            $item->monday = $value->avail_monday;
            $item->tuesday = $value->avail_tuesday;
            $item->wednesday = $value->avail_wednesday;
            $item->thursday = $value->avail_thursday;
            $item->friday = $value->avail_friday;
            $item->saturday = $value->avail_saturday;
            $item->second_interview_date = $value->second_interview_date;
            $item->second_interview_assisted = $value->second_interview_assisted;
            $item->hired = $value->hired;
            $item->first_training_date = $value->first_training_date;
            $item->first_training_assisted = $value->first_training_assisted;
            $item->oxygen = $value->oxygen;
            $item->second_training_date = $value->second_training_date;
            $item->second_training_assisted = $value->second_training_assisted;
            $item->referred_one_name = $value->referred_one_name;
            $item->referred_one_email = $value->referred_one_email;
            $item->referred_one_phone = $value->referred_one_phone;
            $item->referred_two_name = $value->referred_two_name;
            $item->referred_two_email = $value->referred_two_email;
            $item->referred_two_phone = $value->referred_two_phone;
            $item->referred_three_name = $value->referred_three_name;
            $item->referred_three_email = $value->referred_three_email;
            $item->referred_three_phone = $value->referred_three_phone;
            $item->interview_reminder = $value->interview_reminder;

            if ($value->referred_number) {

                $user = DB::connection('admin_newworld')->select("select users.id from users where users.number_account = '{$value->referred_number}'");
                if(!empty($user))
                 $item->referredBy()->associate(User::find($user[0]->id));

            }

            $item->save();
        }
        //End migrate user

        //End migrate product

        //Migrate subscriptions

        $items = DB::connection('admin_newworld')->select('select * from subscriptions');

        foreach ($items as $value) {
            $item = new Subscription();
            $item->id = $value->id;
            $item->title = $value->title;
            $item->description = strip_tags($value->description);
            $item->price = $value->price;
            $item->duration = $value->duration;
            $item->active = $value->active;
            $item->type = $value->title == '1 Month Free' ? 'default' : $value->type;
            $item->role_id = User::getRole($value->role == 'admin' ? 'agency' : $value->role);
            $item->save();

            $items = DB::connection('admin_newworld')->select("select * from subscriptions_user where subscription_id = {$item->id}");

            foreach ($items as $value) {
                if ($user = User::find($value->user_id)) {
                    $user->update(['subscription_id' => $value->subscription_id]);
                    $user->subscription_renew = (new Carbon())->addMonths($value->duration);
                    $user->save();
                }

                // DB::table('user_subscription')->insert([
                //    'user_id' => $value->user_id,
                //    'subscription_id' => $item->id,
                //    'paid' => $value->paid_amount,
                //    'duration' => $value->duration,
                //    'transaction_ref' => $value->transaction_ref,
                //    'paid_date' => $value->paid_date,
                // ]);
            }

        }

        //End migrate subscriptions

        $this->info('Migration completed!!');
    }
}
