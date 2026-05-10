<div class="breadcrumbs">

    @if(request()->is('admin*'))
        <a href="{{ url('/admin/dashboard') }}">Home</a>
    @else
        <a href="{{ url('/agency/dashboard') }}">Home</a>
    @endif

    @isset($items)
        @foreach ($items as $item)
            <span>/</span>

            <a
                href="{{ $item['url'] ?? '#' }}"
                class="{{ $loop->last ? 'active' : '' }}"
            >
                {{ $item['label'] }}
            </a>
        @endforeach
    @endisset

</div>