<div class="table-card">
    @isset($title)
        <div class="table-header">
            <h2>{{ $title }}</h2>

            @isset($action)
                <div>
                    {{ $action }}
                </div>
            @endisset
        </div>
    @endisset

    <div class="table-responsive">
        {{ $slot }}
    </div>
</div>