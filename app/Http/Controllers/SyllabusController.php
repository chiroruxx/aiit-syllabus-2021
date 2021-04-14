<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Syllabus;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class SyllabusController extends Controller
{
    public function list(): View
    {
        $syllabi = Syllabus::orderBy('course')->orderBy('name_ja')->get();

        return view('syllabus.list', compact('syllabi'));
    }

    public function show(Syllabus $syllabus): View
    {
        return view('syllabus.show', compact('syllabus'));
    }
}
