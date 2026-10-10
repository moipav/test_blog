<?php

declare(strict_types=1);

namespace App\Helpers;

class H
{
    public static function dump($var)
    {
        echo '<pre>';
        var_dump($var);
        echo '</pre>';
    }
}