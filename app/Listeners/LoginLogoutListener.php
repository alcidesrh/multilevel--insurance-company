<?php

namespace App\Listeners;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginLogoutListener
{
    /**
     * Handle user login events.
     */
    public function handleUserLogin($event) {
        $user = Auth::user();
        if(!$user->is('admin') && $user->subscription_renew && $user->subscription_renew < new Carbon){
            $user->subscription_renew = null;
            $user->active = 0;
            $user->save();
        }
    }

    /**
     * Handle user logout events.
     */
    // public function handleUserLogout($event) {
       
    // }

    /**
     * Register the listeners for the subscriber.
     *
     * @param  \Illuminate\Events\Dispatcher  $events
     */
    public function subscribe($events)
    {
        $events->listen(
            'Illuminate\Auth\Events\Login',
            'App\Listeners\LoginLogoutListener@handleUserLogin'
        );

        // $events->listen(
        //     'Illuminate\Auth\Events\Logout',
        //     'App\Listeners\LoginLogoutListener@handleUserLogout'
        // );
    }
}
