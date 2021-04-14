<?php

declare(strict_types=1);

namespace Database\Seeders;

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
    private const COURSE_INFO = 0;
    private const COURSE_CREATION = 1;
    private const COURSE_BUSINESS = 2;
    private const COURSE_ALL = 3;
    private const COMPULSORY_COMPULSORY = 0;
    private const COMPULSORY_SELECTABLE = 1;
    private const COMPULSORY_SELECTABLE_COMPULSORY = 2;
    private const DEGREE_OFTEN = 2;
    private const DEGREE_SOMETIMES = 1;
    private const DEGREE_NONE = 0;
    private const SATELLITE_NONE = 0;
    private const SATELLITE_EXIST = 1;
    private const LESSON_TYPE_IN_PERSON = 0;
    private const LESSON_TYPE_VIDEO = 1;
    private const LESSON_TYPE_BOTH = 2;
    private const FORM_TYPE_MIXED = 0;
    private const FORM_TYPE_BIDIRECTIONAL = 1;
    private const FORM_TYPE_PERSONAL_WORK = 2;
    private const FORM_TYPE_GROUP_WORK = 3;
    private const FORM_TYPE_SATELLITE = 4;
    private const FORM_TYPE_OTHER = 5;

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
                        '情報アーキテクチャコース' => self::COURSE_INFO,
                        '創造技術コース' => self::COURSE_CREATION,
                        '事業設計工学コース' => self::COURSE_BUSINESS,
                        '全コース共通' => self::COURSE_ALL,
                        default => throw new DomainException("Course {$column} is not defined."),
                    };
                } elseif ($heading === '必修・選択') {
                    $value = match ($column) {
                        '必修' => self::COMPULSORY_COMPULSORY,
                        '選択' => self::COMPULSORY_SELECTABLE,
                        '選択必修' => self::COMPULSORY_SELECTABLE_COMPULSORY,
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
                        '◎' => self::DEGREE_OFTEN,
                        '○' => self::DEGREE_SOMETIMES,
                        '―' => self::DEGREE_NONE,
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
                        '有' => self::SATELLITE_EXIST,
                        'ー' => self::SATELLITE_NONE,
                        default => throw new DomainException("Satellite {$column} is not defined."),
                    };
                } elseif ($heading === '対面録画') {
                    $syllabus['lessons'][$lessonCount]['type'] = match ($column) {
                        '対面' => self::LESSON_TYPE_IN_PERSON,
                        '録画（対面無し）' => self::LESSON_TYPE_VIDEO,
                        '録画（対面有り）' => self::LESSON_TYPE_BOTH,
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
            self::FORM_TYPE_MIXED,
            self::FORM_TYPE_BIDIRECTIONAL,
            self::FORM_TYPE_PERSONAL_WORK,
            self::FORM_TYPE_GROUP_WORK,
            self::FORM_TYPE_SATELLITE,
            self::FORM_TYPE_OTHER,
        ];

        return $labels[$key];
    }
}
