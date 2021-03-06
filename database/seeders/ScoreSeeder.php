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

                if ($heading === '???????????????' || $heading === '????????????') {
                    continue;
                }

                if (in_array($heading, ['?????????', '??????'], strict: true)) {
                    $name = $column;
                    continue;
                }

                $scoreKey = $heading === '????????????' ? 'participants' : "score_{$heading}";
                $score[$scoreKey] = $column;
            }

            // ????????????
            $name = match ($name) {
                '??????????????????????????????????????????' => '???????????????????????????????????????',
                'IT???CIO???????????????' => 'IT???CIO??????',
                '??????????????????????????????????????????????????????' => '???????????????????????????????????????????????????',
                'DESIGN ???RE??? THINKING' => 'DESIGN???RE???THINKING',
                'ET???Embedded Technology???????????????' => 'ET(Embedded Technology)????????????',
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
