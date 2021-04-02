<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Mail\Christmas as ChristmasMail;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class Christmas extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'christmas:invitation';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send an email to agents';

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

        foreach (User::where('role_id', '!=', User::getRole('admin'))->where('role_id', '!=', User::getRole('client'))->get() as $user) {
            try {
                if (filter_var($user->email, FILTER_VALIDATE_EMAIL))
                 Mail::to($user)->queue(new ChristmasMail($user));
            } catch (\Throwable $th) {
                //throw $th;
            }
        }
        $this->info('Done!!');
    }
}
