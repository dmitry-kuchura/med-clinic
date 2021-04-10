<?php

namespace App\Helpers;

use App\Repositories\SettingsRepository;

class Settings
{
    public static function getParam(string $param): string
    {
        $repository = new SettingsRepository();

        return $repository->find($param);
    }
}
