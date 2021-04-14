<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\CompulsoryType;
use App\Enums\Course;
use App\Enums\FormDegree;
use App\Enums\FormType;
use App\Enums\LessonSatelliteType;
use App\Enums\LessonType;
use App\Models\Form;
use App\Models\Lesson;
use App\Models\Syllabus;
use DomainException;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use SplFileObject;

class SyllabusSeeder extends Seeder
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
        $syllabi = [];

        $path = storage_path('app/seeds.csv');
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

            $syllabus = [];
            $formCount = 0;
            $lessonCount = 1;
            foreach ($row as $key => $column) {
                $heading = $this->getHeading($key);
                // FIXME match式にする
                if ($heading === 'コース名') {
                    $syllabus['course'] = match ($column) {
                        '情報アーキテクチャコース' => Course::INFO,
                        '創造技術コース' => Course::CREATION,
                        '事業設計工学コース' => Course::BUSINESS,
                        '全コース共通' => Course::ALL,
                        default => throw new DomainException("Course {$column} is not defined."),
                    };
                } elseif ($heading === '必修・選択') {
                    $value = match ($column) {
                        '必修' => CompulsoryType::COMPULSORY,
                        '選択' => CompulsoryType::SELECTABLE,
                        '選択必修' => CompulsoryType::SELECTABLE_COMPULSORY,
                        default => throw new DomainException("Compulsory {$column} is not defined."),
                    };
                    $syllabus['compulsory'] = $value;
                } elseif ($heading === '単位数') {
                    $syllabus['credit'] = (int)$column;
                } elseif ($heading === '学期') {
                    $syllabus['quarter'] = (int)substr($column, 0, 1);
                } elseif ($heading === '科目群') {
                    $syllabus['group'] = $column;
                } elseif ($heading === '科目名') {
                    $syllabus['name_ja'] = $column;
                } elseif ($heading === '（英文表記）') {
                    $syllabus['name_en'] = $column;
                } elseif ($heading === '教員名') {
                    $syllabus['teacher'] = $column;
                } elseif ($heading === '概要') {
                    $syllabus['abstract'] = $column;
                } elseif ($heading === '目的・狙い') {
                    $syllabus['purpose'] = $column;
                } elseif ($heading === '前提知識（履修条件）') {
                    $syllabus['precondition'] = $column;
                } elseif ($heading === '上位到達目標') {
                    $syllabus['higher_goal'] = $column;
                } elseif ($heading === '下位到達目標') {
                    $syllabus['lower_goal'] = $column;
                } elseif ($heading === '程度') {
                    $formKey = $formCount % 6;
                    $value = match ($column) {
                        '◎' => FormDegree::OFTEN,
                        '○' => FormDegree::SOMETIMES,
                        '―' => FormDegree::NONE,
                        default => throw new DomainException("Degree {$column} is not defined.")
                    };
                    $syllabus['forms'][$formKey] = ['type' => $this->getFormType($formKey), 'degree' => $value];
                } elseif ($heading === '特徴・留意点') {
                    $formKey = $formCount % 6;
                    $syllabus['forms'][$formKey]['feature'] = $column;
                    $formCount++;
                } elseif ($heading === '授業外の学習') {
                    $syllabus['outside_learning'] = $column;
                } elseif ($heading === '授業の内容') {
                    $syllabus['inside_learning'] = $column;
                } elseif ($heading === '内容') {
                    $syllabus['lessons'][$lessonCount]['number'] = $lessonCount;
                    $syllabus['lessons'][$lessonCount]['content'] = $column;
                } elseif ($heading === 'サテライト開講') {
                    $syllabus['lessons'][$lessonCount]['satellite'] = match ($column) {
                        '有' => LessonSatelliteType::EXIST,
                        'ー' => LessonSatelliteType::NONE,
                        default => throw new DomainException("Satellite {$column} is not defined."),
                    };
                } elseif ($heading === '対面録画') {
                    $syllabus['lessons'][$lessonCount]['type'] = match ($column) {
                        '対面' => LessonType::IN_PERSON,
                        '録画（対面無し）' => LessonType::VIDEO,
                        '録画（対面有り）' => LessonType::BOTH,
                        default => throw new DomainException("Lesson type {$column} is not defined."),
                    };
                    $lessonCount++;
                } elseif ($heading === '成績評価') {
                    $syllabus['evaluation'] = $column;
                } elseif ($heading === '教科書・教材') {
                    $syllabus['text'] = $column;
                } elseif ($heading === '参考図書') {
                    $syllabus['reference'] = $column;
                }
            }

            $syllabi[] = $syllabus;
        }

        DB::transaction(function () use ($syllabi) {
            foreach ($syllabi as $record) {
                $syllabus = Syllabus::create(Arr::except($record, ['forms', 'lessons']));

                $forms = array_map(fn(array $attributes): Form => new Form($attributes), $record['forms']);
                $syllabus->forms()->saveMany($forms);

                $lessons = array_map(fn(array $attributes): Lesson => new Lesson($attributes), $record['lessons']);
                $syllabus->lessons()->saveMany($lessons);
            }
        });
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

    private function getFormType(int $key): int
    {
        static $labels = [
            FormType::FORM_TYPE_MIXED,
            FormType::FORM_TYPE_BIDIRECTIONAL,
            FormType::FORM_TYPE_PERSONAL_WORK,
            FormType::FORM_TYPE_GROUP_WORK,
            FormType::FORM_TYPE_SATELLITE,
            FormType::FORM_TYPE_OTHER,
        ];

        return $labels[$key];
    }
}
