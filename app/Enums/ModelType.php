<?php

declare(strict_types=1);

namespace App\Enums;

use MyCLabs\Enum\Enum;

class ModelType extends Enum
{
    public const STRATEGIST = 0;
    public const SYSTEM_ARCHITECT = 1;
    public const PROJECT_MANAGER = 2;
    public const TECHNICAL_SPECIALIST = 3;

    private static array $label = [
        self::STRATEGIST => 'ストラテジスト',
        self::SYSTEM_ARCHITECT => 'システムアーキテクト',
        self::PROJECT_MANAGER => 'プロジェクトマネージャー',
        self::TECHNICAL_SPECIALIST => 'テクニカルスペシャリスト',
    ];

    public function label(): string
    {
        return self::$label[$this->getValue()];
    }
}
