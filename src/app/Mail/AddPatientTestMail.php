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

    public ?UploadedFile $file;

    public function __construct(?UploadedFile $file)
    {
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
                'date' => Carbon::now()->format('Y-m-d'),
                'link' => 'http://localhost/',
            ]);

        if ($this->file) {
            $email->attach($this->file->getRealPath(), [
                'as' => $this->file->getClientOriginalName(),
                'mime' => $this->file->getMimeType(),
            ]);
        }

        return $email;
    }
}
