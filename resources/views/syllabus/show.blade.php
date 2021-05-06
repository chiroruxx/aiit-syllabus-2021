@extends('layout')

@section('title', "{$syllabus->name_ja} - AIIT 2021 syllabus")

@section('content')
    @php
        /**
         * @var \App\Models\Syllabus $syllabus
         */

        $items = [
            ['content' => $syllabus->getCompulsoryLabel()],
            ['content' => $syllabus->credit, 'unit' => '単位'],
            ['content' => $syllabus->quarter, 'unit' => 'Q'],
        ];
    @endphp

    <header class="w-full py-16 bg-yellow-300">
        <h2 class="text-3xl ml-4"><a href="/">AIIT 2021 syllabus</a></h2>
    </header>

    <div class="container w-4/5 mx-auto">
        <div class="my-16 flex flex-row justify-between flex-wrap space-y-4">
            <h3 class="text-3xl flex">{{ $syllabus->name_ja }} ( {{ $syllabus->name_en }} )</h3>
            <div class="flex mt-auto">
                @include('icons.user') {{ $syllabus->teacher }}
            </div>
        </div>
        <x-item-box-list :items="$items" />

        <div class="border-b mt-16 mb-4">
            <h3 class="text-2xl">概要</h3>
        </div>
        <p class="pl-4">{!! nl2br(e($syllabus->abstract)) !!}</p>

        <div class="border-b mt-16 mb-4">
            <h3 class="text-2xl">目的・狙い</h3>
        </div>
        <p class="pl-4">{!! nl2br(e($syllabus->purpose)) !!}</p>

        <h4 class="text-xl my-4">到達目標</h4>
        <ul class="pl-4">
            <li class="mb-2">@include('icons.arrow-circle-down') {{ $syllabus->lower_goal }}</li>
            <li class="mb-2">@include('icons.arrow-circle-up') {{ $syllabus->higher_goal }}</li>
        </ul>

        <div class="border-b mt-16 mb-4">
            <h3 class="text-2xl">前提知識（履修条件）</h3>
        </div>
        <p class="pl-4">{!! nl2br(e($syllabus->precondition)) !!}</p>

        <div class="border-b mt-16 mb-4">
            <h3 class="text-2xl">授業の内容</h3>
        </div>
        <p class="pl-4">{!! nl2br(e($syllabus->inside_learning)) !!}</p>

        <div class="border-b mt-16 mb-4">
            <h3 class="text-2xl">授業外の学習</h3>
        </div>
        <p class="pl-4">{!! nl2br(e($syllabus->outside_learning)) !!}</p>

        <div class="border-b mt-16 mb-4">
            <h3 class="text-2xl">成績評価</h3>
        </div>
        <p class="pl-4">{!! nl2br(e($syllabus->evaluation)) !!}</p>

        <div class="border-b mt-16 mb-4">
            <h3 class="text-2xl">教科書・教材</h3>
        </div>
        <p class="pl-4">{!! nl2br(e($syllabus->text)) !!}</p>

        <div class="border-b mt-16 mb-4">
            <h3 class="text-2xl">参考図書</h3>
        </div>
        <p class="pl-4">{!! nl2br(e($syllabus->reference)) !!}</p>

        <div class="border-b mt-16 mb-4">
            <h3 class="text-2xl">授業の形態</h3>
        </div>
        <div class="flex flex-column flex-wrap pl-4">
            @foreach($syllabus->forms as $form)
                <div class="flex flex-row w-full h-32 space-x-4 items-center pl-4 even:bg-gray-100 hover:bg-gray-200">
                    <div class="flex flex-none w-5">
                        @if($form->isDegreeOften())
                            ◎
                        @elseif($form->isDegreeSometimes())
                            ○
                        @elseif($form->isDegreeNone())
                            -
                        @endif
                    </div>
                    <div class="flex flex-shrink-0 w-52">
                        {{ $form->getTypeLabel() }}
                    </div>
                    <div class="flex flex-grow">
                        {{ $form->feature }}
                    </div>
                </div>
            @endforeach
        </div>

        <div class="border-b mt-16 mb-4">
            <h3 class="text-2xl">授業の計画</h3>
        </div>
        <div class="flex flex-column flex-wrap pl-4">
            @foreach($syllabus->lessons as $lesson)
                <div class="flex flex-row w-ful h-32 min-w-full space-x-4 items-center pl-4 pr-4 even:bg-gray-100 hover:bg-gray-200">
                    <div class="flex flex-none w-5">
                        {{ $lesson->number }}
                    </div>
                    <div class="flex flex-grow">
                        {{ $lesson->content }}
                    </div>
                    <div class="flex flex-none w-5">
                        {{ $lesson->hasSatellite() ? '有' : '無' }}
                    </div>
                    <ul class="flex flex-none w-16">
                        @if($lesson->isInPersonal())
                            <li>対面</li>
                        @endif
                        @if($lesson->isVideo())
                            <li>録画</li>
                        @endif
                    </ul>
                </div>
            @endforeach
        </div>
    </div>
@endsection
