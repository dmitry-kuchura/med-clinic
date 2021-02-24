<?php

namespace App\Mail;

use App\Models\PatientsTests;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AddPatientTestMail extends Mailable
{
    use Queueable, SerializesModels;

    public PatientsTests $patientsTest;

    public function __construct(PatientsTests $patientsTest)
    {
        $this->patientsTest = $patientsTest;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.add-patient-test')->with([
            'patient' => $this->patientsTest->patient,
            'test' => $this->patientsTest,
        ]);
    }
}
