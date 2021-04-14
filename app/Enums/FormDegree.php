<?php

declare(strict_types=1);

namespace App\Enums;

use MyCLabs\Enum\Enum;

class FormDegree extends Enum
{
    public const NONE = 0;
    public const SOMETIMES = 1;
    public const OFTEN = 2;
}
