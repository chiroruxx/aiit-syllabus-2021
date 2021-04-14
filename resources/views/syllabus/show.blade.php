@php
    /**
     * @var \App\Models\Syllabus $syllabus
     */
@endphp

    <!DOCTYPE html>
<html lang="en">
<head>
    <title>{{ $syllabus->name_ja }} - AIIT 2021 syllabus</title>
    <meta name="robots" content="noindex">
</head>
<body>
<h1>{{ $syllabus->name_ja }}</h1>
<h2>授業名</h2>
<dl>
    <dt>ja</dt>
    <dd>{{ $syllabus->name_ja }}</dd>
    <dt>en</dt>
    <dd>{{ $syllabus->name_en }}</dd>
</dl>
<h2>必修・選択</h2>
<p>{{ $syllabus->getCompulsoryLabel() }}</p>
<h2>単位</h2>
<p>{{ $syllabus->credit }}</p>
<h2>学期</h2>
<p>{{ $syllabus->quarter }}Q</p>
<h2>教員名</h2>
<p>{{ $syllabus->teacher }}</p>
<h2>概要</h2>
<p>{{ $syllabus->abstract }}</p>
<h2>目的・狙い</h2>
<p>{{ $syllabus->purpose }}</p>
<h2>前提知識（履修条件）</h2>
<p>{{ $syllabus->precondition }}</p>
<h2>到達目標</h2>
<dl>
    <dt>上位到達目標</dt>
    <dd>{{ $syllabus->higher_goal }}</dd>
    <dt>下位到達目標</dt>
    <dd>{{ $syllabus->lower_goal }}</dd>
</dl>
<h2>授業の内容</h2>
<p>{{ $syllabus->inside_learning }}</p>
<h2>授業外の学習</h2>
<p>{{ $syllabus->outside_learning }}</p>
<h2>成績評価</h2>
<p>{{ $syllabus->evaluation }}</p>
<h2>教科書・教材</h2>
<p>{{ $syllabus->text }}</p>
<h2>参考図書</h2>
<p>{{ $syllabus->reference }}</p>
<h2>授業の形態</h2>
<ul>
    @foreach($syllabus->forms as $form)
        <li>
            <h3>{{ $form->getTypeLabel() }}</h3>
            <h4>実施</h4>
            <p>
                @if($form->isDegreeOften())
                    ◎
                @elseif($form->isDegreeSometimes())
                    ○
                @elseif($form->isDegreeNone())
                    -
                @endif
            </p>
            <h4>特徴・留意点</h4>
            <p>{{ $form->feature }}</p>
        </li>
    @endforeach
</ul>
<h2>授業の計画</h2>
<ul>
    @foreach($syllabus->lessons as $lesson)
        <li>
            <h3>回数</h3>
            <p>第{{ $lesson->number }}回</p>
            <h3>内容</h3>
            <p>{{ $lesson->content }}</p>
            <h3>サテライト開講</h3>
            <p>{{ $lesson->hasSatellite() ? '有' : '無' }}</p>
            <h3>形式</h3>
            <ul>
                @if($lesson->isInPersonal())
                    <li>対面</li>
                @endif
                @if($lesson->isVideo())
                    <li>録画</li>
                @endif
            </ul>
        </li>
    @endforeach
</ul>

<a href="/">TOP</a>
</body>
</html>
