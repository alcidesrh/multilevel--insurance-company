<?php

namespace App\Mail\Insurance;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Insurance\InsuranceObama;
use Illuminate\Contracts\Queue\ShouldQueue;
use Barryvdh\DomPDF\Facade as PDF;

class ObamaCareInsuranceEmail extends Mailable
{
    use Queueable, SerializesModels;

    private $data;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(InsuranceObama $data, $edit = false)
    {
        $this->data = $data;
        $this->edit = $edit;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        if($this->edit) $msg = "D NAKAMA FACTION QUOTE. Obama Care. Cliente: {$this->data->client->fullName}";
        else {
            $date = now()->format('M/D/YY');
            $msg = "Editado $date. Obama Care. Cliente: {$this->data->client->fullName}";
        }

        $pdf = PDF::loadView('pdf.insurance.obama-care', ['data' => $this->data]);
        $email = $this->from('noreply@dnfnewworld.com')->view('emails.insurance.obama', ['data' => $this->data])->subject($msg)
        ->attachData($pdf->stream(), 'Insurance Obama Care.pdf', [
            'mime' => 'application/pdf',
        ]);
        foreach ($this->data->files as $file) {
            $email->attachFromStorageDisk('files', $file->path, $file->name);
        }
        return $email;
    }
}
