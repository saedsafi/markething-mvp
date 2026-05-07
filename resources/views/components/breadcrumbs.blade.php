<div class="breadcrumbs">
    <a href="{{ url('/agency/dashboard') }}">Home</a>

    @isset($items)
        @foreach ($items as $item)
            <span>/</span>
            <a href="{{ $item['url'] ?? '#' }}" class="{{ $loop->last ? 'active' : '' }}">
                {{ $item['label'] }}
            </a>
        @endforeach
    @endisset
</div>