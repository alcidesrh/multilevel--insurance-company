<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Alcidesrh\Generic\GenericResource;
use App\Http\Resources\CategoryListResource;
use Alcidesrh\Generic\GenericResourceCollection;

class ProductController
{
    function listCategories(Request $request) {
 
        return CategoryListResource::collection(Category::where('parent_id', null)->get());

    }

    function category(Request $request) {
 
        return new GenericResource(Category::find($request->id), ['id', 'name', 'children', 'parent_id']);

    }

    function saveCategory(Request $request) {
        
        $input = $request->all();

        if(!isset($input['parent_id'])) $input['parent_id'] = null;

        if (isset($input['id'])) {

            $item = Category::find($input['id']);

            $item->update($input);
        } else {
            $item = Category::create($input);
        }
        
        $item->children()->update(['parent_id' => null]);
        $item->children()->saveMany(Category::find($input['children']));
        return 'saved';

    }
}
