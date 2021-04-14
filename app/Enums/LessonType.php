<?php

declare(strict_types=1);

namespace App\Enums;

use MyCLabs\Enum\Enum;

class LessonType extends Enum
{
    public const IN_PERSON = 0;
    public const VIDEO = 1;
    public const BOTH = 2;
}
