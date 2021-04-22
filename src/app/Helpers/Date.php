<?php

namespace App\Helpers;

use Illuminate\Support\Carbon;

class Date
{
    public static function getCurrentHour(): string
    {
        return Carbon::now()->format('H');
    }

    public static function getTomorrowMorningTime(): string
    {
        return Carbon::now()->addDays(1)->setHours(00)->setMinutes(00)->setSeconds(00)->format('Y-m-d H:i:s');
    }

    public static function getTomorrowEndDayTime(): string
    {
        return Carbon::now()->addDays(1)->setHours(23)->setMinutes(59)->setSeconds(59)->format('Y-m-d H:i:s');
    }

    public static function getCurrentTime(): string
    {
        return Carbon::now()->format('Y-m-d H:i:s');
    }

    public static function getMorningTime(): string
    {
        return Carbon::now()->setHours(8)->setMinutes(00)->setSeconds(00)->format('Y-m-d H:i:s');
    }

    public static function getEndDayTime(): string
    {
        return Carbon::now()->setHours(23)->setMinutes(59)->setSeconds(59)->format('Y-m-d H:i:s');
    }
}
