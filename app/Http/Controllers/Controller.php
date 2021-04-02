<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Alcidesrh\Generic\GenericResourceCollection;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    function dinamicListBase($query, Request $request) {

        $field = $request->fields;
        return ($perPage = $request->get('per_page'))
        ? new GenericResourceCollection($query->paginate($perPage), $request->fields)
        : new GenericResourceCollection($query->get(), $request->fields);

    }
}
