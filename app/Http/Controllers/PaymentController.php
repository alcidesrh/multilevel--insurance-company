<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Trail\SearchText;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\PaymentResource;
use Barryvdh\DomPDF\Facade as PDF;

class PaymentController extends Controller
{
    use SearchText;

    public function pdf($download = false){
        $pdf = PDF::loadView('pdf.payment', ['data' =>  Payment::query()->get()]);
        return !$download ? $pdf->stream() : $pdf->download('Pagos realizados.pdf');
    }

    function list(Request $request) {

        $user = Auth::user();

        Payment::whereNull('user_id')->delete();

        $query = Payment::query();
        
           
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
        

        return PaymentResource::collection($query->paginate($request->get('perPage')));
    }

    function dinamicList(Request $request) {

        return $this->dinamicListBase(Company::query(), $request);

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

        $owner = null;

        if (isset($input['id'])) {

            $item = Company::find($input['id']);

            $item->update($input);

            $owner = $item->owner()->first();
        } else {
            $item = Company::create($input);
        }

        if (is_array($input['owner'])) {

            if ($owner && $input['owner']['id'] != $owner->id) {
                $owner->company()->dissociate();
                $owner->save();
                $owner = User::find($input['owner']['id']);
                $owner->company()->associate($item);
                $owner->save();
            } else if (!isset($owner->id) && $input['owner']['id']) {
                $owner = User::find($input['owner']['id']);
                $owner->company()->associate($item);
                $owner->save();
                $item->code = $owner->number_account;                
            }
            $item->save();
        } else if ($owner) {
            $owner->company()->dissociate();
            $owner->save();
            $item->code = null;
            $item->save();
        }

        if(!empty($input['personalRemoved'])){
            foreach(User::find($input['personalRemoved']) as $user){
                $user->company()->dissociate();
                $user->save();
            }
        }

        if(!empty($input['personal'])){
            foreach(User::find($input['personal']) as $user){
                $user->company()->associate($item);
                $user->save();
            }
        }
 
        if (\is_array($input['image']) && !isset($input['image']['id'])) {

            $file = new File();

            $file->save($input['image']);

            if($image = $item->image()->first())
             $image->delete();

            $item->image()->save($file);

        }
        return $item->id;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        return new CompanyEditResource(Company::find($request->id));
    }

    public function show(Request $request)
    {
        return new CompanyShowResource(Company::find($request->id));
    }

    public function personal(Request $request)
    {
        return new GenericResourceCollection(
            User::where('role_id', '!=', User::getRole('agency'))->where('company_id', $request->id)->paginate($request->get('perPage')),
             ['id', 'fullName', 'email', 'active', ['image' => ['url']], ['role' => ['slug', 'name']]]);        
    }

    public function delete($id)
    {
        Company::find($id)->delete();

        return 'deleted';
    }
}
