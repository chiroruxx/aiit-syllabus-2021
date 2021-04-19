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
        $params = $this->getSearchParameters($request);

        $syllabi = $service->list($params);

        $courses = Course::values();
        $quarters = [1, 2, 3, 4];

        $selected = [];
        $selected['courses'] = count($params['courses']) > 0 ? $params['courses'] : array_values(Course::toArray());
        $selected['quarters'] = count($params['quarters']) > 0 ? $params['quarters'] : $quarters;

        return view(
            'syllabus.list',
            compact('syllabi', 'courses', 'quarters', 'selected')
        );
    }

    private function getSearchParameters(Request $request): array
    {
        $validated = $this->getValidationFactory()->make($request->query(), [
            'search' => ['array'],
            'search.courses' => ['array'],
            'search.courses.*' => [Rule::in(Course::toArray())],
            'search.quarters' => ['array'],
            'search.quarters.*' => ['integer', 'min:1', 'max:4'],
        ])->validated();

        $search = $validated['search'] ?? [];

        foreach (['courses', 'quarters'] as $key) {
            $search[$key] = $search[$key] ?? [];

            if (!is_array($search[$key])) {
                $search[$key] = [];
            }

            $search[$key] = array_map(array: $search[$key], callback: fn(int|string $course) => (int)$course);
        }

        return $search;
    }

    public function show(Syllabus $syllabus): View
    {
        return view('syllabus.show', compact('syllabus'));
    }
}
