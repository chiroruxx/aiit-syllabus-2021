<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\Course;
use App\Models\Syllabus;
use App\Services\SyllabusService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SyllabusController extends Controller
{
    public function list(Request $request, SyllabusService $service): View
    {
        $selectedCourses = $this->getValidatedData($request)['courses'];

        $syllabi = $service->list($selectedCourses);

        $courses = Course::values();

        if (count($selectedCourses) === 0) {
            $selectedCourses = array_values(Course::toArray());
        }

        return view('syllabus.list', compact('syllabi', 'courses', 'selectedCourses'));
    }

    private function getValidatedData(Request $request): array
    {
        $validated = $this->getValidationFactory()->make($request->query(), [
            'courses' => ['array'],
            'courses.*' => [Rule::in(Course::toArray())],
        ])->validated();

        $validated['courses'] = $validated['courses'] ?? [];

        if (!is_array($validated['courses'])) {
            $validated['courses'] = [];
        }

        $validated['courses'] = array_map(array: $validated['courses'], callback: fn(int|string $course) => (int)$course);

        return $validated;
    }

    public function show(Syllabus $syllabus): View
    {
        return view('syllabus.show', compact('syllabus'));
    }
}
