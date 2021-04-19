<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Syllabus;
use Illuminate\Support\Collection;

class SyllabusService
{
    public function list(array $params): Collection
    {
        $syllabiQuery = Syllabus::orderBy('course')->orderBy('name_ja');

        if (isset($params['courses']) && count($params['courses']) > 0) {
            $syllabiQuery = $syllabiQuery->whereIn('course', $params['courses']);
        }
        if (isset($params['quarters']) && count($params['quarters']) > 0) {
            $syllabiQuery = $syllabiQuery->whereIn('quarter', $params['quarters']);
        }

        return $syllabiQuery->get();
    }
}
