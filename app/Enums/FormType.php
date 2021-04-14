<?php

declare(strict_types=1);

namespace App\Enums;

use MyCLabs\Enum\Enum;

class FormType extends Enum
{
    public const FORM_TYPE_MIXED = 0;
    public const FORM_TYPE_BIDIRECTIONAL = 1;
    public const FORM_TYPE_PERSONAL_WORK = 2;
    public const FORM_TYPE_GROUP_WORK = 3;
    public const FORM_TYPE_SATELLITE = 4;
    public const FORM_TYPE_OTHER = 5;

    private static array $labels = [
        self::FORM_TYPE_MIXED => '録画・対面混合授業',
        self::FORM_TYPE_BIDIRECTIONAL => '講義（双方向）',
        self::FORM_TYPE_PERSONAL_WORK => '実習・演習（個人）',
        self::FORM_TYPE_GROUP_WORK => '実習・演習（グループ）',
        self::FORM_TYPE_SATELLITE => 'サテライト開講授業',
        self::FORM_TYPE_OTHER => 'その他',
    ];

    public static function label(self $course): string
    {
        return self::$labels[$course->getValue()];
    }
}
