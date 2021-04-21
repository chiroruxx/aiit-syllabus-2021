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
    public const ENTREPRENEUR = 4;
    public const INTOREPRENEUR = 5;
    public const BUSINESS_SUCCESSION = 6;
    public const INDUSTRIAL_DESIGNER = 7;
    public const DEVELOPMENT_DESIGN_ENGINEER = 8;
    public const AI_DATA_SCIENTIST = 9;
    public const GLOBAL_ENGINEERING = 10;

    private static array $label = [
        self::STRATEGIST => 'ストラテジスト',
        self::SYSTEM_ARCHITECT => 'システムアーキテクト',
        self::PROJECT_MANAGER => 'プロジェクトマネージャー',
        self::TECHNICAL_SPECIALIST => 'テクニカルスペシャリスト',
        self::ENTREPRENEUR => 'アントレプレナー',
        self::INTOREPRENEUR => 'イントラプレナー',
        self::BUSINESS_SUCCESSION => '事業承継',
        self::INDUSTRIAL_DESIGNER => 'インダストリアルデザイナー',
        self::DEVELOPMENT_DESIGN_ENGINEER => '開発設計エンジニア',
        self::AI_DATA_SCIENTIST => 'AI・データサイエンティスト',
        self::GLOBAL_ENGINEERING => 'グローバルエンジニアリング',
    ];

    public function label(): string
    {
        return self::$label[$this->getValue()];
    }
}
