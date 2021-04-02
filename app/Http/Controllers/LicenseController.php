<?php

namespace App\Http\Controllers;

use App\Models\License;
use App\Trail\SearchText;
use Illuminate\Http\Request;
use Alcidesrh\Generic\GenericResource;
use Alcidesrh\Generic\GenericResourceCollection;

class LicenseController extends Controller
{
    use  SearchText;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function list(Request $request)
    {
        $query = License::query();

        if ($search = $request->search) {
            $this->searchTerm($query, $search, ['title']);
        }
        return new GenericResourceCollection($query->paginate($request->get('perPage')), ['id', 'title']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        return new GenericResource(License::find($request->id), ['id', 'title']);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function save(Request $request)
    {
        $input = $request->all();

        if($id = $request->id){
            $item = License::find($id);
            $item->update($input);    
            return new GenericResource($item, ['id', 'title']);    
        }
        else{
            $item = License::create($input);
            return 'created';
        }

        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return License::find($id)->delete();
    }
}
