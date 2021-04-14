@php
    /**
     * @var \Illuminate\Support\Collection&\App\Models\Syllabus[] $syllabi
     */
@endphp

    <!DOCTYPE html>
<html lang="en">
<head>
    <title>AIIT 2021 syllabus</title>
    <meta name="robots" content="noindex">
</head>
<body>
<h1>AIIT 2021 syllabus</h1>
<table>
    <thead>
    <tr>
        <th>授業名</th>
        <th>コース</th>
        <th>クォーター</th>
    </tr>
    </thead>
    <tbody>
    @foreach($syllabi as $syllabus)
        <tr>
            <td><a href="{{ route('syllabus.show', $syllabus) }}">{{ $syllabus->name_ja }}</a></td>
            <td>{{ $syllabus->getCourseLabel() }}</td>
            <td>{{ $syllabus->quarter }}Q</td>
        </tr>
    @endforeach
    </tbody>
</table>
</body>
</html>
