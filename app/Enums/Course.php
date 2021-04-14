<?php

declare(strict_types=1);

namespace App\Enums;

use MyCLabs\Enum\Enum;

class Course extends Enum
{
    public const INFO = 0;
    public const CREATION = 1;
    public const BUSINESS = 2;
    public const ALL = 3;

    private static array $labels = [
        self::INFO => '情報アーキテクチャコース',
        self::CREATION => '創造技術コース',
        self::BUSINESS => '事業設計工学コース',
        self::ALL => '全コース共通',
    ];

    public static function label(self $course): string
    {
        return self::$labels[$course->getValue()];
    }
}
