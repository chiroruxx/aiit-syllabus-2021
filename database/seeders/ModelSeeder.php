<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\ModelType;
use App\Models\Model;
use App\Models\Syllabus;
use DomainException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use SplFileObject;

class ModelSeeder extends Seeder
{
    private array $header = [];

    /**
     * Run the database seeds.
     *
     * @return void
     * @throws \Throwable When failed transactions.
     */
    public function run()
    {
        $models = [];
//        $csvList = ['ia_models.csv', 'bd_models.csv'];
        $csvList = ['ia_models.csv', 'ct_models.csv', 'bd_models.csv'];
        foreach ($csvList as $csv) {
            $models = array_merge($models, $this->readCsv("app/seeds/{$csv}"));
            $this->setHeader([]);
        }

        DB::transaction(
            function () use ($models) {
                foreach ($models as $record) {
                    /** @var Syllabus $syllabus */
                    $syllabus = Syllabus::whereNameJa($record['name'])
                        ->orWhere('name_ja', str_replace(' ', '', $record['name']))
                        ->first();

                    if ($syllabus === null) {
                        throw new ModelNotFoundException("Syllabus {$record['name']} is not found.");
                    }

                    $models = array_map(fn(array $attributes): Model => new Model($attributes), $record['types']);
                    $syllabus->models()->saveMany($models);
                }
            }
        );
    }

    private function readCsv(string $relativePath): array
    {
        $models = [];

        $path = storage_path($relativePath);
        if (!file_exists($path)) {
            echo("Cannot find seed file at '{$path}'");
        }

        $file = new SplFileObject($path);
        $file->setFlags(SplFileObject::READ_AHEAD | SplFileObject::SKIP_EMPTY | SplFileObject::DROP_NEW_LINE);
        while (!$file->eof()) {
            $row = $file->fgetcsv();
            if ($row === null) {
                continue;
            }

            if ($this->hasHeader()) {
                $this->setHeader($row);
                continue;
            }

            $model = [];
            $name = '';
            foreach ($row as $key => $column) {
                $heading = $this->getHeading($key);

                if ($column === null || $column === '') {
                    continue;
                }

                if (in_array($heading, ['科目名', '科目'], strict: true)) {
                    $name = $column;
                    continue;
                }

                $modelType = match ($heading) {
                    'ストラテジスト' => ModelType::STRATEGIST,
                    'システムアーキテクト' => ModelType::SYSTEM_ARCHITECT,
                    'プロジェクトマネージャ' => ModelType::PROJECT_MANAGER,
                    'テクニカルスペシャリスト' => ModelType::TECHNICAL_SPECIALIST,
                    'アントレプレナーモデル' => ModelType::ENTREPRENEUR,
                    'イントラプレナーモデル' => ModelType::INTOREPRENEUR,
                    '事業承継モデル' => ModelType::BUSINESS_SUCCESSION,
                    'インダストリアルデザイナー' => ModelType::INDUSTRIAL_DESIGNER,
                    '開発設計エンジニア' => ModelType::DEVELOPMENT_DESIGN_ENGINEER,
                    'AI・データサイエンティスト' => ModelType::AI_DATA_SCIENTIST,
                    'グローバルエンジニアリング' => ModelType::GLOBAL_ENGINEERING,
                    default => throw new DomainException("Name {$column} is not defined."),
                };

                $model['types'][] = [
                    'type' => $modelType,
                    'is_basic' => $column === '☆',
                ];
            }

            // 誤植修正
            $name = match ($name) {
                'DESING[RE]THINKING ' => 'DESIGN［RE］THINKING',
                'ビックデータ解析特論' => 'ビッグデータ解析特論',
                'ET（Embedded Technology）特別演習' => 'ET(Embedded Technology)特別演習',
                default => $name,
            };

            $model['name'] = trim($name);
            $models[] = $model;
        }
        return $models;
    }

    private function hasHeader(): bool
    {
        return count($this->header) === 0;
    }

    private function setHeader(array $header): void
    {
        $this->header = $header;
    }

    private function getHeading(int $key): string
    {
        return $this->header[$key];
    }
}
