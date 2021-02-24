<?php

namespace App\Mail;

use App\Models\PatientsTests;
use Illuminate\Bus\Queueable;
use Illuminate\Http\UploadedFile;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;

class AddPatientTestMail extends Mailable
{
    use Queueable, SerializesModels;

    public PatientsTests $patientsTest;

    public ?UploadedFile $file;

    public function __construct(PatientsTests $patientsTest, ?UploadedFile $file)
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
        return $this->subject('Результати аналізу | МедСервіс - Медична система')
            ->view('emails.add-patient-test')
            ->attachData($this->file, 'name' . mb_strtolower($this->file->getExtension()), [
                'mime' => $this->file->getMimeType(),
            ])
            ->with([
                'result' => $this->patientsTest,
                'patient' => $this->patientsTest->patient,
                'test' => $this->patientsTest->test,
                'date' => Carbon::now()->format('Y-m-d'),
                'link' => 'http://localhost/',
            ]);
    }
}
