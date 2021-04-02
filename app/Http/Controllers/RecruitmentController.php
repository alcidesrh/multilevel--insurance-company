<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\RecruitmentListResource;
use App\Http\Resources\RecruitmentResource;
use App\Http\Util\SquarePayment;
use App\Mail\CreatePassword;
use App\Mail\Invitation;
use App\Models\Payment;
use App\Models\Recruitment;
use App\Models\Role;
use App\Models\Subscription;
use App\Models\User;
use App\Trail\SearchText;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

class RecruitmentController extends Controller
{

    use SearchText;

    public function saveApplication(Request $request)
    {
        if ($request->get('user_number')) {

            if (!($user = User::where('number_account', $request->get('user_number'))->first())) {
                return \json_encode('Wrong reference');
            }

            if (Recruitment::where('email', $request->email)->first()) {
                return \json_encode('Ya se ha registrado un representante con ese email.');
            }

            $formatTime = function ($time1 = null, $time2 = null) {

                if (!$time1 && !$time2) {
                    return null;
                }

                $result = "";

                if ($time1) {
                    $time1 = \explode(':', $time1);
                    $result = Carbon::create(null, null, null, $time1[0], $time1[1])->format('h:i A');
                }

                if ($time2) {
                    $time2 = \explode(':', $time2);
                    return empty($result) ?
                    Carbon::create(null, null, null, $time2[0], $time2[1])->format('h:i A') :
                    $result . ' - ' . Carbon::create(null, null, null, $time2[0], $time2[1])->format('h:i A');
                }

                return $result;
            };

            $data = $request->all();

            $item = new Recruitment();
            $item->first_name = $data['first_name'];
            $item->last_name = $data['last_name'];
            $item->email = $data['email'];
            $item->phone = $data['phone'];
            $item->address = $data['address'];
            $item->zip_code = $data['postal_code'];
            $item->city = $data['city'];
            $item->citizenship = $data['citizenship'] ?? null;
            $item->profession = $data['profession'] ?? null;
            $item->likejob = $data['likejob'] ?? null;
            $item->dislikejob = $data['dislikejob'] ?? null;
            $item->desireincome = $data['desireincome'] ?? null;
            $item->attractivestatus = $data['attractivestatus'] ?? null;
            $item->bigachievement = $data['bigachievement'] ?? null;
            $item->knowfrom = $data['knowfrom'] ?? null;
            $item->interested_us = $data['interested_us'] ?? null;
            $item->licenses = $data['licenses'] ?? null;
            $item->monday = $formatTime($request->get('monday1'), $request->get('monday2'));
            $item->tuesday = $formatTime($request->get('tuesday1'), $request->get('tuesday2'));
            $item->wednesday = $formatTime($request->get('wednesday1'), $request->get('wednesday2'));
            $item->thursday = $formatTime($request->get('thuersday1'), $request->get('thuersday2'));
            $item->friday = $formatTime($request->get('friday1'), $request->get('friday2'));
            $item->saturday = $formatTime($request->get('saturday1'), $request->get('saturday'));
            $item->referred_one_name = $data['referred_one_name'] ?? null;
            $item->referred_one_email = $data['referred_one_email'] ?? null;
            $item->referred_one_phone = $data['referred_one_phone'] ?? null;
            $item->referred_two_name = $data['referred_two_name'] ?? null;
            $item->referred_two_email = $data['referred_two_email'] ?? null;
            $item->referred_two_phone = $data['referred_two_phone'] ?? null;
            $item->referred_three_name = $data['referred_three_name'] ?? null;
            $item->referred_three_email = $data['referred_three_email'] ?? null;
            $item->referred_three_phone = $data['referred_three_phone'] ?? null;

            $item->referredBy()->associate($user);

            $item->save();

            return \json_encode('saved');
        }
        if ($number = $request->get('number')) {
            $user = User::select('name', 'number_account')->where('number_account', $number)->first();
            return view('application', ['user' => $user]);
        }
    }

    public function sendInvitation(Request $request)
    {

        $emails = \explode(',', $request->emails);

        $user = Auth::user();

        $name = $user->fullName;

        if ($user->is('staff')) {
            if($user->parent)
             $name = $user->parent->fullName;
        }        

        $invalidEmails = [];

        try {
            foreach ($emails as $email) {
                $email = str_replace(' ', '', $email);
                if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    Mail::to($email)->send(new Invitation($user, $request->subject, $request->message, $name));
                    // Mail::to($email)->send(new Invitation($user, $user->company, $request->subject, $request->message));
                } else {
                    $invalidEmails[] = $email;
                }

            }

        } catch (\Exception $e) {
            return Response::json(['error' => $e->getMessage()], 500);
        }

        if (!empty($invalidEmails)) {
            return Response::json(['invalid' => $invalidEmails]);
        }

        return \response('Invitación enviada');
    }

    public function application(Request $request)
    {
        if ($request->get('user_number')) {

            if (!($user = User::where('number_account', $request->get('user_number'))->first())) {
                return \json_encode('Wrong reference');
            }

            $data = $request->all();

            $item = new Recruitment();
            $item->first_name = $data['first_name'];
            $item->last_name = $data['last_name'];
            $item->email = $data['email'];
            $item->phone = $data['phone'];
            $item->address = $data['address'];
            $item->zip_code = $data['postal_code'];
            $item->city = $data['city'];
            $item->citizenship = $data['citizenship'] ?? null;
            $item->profession = $data['profession'] ?? null;
            $item->likejob = $data['likejob'] ?? null;
            $item->dislikejob = $data['dislikejob'] ?? null;
            $item->desireincome = $data['desireincome'] ?? null;
            $item->attractivestatus = $data['attractivestatus'] ?? null;
            $item->bigachievement = $data['bigachievement'] ?? null;
            $item->knowfrom = $data['knowfrom'] ?? null;
            $item->interested_us = $data['interested_us'] ?? null;
            $item->licenses = $data['licenses'] ?? null;
            $item->monday = $request->get('monday1', '') . '-' . $request->get('monday2', ''); //$formatTime($request->get('monday1'), $request->get('monday2'));
            $item->tuesday = $request->get('tuesday1', '') . '-' . $request->get('tuesday2', ''); //$formatTime($request->get('tuesday1'), $request->get('tuesday2'));
            $item->wednesday = $request->get('wednesday1', '') . '-' . $request->get('wednesday2', ''); //$formatTime($request->get('wednesday1'), $request->get('wednesday2'));
            $item->thursday = $request->get('thursday', '') . '-' . $request->get('thursday2', ''); //$formatTime($request->get('thuersday1'), $request->get('thuersday2'));
            $item->friday = $request->get('friday1', '') . '-' . $request->get('friday2', ''); //$formatTime($request->get('friday1'), $request->get('friday2'));
            $item->saturday = $request->get('saturday1', '') . '-' . $request->get('saturday2', ''); //$formatTime($request->get('saturday1'), $request->get('saturday'));
            $item->referred_one_name = $data['referred_one_name'] ?? null;
            $item->referred_one_email = $data['referred_one_email'] ?? null;
            $item->referred_one_phone = $data['referred_one_phone'] ?? null;
            $item->referred_two_name = $data['referred_two_name'] ?? null;
            $item->referred_two_email = $data['referred_two_email'] ?? null;
            $item->referred_two_phone = $data['referred_two_phone'] ?? null;
            $item->referred_three_name = $data['referred_three_name'] ?? null;
            $item->referred_three_email = $data['referred_three_email'] ?? null;
            $item->referred_three_phone = $data['referred_three_phone'] ?? null;

            $item->referredBy()->associate($user);

            $item->save();

            return \json_encode('saved');
        }
        if ($number = $request->get('number')) {
            if ($user = User::select('name', 'number_account')->where('number_account', $number)->first()) {
                return view('application', ['user' => $user]);
            }

        }
        return view('index', ['data' => ['route' => Route::current()->parameters() ?? null]]);
    }

    function list(Request $request) {

        $user = Auth::user();

        $query = Recruitment::query();

        // if ($user->is('staff')) {
        //     $query->select('recruitment.*')->join('users', 'users.id', '=', 'recruitment.referred_by')->whereRaw("(recruitment.referred_by = $user->id)");
        // }
        if ($user->is('agency')) {
            $query->select('recruitment.*')->join('users', 'users.id', '=', 'recruitment.referred_by')->where(function($query) use ($user){
                $query->orWhere('recruitment.referred_by', $user->id)
                      ->orWhereIn('recruitment.referred_by', User::where('root_id', $user->id)->get()->map(function($user){return $user->id;})->all());
            });
        } else if (!$user->is('admin')) {
            $query->where('referred_by', $user->id);
        }

        if ($search = $request->search) {
            $this->searchTerm($query, $search, ['first_name', 'last_name', 'email', 'phone'], 'recruitment');
        }

        if ($search = $request->first_name) {
            $this->searchTerm($query, $search, ['first_name'], 'recruitment');
        }

        if ($search = $request->email) {
            $this->searchTerm($query, $search, ['email'], 'recruitment');
        }

        if ($search = $request->last_name) {
            $this->searchTerm($query, $search, ['last_name'], 'recruitment');
        }

        if ($search = $request->phone) {
            $this->searchTerm($query, $search, ['phone'], 'recruitment');
        }

        if ($parent = $request->parent) {
            $query->where('recruitment.referred_by', $parent);
        }

        if ($request->from || $request->to) {
            $this->searchDateInterval($query, $request->from, $request->to, 'recruitment');
        }

        if ($request->state) {
            foreach (['second_training_assisted', 'first_training_assisted', 'second_interview_assisted', 'hired'] as $value) {
                if ($request->state == $value) {
                    $query->where('recruitment.' . $value, '!=', 0)->whereNotNull($value);
                    break;
                } else {
                    $query->where(function ($query) use ($value) {
                        $query->where('recruitment.' . $value, 0)
                            ->orWhereNull($value);
                    });
                }

            }
        }

        return RecruitmentListResource::collection($query->paginate($request->get('perPage')));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function item(Request $request)
    {
        return new RecruitmentResource(Recruitment::find($request->get('id')));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function save(Request $request)
    {
        $input = $request->all();

        if (isset($input['payment'])) {

            $recruitment = Recruitment::find($input['recruitment']);

            if (User::where('email', $recruitment->email)->exists()) {
                return \json_encode(['error' => 'Ya existe un usuario con ese correo.']);
            }

            $subscription = Subscription::where('type', 'default')->where('active', 1)->where('role_id', User::getRole('broker'))->first();

            if (!$request->noPaid && $subscription->price) {

                $client = new SquarePayment($input['nonce']);

                $paymentResponse = $client->proceedPayment($subscription->price);

                if (isset($paymentResponse['error'])) {
                    return \json_encode(['error' => $paymentResponse['error']['category'] . ': ' . $paymentResponse['error']['detail']]);
                }

                $paymentResponse['user_id'] = Auth::user()->id;

                $payment = Payment::create($paymentResponse);
            }

            $user = $recruitment->referredBy()->first() ?? Auth::user();

            // $company = $user->company()->first();

            $newUser = User::create([
                'address' => $recruitment->address,
                'email' => $recruitment->email,
                'name' => $recruitment->first_name,
                'last_name' => $recruitment->last_name,
                'phone' => $recruitment->phone,
                'role_id' => Role::where('slug', 'broker')->first()->id,
                'commission_rate' => 50,
                'active' => 0,
                'root_id' => $user->root_id,
                // 'company_id' => $company->id ?? null,
                'parent_id' => $user->is('staff') ? $user->parent_id : $user->id,
                'number_account' => User::generateNumberAccount(),
                'subscription_id' => $subscription->id,
                'hired' => 1,
            ]);

            if (isset($payment)) {
                $payment->user_target = $newUser->id;
                $payment->action = 'Nuevo usuario';
                $payment->type = 'user-subscription';
                $payment->save();
            }

            if(!$request->noPaid) $newUser->subscription_renew = (new Carbon())->addMonths($subscription->duration ?? 1);

            $newUser->save();

            $token = hash_hmac('sha256', Str::random(40), config('app.key'));

            DB::table('reset_password')->insert(['email' => $newUser->email, 'token' => $token, 'user_pay' => $request->noPaid]);

            Mail::to($newUser)->send(new CreatePassword($newUser, $token));

            $recruitment->save();

            return "Se ha creado una cuenta y se ha enviado el correo electrónico al usuario para activarla.";
        }

        $item = Recruitment::find($request->get('id'));

        foreach (['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'] as $value) {

            $input[$value] = ($input[$value . '1'] || $input[$value . '2']) ?
            $input[$value . '1'] . '-' . $input[$value . '2'] :
            null;
        }

        $item->update($input);

        return new RecruitmentListResource($item);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        $item = Recruitment::find($id);

        $item->delete();

        return 'deleted';
    }
}
