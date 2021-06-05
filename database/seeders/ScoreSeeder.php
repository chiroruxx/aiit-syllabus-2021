<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Score;
use App\Models\Syllabus;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use SplFileObject;

class ScoreSeeder extends Seeder
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
        $scores = [];
        $csvList = ['scores_2020_1.csv','scores_2020_2.csv','scores_2020_3.csv','scores_2020_4.csv'];
        foreach ($csvList as $csv) {
            $scores = array_merge($scores, $this->readCsv("app/seeds/{$csv}"));
            $this->setHeader([]);
        }

        DB::transaction(
            function () use ($scores) {
                foreach ($scores as $record) {
                    /** @var Syllabus $syllabus */
                    $syllabus = Syllabus::whereNameJa($record['name'])->first();

                    if ($syllabus === null) {
                        throw new ModelNotFoundException("Syllabus {$record['name']} is not found.");
                    }
                    unset($record['name']);

                    if ($syllabus->score === null) {
                        $syllabus->score()->save(new Score($record));
                    } else {
                        $score = $syllabus->score;
                        foreach ($score->getFillable() as $attribute) {
                            $score->$attribute += $record[$attribute];
                        }
                        $score->save();
                    }
                }
            }
        );
    }

    private function readCsv(string $relativePath): array
    {
        $scores = [];

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

            $score = [];
            $name = '';
            foreach ($row as $key => $column) {
                $heading = $this->getHeading($key);

                if ($column === null || $column === '') {
                    continue;
                }

                if ($heading === '専攻コース' || $heading === '担当教員') {
                    continue;
                }

                if (in_array($heading, ['科目名', '科目'], strict: true)) {
                    $name = $column;
                    continue;
                }

                $scoreKey = $heading === '受講者数' ? 'participants' : "score_{$heading}";
                $score[$scoreKey] = $column;
            }

            // 誤植修正
            $name = match ($name) {
                '会計・ファインナンス工学特論' => '会計・ファイナンス工学特論',
                'IT・CIO特論コース' => 'IT・CIO特論',
                '統計・数理軽量ファインナンス特別演習' => '統計・数理計量ファイナンス特別演習',
                'DESIGN ［RE］ THINKING' => 'DESIGN［RE］THINKING',
                'ET（Embedded Technology）特別演習' => 'ET(Embedded Technology)特別演習',
                default => $name,
            };

            $score['name'] = trim($name);
            $scores[] = $score;
        }
        return $scores;
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
