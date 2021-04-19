<?php

declare(strict_types=1);

namespace Database\Seeders;

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

        $path = storage_path('app/seeds/ia_models.csv');
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

                if ($column === null) {
                    continue;
                }

                if ($heading === '科目名') {
                    $name = $column;
                    continue;
                }

                $modelType = match ($heading) {
                    'ストラテジスト' => 0,
                    'システムアーキテクト' => 1,
                    'プロジェクトマネージャ' => 2,
                    'テクニカルスペシャリスト' => 3,
                    default => throw new DomainException("Name {$column} is not defined."),
                };

                $model['types'][] = [
                    'type' => $modelType,
                    'is_basic' => $column === '☆',
                ];
            }

            $model['name'] = $name;
            $models[] = $model;
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
