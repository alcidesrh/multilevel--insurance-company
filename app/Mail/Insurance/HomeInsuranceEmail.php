<?php

namespace App\Mail\Insurance;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Insurance\InsuranceObama;
use Illuminate\Contracts\Queue\ShouldQueue;
use Barryvdh\DomPDF\Facade as PDF;

class HomeInsuranceEmail extends Mailable
{
    use Queueable, SerializesModels;

    private $data;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $pdf = PDF::loadView('pdf.insurance.home', ['data' => $this->data]);
        $email = $this->from('noreply@dnfnewworld.com')->view('emails.insurance.home', ['data' => $this->data])->subject("D NAKAMA FACTION QUOTE. Home Insurance. Cliente: {$this->data->client->fullName}")
        ->attachData($pdf->stream(), 'Home Insurance.pdf', [
            'mime' => 'application/pdf',
        ]);
        foreach ($this->data->files as $file) {
            $email->attachFromStorageDisk('files', $file->path, $file->name);
        }
        return $email;
    }
}
