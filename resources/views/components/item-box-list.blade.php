@props(['items'])

<div class="flex flex-row space-x-4">
    @foreach($items as $item)
        @if(isset($item['unit']))
            <x-item-box-with-unit :content="$item['content']" :unit="$item['unit']" />
        @else
            <x-item-box>
                {{ $item['content'] }}
            </x-item-box>
        @endif
    @endforeach
</div>
