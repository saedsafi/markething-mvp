<div class="empty-state">
    <div class="empty-icon">{{ $icon ?? '✦' }}</div>

    <h3>{{ $title }}</h3>

    <p>{{ $description }}</p>

    @isset($action)
        <div class="empty-action">
            {{ $action }}
        </div>
    @endisset
</div>