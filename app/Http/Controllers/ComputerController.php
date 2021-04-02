<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Computer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\ComputerReservationResource;

class ComputerController
{
    public function save(Request $request)
    {

        Computer::create(\array_merge($request->all(), ['user_id' => (Auth::user())->id]));

        return $this->list();
    }

    function list() {
        $user = Auth::user();
        return $user->is('admin') ? ComputerReservationResource::collection(Computer::where('day', '>=', now()->startOfDay())->get()) : ComputerReservationResource::collection( Computer::where('user_id', (Auth::user())->id)->where('day', '>=', now()->startOfDay())->get() );
    }

    public function avialable(Request $request)
    { 
        return Computer::select('computer')
        ->where('turn', $request->turn)->where('day', new Carbon($request->day))->get()->map(function($i){return $i->computer;});
    }

    public function delete($id)
    {
       Computer::find($id)->delete();

       return $this->list();
    }
}
