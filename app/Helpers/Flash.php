<?php

declare(strict_types = 1);

namespace App\Helpers;

class Flash
{
    public static function set(string $key, string $message): void
    {
        $_SESSION['flash'][$key] = $message;
    }

    public static function get(string $key):string|null
    {
        if(isset($_SESSION['flash'][$key])) {
            $msg = $_SESSION['flash'][$key];
            unset($_SESSION['flash'][$key]);
            return $msg;
        }

        return null;
    }
     public static function delete(string $key) : void
    {
        if(isset($_SESSION['flash'][$key])) {
            unset($_SESSION['flash'][$key]);
        }
    }
}