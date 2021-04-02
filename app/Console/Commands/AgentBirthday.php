<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Mail\AgentBirthday as AgentBirthdayMail;

class AgentBirthday extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:agent:birthday';

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
        $date = now();

        foreach (User::whereRaw("birthday >= '$date' AND birthday <= '{$date->copy()->add('1 day')}'")->get() as $user) {
            Mail::to($user)->send(new AgentBirthdayMail($user));
        }
    }
}
