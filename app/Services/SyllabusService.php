<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Syllabus;
use Illuminate\Support\Collection;

class SyllabusService
{
    public function list(array $courses): Collection
    {
        $syllabiQuery = Syllabus::orderBy('course')->orderBy('name_ja');

        if (count($courses) > 0) {
            $syllabiQuery = $syllabiQuery->whereIn('course', $courses);
        }

        return $syllabiQuery->get();
    }
}
