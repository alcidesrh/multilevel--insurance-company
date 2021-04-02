<?php

namespace App\Mail;

use App\Models\User;
// use App\Models\Company;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class Invitation extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    // public $agency;
    public $subject;
    public $message2;
    public $name;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    // public function __construct(User $user, $agency, $subject = null, $message = null)
    public function __construct(User $user, $subject = null, $message = null, $name = null)
    {
        $this->user = $user;
        // $this->agency = $agency;
        $this->subject = $subject;
        $this->message2 = $message;
        $this->name = $name;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {        
        return $this->from("noreply@dnfnewworld.com")
        ->subject($this->subject ?? 'Invitación a DNF')->view('emails.invitation');
    }
}
