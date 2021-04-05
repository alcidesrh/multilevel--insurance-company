<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\User;
use App\Models\Company;
use App\Trail\SearchText;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\CompanyResource;
use App\Http\Resources\CompanyEditResource;
use App\Http\Resources\CompanyShowResource;
use Alcidesrh\Generic\GenericResourceCollection;

class CompanyController extends Controller
{
    use SearchText;

    function list(Request $request) {

        $user = Auth::user();
        
        $query = $user->role == 'agency' ? $user->company() : Company::query();

        if($search = $request->search) $this->searchTerm($query, $search, ['name', 'email']);

        return CompanyResource::collection($query->paginate($request->get('perPage')));

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
 
        if (isset($input['image']) && \is_array($input['image']) && !isset($input['image']['id'])) {

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
