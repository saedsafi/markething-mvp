<div class="stat-card">
    <p class="stat-label">{{ $label }}</p>
    <h2 class="stat-value">{{ $value }}</h2>

    @isset($hint)
        <p class="stat-hint">{{ $hint }}</p>
    @endisset
</div>