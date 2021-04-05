<?php

namespace App\Http\Controllers;

use Validator;
use Carbon\Carbon;
use App\Models\File;
use App\Models\User;
use App\Mail\SendEmail;
use App\Models\Company;
use App\Models\Payment;
use App\Trail\SearchText;
use App\Mail\ResetPassword;
use Illuminate\Support\Str;
use App\Mail\CreatePassword;
use App\Models\Subscription;
use Illuminate\Http\Request;
use App\Http\Util\SquarePayment;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Alcidesrh\Generic\GenericResource;
use App\Http\Resources\UserEditResource;
use App\Http\Resources\UserListResource;
use Illuminate\Support\Facades\Response;
use App\Http\Resources\UserLoginResource;
use App\Http\Resources\UserProfileResource;
use Alcidesrh\Generic\GenericResourceCollection;

class UserController extends Controller
{
    use SearchText;

    public function authenticate(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            $errors = [];
            foreach ($validator->errors()->keys() as $key) {
                $errors[$key] = $validator->errors()->first($key);
            }
            return Response::json('Datos incorrectos.', $errors);
        }

        $record = DB::table('reset_password')->where('email', $request->email)->first();

        if ($request->get('newPassword')) {
            $user = User::where('email', $request->email)->first();
            $user->password = Hash::make($request->password);
            $user->email_verified_at = new Carbon;

            if (!$record->user_pay) {
                $user->active = 1;
                DB::table('reset_password')->where('email', $request->email)->delete();
            }

            $user->save();

        }

        if (Auth::attempt($request->only('email', 'password'), $request->remember)) {

            return new UserLoginResource(Auth::user(), $record ? $record->user_pay : false);

        }
        return response(['error' => 'Incorrecto email o contraseña, por favor pruebe de nuevo'], 400);
    }

    public function logout()
    {
        Auth::logout();
        return response('logout', 200);
    }

    public function resetPassword(Request $request)
    {

        if ($email = $request->get('email')) {

            if (!($user = User::where('email', $email)->first())) {
                return response('El usuario no existe', 500);
            }

            $token = hash_hmac('sha256', Str::random(40), config('app.key'));

            DB::table('reset_password')->where('email', $email)->delete();

            DB::table('reset_password')->insert(['email' => $email, 'token' => $token]);

            try {
                Mail::to($user)->send(new ResetPassword($user, $token));
                return response('El correo ha sido enviado', 200);
            } catch (\Exception $e) {
                return response($e->getMessage(), 500);
            }
        }
    }    

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function user()
    {
        return new UserLoginResource(Auth::user());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function profile(Request $request)
    {
        return new UserProfileResource(User::find($request->get('id')));
    }

    function list(Request $request) {

        $user = Auth::user();

        $query = User::select('users.*');

        if (($role = $request->get('role'))) {
            $query->where('users.role_id', User::getRole($role));
        }

        switch ($user->role) {
            case 'admin':{
                        break;
                }
            case 'agency':{
                    $query->whereRaw("(users.root_id = $user->id OR users.parent_id = $user->id)");
                    // $query->whereRaw("(company_id = $user->company_id OR parent_id = $user->id)");
                    $query->where('users.id', '!=', $user->id);
                    break;
                }
            case 'staff':{
                    $query->where('users.parent_id', $user->parent_id);
                    break;
                }
            default:
                $query->where('users.parent_id', $user->id);
        }

        if ($search = $request->search) {
            $this->searchTerm($query, $search, ['name', 'last_name', 'email', 'phone', 'number_account'], 'users');
        }

        if ($search = $request->name) {
            $this->searchTerm($query, $search, ['name'], 'users');
        }

        if ($search = $request->email) {
            $this->searchTerm($query, $search, ['email'], 'users');
        }

        if ($search = $request->last_name) {
            $this->searchTerm($query, $search, ['last_name'], 'users');
        }

        if ($search = $request->phone) {
            $this->searchTerm($query, $search, ['phone'], 'users');
        }

        if ($search = $request->number_account) {
            $this->searchTerm($query, $search, ['number_account'], 'users');
        }

        if ($subscription = $request->subscription) {
            if ($subscription == 'active') {
                $query->whereNotNull('users.subscription_renew');
            } else {
                $query->whereNull('users.subscription_renew');
            }

            //  $query->where('subscription_id', $subscription);
        }

        if ($license = $request->license) {
            $query->join('license_user', 'license_user.user_id', 'users.id');
            $query->where('license_user.license_id', $license);
        }

        if ($request->from || $request->to) {
            $this->searchDateInterval($query, $request->from, $request->to, 'users');
        }

        return UserListResource::collection($query->paginate($request->get('perPage')));
    }

    public function usersForSelect(Request $request)
    {
        $user = Auth::user();
        $query = User::query();

        // if ($request->company) {
        //     $query->where('company_id', $request->company);
        // } else {

        switch ($user->role) {
            case 'agency':{
                    $query->where('root_id', $user->id);
                    // $query->where('company_id', $user->company_id);
                    break;
                }
            default:{
                    if ($role = $request->role) {
                        $query->where('role_id', User::getRole($role));
                    }
                    if ($user->root_id) {
                        $query->where('root_id', $user->root_id);
                    } else {
                        $query->where('role_id', '!=', User::getRole('admin'))->where('role_id', '!=', User::getRole('staff'));
                    }

                }
        }
        // }

        if ($request->parent) {
            $query->orWhere('id', $request->parent);
        }

        return new GenericResourceCollection($query->get(), ['id', ['name' => 'fullNameAndAcode'], 'number_account', ['image' => ['url']], 'email', 'role']);
    }

    public function usersForSelectTest(Request $request)
    {

        return new GenericResourceCollection(User::whereIn('id', [172, 178, 160, 208, 122, 115, 117])->orderBy('role_id', 'asc')->get(), ['id', 'name', ['image' => ['url']], 'email', ['role' => ['slug']]]);
    }

    public function sendEmail(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        Mail::to($user->email)->send(new SendEmail($user, ['subject' => $request->subject, 'message' => $request->message]));

        return 'sent';
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        $user = User::find($request->get('id'));

        return new UserEditResource(User::find($request->get('id')));
    }

    public function save(Request $request)
    {
        $user = Auth::user();

        $input = $request->all();

        $parent = null;

        $input['role_id'] = $input['role'];

        if (isset($input['id'])) {

            $item = User::find($input['id']);

            if ($item->id != $user->id) {

                if (!$user->is('admin')) {

                    $input['parent_id'] = $user->id;
                    $input['root_id'] = $user->root_id;
                    // $input['company_id'] = $user->company_id;
                }

                $parent = $item->parent()->first();
            }

            $item->update($input);

            if($item->is('agency')){

                if(!($company = $item->company)){
                 if(isset($input['company_name']) && $input['company_name'])
                  $company = $this->createCompany($input['company_name'], $item);
                }
                else{
                 $company->name = $input['company_name'];
                 $company->save();
                }
            }            

        } else {

            if (User::where('email', $input['email'])->exists()) {
                return \json_encode(['error' => 'Ya existe un usuario con ese correo.']);
            }

            $subscription = Subscription::where('type', 'default')->where('active', 1)->where('role_id', $input['role'])->first();

            if (!$user->is(['admin'])) {

                $input['parent_id'] = $user->is('staff') ? $user->parent_id : $user->id;

                $input['root_id'] = $user->root_id;

                if (!$request->sendPayLink) {

                    $paid = true;

                    //Crear staff  gratis----------------------------------------------------------------------------------------------
                    // if ($user->is('agency') && User::getRole($input['role']) == 'staff') {
                    if (User::getRole($input['role']) == 'staff') {
                        $paid = false;
                        // if (!($userFreeCount = DB::table('create_free_user_count')->where('user_id', $user->id)->first())) {
                        //     DB::table('create_free_user_count')->insert(['user_id' => $user->id, 'users' => 1]);
                        // } else if ($userFreeCount->users < 3) {
                        //     DB::table('create_free_user_count')->update(['users' => $userFreeCount->users + 1]);
                        // } else {
                        //     $paid = true;
                        // }
                    }

                    if ($paid) {

                        if ($subscription->price) {

                            $client = new SquarePayment($input['nonce']);

                            $paymentResponse = $client->proceedPayment($subscription->price);

                            if (isset($paymentResponse['error'])) {
                                return \json_encode(['error' => $paymentResponse['error']['category'] . ': ' . $paymentResponse['error']['detail']]);
                            }

                            $paymentResponse['user_id'] = $user->id;

                            $payment = Payment::create($paymentResponse);

                            $input['active'] = 1;
                        }

                    }
                }
            }

            $input['number_account'] = User::generateNumberAccount();

            $input['subscription_id'] = $subscription->id;

            $item = User::create($input);

            if (isset($payment)) {
                $payment->user_target = $item->id;
                $payment->action = 'Nuevo usuario';
                $payment->type = 'user-subscription';
                $payment->save();
            }

            $item->subscription_renew = (new Carbon())->addMonths($subscription->duration);

            $item->save();

            if($item->is('agency') && isset($input['company_name']) && $input['company_name']){
                $this->createCompany($input['company_name'], $item);
            }

            $token = hash_hmac('sha256', Str::random(40), config('app.key'));

            DB::table('reset_password')->insert(['email' => $item->email, 'token' => $token, 'user_pay' => $request->sendPayLink]);

            try {
                Mail::to($item)->send(new CreatePassword($item, $token));
            } catch (\Exception $exception) {
            }
        }

        if ($user->is(['admin', 'agency'])) {

            if (isset($input['parent']) && is_array($input['parent']) && count($input['parent'])) {

                if ($parent && $input['parent']['id'] != $parent->id) {
                    $parent = User::find($input['parent']['id']);
                    $item->parent()->associate($parent);
                    if($parent->root_id)
                     $item->root_id = $parent->root_id;
                    $item->save();
                } else if (!isset($parent->id) && $input['parent']['id']) {
                    $parent = User::find($input['parent']['id']);
                    $item->parent()->associate($parent);
                    if($parent->root_id)
                     $item->root_id = $parent->root_id;
                    $item->save();
                }
            } else if ($parent) {
                $item->parent()->dissociate();
                $item->save();
            }

            if (isset($input['childrenRemoved']) && !empty($input['childrenRemoved'])) {
                foreach (User::find($input['childrenRemoved']) as $user) {
                    $user->parent()->dissociate();
                    $user->save();
                }
            }

            if (isset($input['children']) && !empty($input['children'])) {
                foreach (User::find($input['children']) as $user) {
                    $user->parent()->associate($item);
                    $user->save();
                }
            }

        }

        if (isset($input['image']) && \is_array($input['image']) && !isset($input['image']['id'])) {

            $file = new File();

            $file->save($input['image']);

            if ($image = $item->image()->first()) {
                $image->delete();
            }

            $item->image()->save($file);

        }

        $item->licenses()->sync($input['licenses'] ?? []);

        return !isset($input['id']) ? response("Se ha creado una cuenta y se ha enviado el correo electrónico {$item->email} para activarla.") : null; //new UserListResource($item);
    }

    public function createCompany($name, User $user){
      $company = new Company();
      $company->name = $name;
      $company->address = $user->address;
      $company->email = $user->email;
      $company->phone = $user->phone;
      $company->save();
      $user->company_id = $company->id;
      $user->save();
      return $company;
    }

    public function pay(Request $request)
    {

        $user = Auth::user();

        $subscription = Subscription::where('type', 'default')->where('active', 1)->where('role_id', $user->role_id)->first();

        if ($subscription->price) {

            $client = new SquarePayment($request->nonce);

            $paymentResponse = $client->proceedPayment($subscription->price);

            if (isset($paymentResponse['error'])) {
                return \json_encode(['error' => $paymentResponse['error']['category'] . ': ' . $paymentResponse['error']['detail']]);
            }

            $paymentResponse['user_id'] = $user->id;
            $paymentResponse['user_target'] = $user->id;
            $paymentResponse['action'] = 'Renovar subscripción';
            $paymentResponse['type'] = 'user-subscription';

            Payment::create($paymentResponse);
        }

        $user->subscription_renew = (new Carbon())->addMonths($subscription->duration ?? 1);

        $user->active = 1;

        DB::table('reset_password')->where('email', $user->email)->delete();

        $user->save();

        return \json_encode(['success' => 'Pago efectuado satifactoriamente']);

    }

    public function delete($id)
    {
        $item = User::find($id);

        $item->delete();

        return 'deleted';
    }
}
