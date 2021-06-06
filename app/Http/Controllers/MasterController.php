<?php

namespace App\Http\Controllers;

use App\Enums\Course;
use App\Enums\ModelType;
use Illuminate\Http\JsonResponse;

class MasterController extends Controller
{
    public function list(): JsonResponse
    {
        $courses = collect(Course::values());
        $courses = $courses->map(fn(Course $course): array => [
            'label' => $course->label(),
            'value' => $course->getValue(),
        ])->values()->all();

        $quarters = [1, 2, 3, 4];

        $modelTypes = collect(ModelType::values());
        $modelTypes = $modelTypes->map(fn(ModelType $modelType): array => [
            'label' => $modelType->label(),
            'value' => $modelType->getValue(),
        ])->values()->all();

        return response()->json(compact('courses', 'quarters', 'modelTypes'));
    }
}
