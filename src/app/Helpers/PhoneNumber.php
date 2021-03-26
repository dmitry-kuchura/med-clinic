<?php

namespace App\Helpers;

class PhoneNumber
{
    public static function prepare(?string $phone): ?string
    {
        if (!$phone) {
            return null;
        }

        $phone = str_replace('+38', '', $phone);
        $phone = str_replace('-', '', $phone);
        $phone = str_replace(' ', '', $phone);

        $pattern = '/\(?([0-9]{3})\)?([ .-]?)([0-9]{3})\2([0-9]{4})/';
        if (preg_match($pattern, $phone, $matches)) {
            return '+38' . $matches[0];
        }

        return null;
    }
}
