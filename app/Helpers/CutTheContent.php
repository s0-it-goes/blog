<?php

declare(strict_types = 1);

namespace App\Helpers;

class CutTheContent
{
    public static function cut(string $text, int $words): string {
        return implode(' ', array_slice(explode(' ', $text), 0, $words));
    }
}