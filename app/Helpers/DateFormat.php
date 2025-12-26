<?php

namespace App\Helpers;

use DateTime;

class DateFormat{
    public static function formatDate(string $date)
    {
        $dt = new DateTime($date);

        return ['date' => $dt->format('d.m.y'), 'time' => $dt->format('H:i')];
    }
}