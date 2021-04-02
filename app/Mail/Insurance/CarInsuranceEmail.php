<?php

namespace App\Mail\Insurance;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Insurance\InsuranceObama;
use Illuminate\Contracts\Queue\ShouldQueue;
use Barryvdh\DomPDF\Facade as PDF;

class CarInsuranceEmail extends Mailable
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
        $pdf = PDF::loadView('pdf.insurance.car', ['data' => $this->data]);
        $email = $this->from('noreply@dnfnewworld.com')->view('emails.insurance.car', ['data' => $this->data])->subject("D NAKAMA FACTION QUOTE. Car Insurance. Cliente: {$this->data->client->fullName}")
        ->attachData($pdf->stream(), 'Car Insurance.pdf', [
            'mime' => 'application/pdf',
        ]);
        foreach ($this->data->files as $file) {
            $email->attachFromStorageDisk('files', $file->path, $file->name);
        }
        return $email;
    }
}
