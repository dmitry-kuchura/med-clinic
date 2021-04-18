<?php

namespace App\Mail;

use App\Models\PatientAnalysis;
use Illuminate\Bus\Queueable;
use Illuminate\Http\UploadedFile;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;

class AddPatientTestMail extends Mailable
{
    use Queueable, SerializesModels;

    public PatientAnalysis $patientsTest;

    public ?UploadedFile $file;

    public function __construct(PatientAnalysis $patientsTest, ?UploadedFile $file)
    {
        $this->patientsTest = $patientsTest;
        $this->file = $file;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $email = $this->subject('Результати аналізу | МедСервіс - Медична система')
            ->view('emails.add-patient-test')
            ->with([
                'patient' => $this->patientsTest->patient,
                'date' => Carbon::now()->format('Y-m-d'),
                'link' => 'http://localhost/',
            ]);

        if ($this->file) {
            $email->attachData($this->file, 'name' . mb_strtolower($this->file->getExtension()), [
                'mime' => $this->file->getMimeType(),
            ]);
        }

        return $email;
    }
}
