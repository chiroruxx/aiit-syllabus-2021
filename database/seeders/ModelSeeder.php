<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Model;
use Illuminate\Database\Seeder;

class ModelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $records = [
            ['name' => 'ストラテジスト', 'course' => '情報アーキテクチャコース'],
            ['name' => 'システムアーキテクト', 'course' => '情報アーキテクチャコース'],
            ['name' => 'プロジェクトマネージャー', 'course' => '情報アーキテクチャコース'],
            ['name' => 'テクニカルスペシャリスト', 'course' => '情報アーキテクチャコース'],
            ['name' => 'アントレプレナー', 'course' => '事業設計工学コース'],
            ['name' => 'イントラプレナー', 'course' => '事業設計工学コース'],
            ['name' => '事業承継', 'course' => '事業設計工学コース'],
            ['name' => 'インダストリアルデザイナー', 'course' => '創造技術コース'],
            ['name' => '開発設計エンジニア', 'course' => '創造技術コース'],
            ['name' => 'AI・データサイエンティスト', 'course' => '創造技術コース'],
            ['name' => 'グローバルエンジニアリング', 'course' => '創造技術コース'],
        ];

        $courses = Course::pluck('id', 'name');

        foreach ($records as $record) {
            Model::insert(
                [
                    'name' => $record['name'],
                    'course_id' => $courses[$record['course']],
                    'created_at' => now(),
                    'updated_at' => now()
                ]
            );
        }
    }
}
