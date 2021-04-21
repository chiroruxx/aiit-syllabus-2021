<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\Course;
use App\Enums\ModelType;
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
        $modelTypes = ModelType::values();
        $quarters = [1, 2, 3, 4];

        $selected = [];
        $selected['courses'] = count($params['courses']) > 0 ? $params['courses'] : array_values(Course::toArray());
        $selected['quarters'] = count($params['quarters']) > 0 ? $params['quarters'] : $quarters;
        $selected['model']['types'] = count($params['model']['types']) > 0 ? $params['model']['types'] : array_values(
            ModelType::toArray()
        );

        return view(
            'syllabus.list',
            compact('syllabi', 'courses', 'modelTypes', 'quarters', 'selected')
        );
    }

    private function getSearchParameters(Request $request): array
    {
        $validated = $this->getValidationFactory()->make(
            $request->query(),
            [
                'search' => ['array'],
                'search.courses' => ['array'],
                'search.courses.*' => [Rule::in(Course::toArray())],
                'search.models' => ['array'],
                'search.models.type' => ['array'],
                'search.models.*' => [Rule::in(ModelType::toArray())],
                'search.quarters' => ['array'],
                'search.quarters.*' => ['integer', 'min:1', 'max:4'],
            ]
        )->validated();

        $search = $validated['search'] ?? [];

        foreach (['courses', 'quarters', ['model', 'types']] as $key) {
            $search = $this->formatParameters($search, $key);
        }

        return $search;
    }

    public function show(Syllabus $syllabus): View
    {
        return view('syllabus.show', compact('syllabus'));
    }

    private function formatParameters(array $input, string|array $key): array
    {
        if (is_array($key)) {
            if (count($key) === 0) {
                return $input;
            }
            if (count($key) === 1) {
                return $this->formatParameters($input, $key[0]);
            }

            $firstKey = array_shift($key);
            $input = $this->trim($input, $firstKey);
            $input[$firstKey] = $this->formatParameters($input[$firstKey], $key);

            return $input;
        }

        $input = $this->trim($input, $key);

        $input[$key] = $this->cast($input[$key]);

        return $input;
    }

    private function trim(array $input, string $key): array
    {
        $input[$key] = $input[$key] ?? [];

        if (!is_array($input[$key])) {
            $input[$key] = [];
        }

        return $input;
    }

    private function cast(array $input): array
    {
        return array_map(
            array: $input,
            callback: fn(int|string $value): int => (int)$value
        );
    }
}
