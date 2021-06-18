<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Model;
use Illuminate\Http\JsonResponse;

class MasterController extends Controller
{
    public function list(): JsonResponse
    {
        $courses = Course::with('models')->get();
        $courses = $courses->map(
            fn(Course $course): array => [
                'label' => $course->name,
                'value' => $course->id,
                'models' => $course->models->map(
                    fn(Model $model): array => [
                        'label' => $model->name,
                        'value' => $model->id,
                    ]
                )->values()->all(),
            ]
        )->values()->all();

        $quarters = [1, 2, 3, 4];

        return response()->json(compact('courses', 'quarters'));
    }
}
