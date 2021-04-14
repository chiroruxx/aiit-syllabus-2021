<?php

declare(strict_types=1);

namespace App\Enums;

use MyCLabs\Enum\Enum;

class CompulsoryType extends Enum
{
    public const COMPULSORY = 0;
    public const SELECTABLE = 1;
    public const SELECTABLE_COMPULSORY = 2;

    private static array $labels = [
        self::COMPULSORY => '必修',
        self::SELECTABLE => '選択',
        self::SELECTABLE_COMPULSORY => '選択必修',
    ];

    public static function label(self $compulsory): string
    {
        return self::$labels[$compulsory->getValue()];
    }
}
