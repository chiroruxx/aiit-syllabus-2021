<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Course;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $records = [
            '情報アーキテクチャコース',
            '創造技術コース',
            '事業設計工学コース',
            '全コース共通',
        ];

        foreach ($records as $name) {
            Course::insert(
                [
                    'name' => $name,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}
