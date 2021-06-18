@extends('layout')
@section('title', 'AIIT 2021 syllabus')

@section('content')
    @php
        /**
         * @var \Illuminate\Support\Collection&\App\Models\Syllabus[] $syllabi
         */
    @endphp
    <header class="w-full py-16 bg-yellow-300">
        <h1 class="text-3xl ml-4">AIIT 2021 syllabus</h1>
    </header>

    <div id="search"></div>

    <table class="my-16 w-full border-collapse">
        <thead>
        <tr class="border-double border-b-2 border-gray-400 bg-gray-100">
            <th class="py-2 pl-4 text-left">授業名</th>
            <th class="py-2 text-left">コース</th>
            <th class="py-2">時期</th>
        </tr>
        </thead>
        <tbody>
        @foreach($syllabi as $syllabus)
            <tr class="even:bg-gray-100 hover:bg-gray-200">
                <td class="py-2 pl-4"><a href="{{ route('syllabus.show', $syllabus) }}" class="underline">{{ $syllabus->name_ja }}</a></td>
                <td class="py-2">{{ $syllabus->course->name }}</td>
                <td class="py-2 text-center">{{ $syllabus->quarter }}Q</td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
