<?php

namespace App\Helpers;

use App\Exceptions\FileUploadErrorException;
use Illuminate\Support\Facades\Storage;

class AmazonS3
{
    public function upload($request, $patientId)
    {
        $file = $request->file('file');

        $filename = $this->quickRandom() . '.' . $file->extension();

        try {
            Storage::disk('s3')->put('/patient_' . $patientId, $file, []);
        } catch (\Throwable $exception) {
            throw new FileUploadErrorException($exception->getMessage());
        }

        return 'https://med-clinic.eu-central-1.amazonaws.com/patient_' . $patientId . '/' . $filename;
    }

    public static function quickRandom($length = 25)
    {
        $pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

        return substr(str_shuffle(str_repeat($pool, $length)), 0, $length);
    }
}
