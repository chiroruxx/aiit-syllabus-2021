<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Model;
use App\Models\SyllabusModel;
use App\Models\Syllabus;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use SplFileObject;

class SyllabusModelSeeder extends Seeder
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
        $syllabusModels = [];
        $csvList = ['ia_models.csv', 'ct_models.csv', 'bd_models.csv'];
        foreach ($csvList as $csv) {
            $syllabusModels = array_merge($syllabusModels, $this->readCsv("app/seeds/{$csv}"));
            $this->setHeader([]);
        }

        DB::transaction(
            function () use ($syllabusModels) {
                foreach ($syllabusModels as $record) {
                    /** @var Syllabus $syllabus */
                    $syllabus = Syllabus::whereNameJa($record['name'])
                        ->orWhere('name_ja', str_replace(' ', '', $record['name']))
                        ->first();

                    if ($syllabus === null) {
                        throw new ModelNotFoundException("Syllabus {$record['name']} is not found.");
                    }

                    $syllabusModels = array_map(
                        fn(array $attributes): SyllabusModel => new SyllabusModel($attributes), $record['types']
                    );
                    $syllabus->modelPivot()->saveMany($syllabusModels);
                }
            }
        );
    }

    private function readCsv(string $relativePath): array
    {
        $models = Model::pluck('id', 'name');

        $syllabusModels = [];

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

            $syllabusModel = [];
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

                // 誤植修正
                $heading = match ($heading) {
                    'プロジェクトマネージャ' => 'プロジェクトマネージャー',
                    'アントレプレナーモデル' => 'アントレプレナー',
                    'イントラプレナーモデル' => 'イントラプレナー',
                    '事業承継モデル' => '事業承継',
                    default => $heading,
                };

                $modelId = $models[$heading] ?? null;
                if ($modelId === null) {
                    throw new ModelNotFoundException("Model {$heading} is not found.");
                }

                $syllabusModel['types'][] = [
                    'model_id' => $modelId,
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

            $syllabusModel['name'] = trim($name);
            $syllabusModels[] = $syllabusModel;
        }
        return $syllabusModels;
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
