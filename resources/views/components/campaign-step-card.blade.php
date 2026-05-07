<div class="campaign-step-card {{ $active ?? false ? 'active' : '' }}">
    <div class="campaign-step-number">
        {{ $number }}
    </div>

    <div>
        <h3>{{ $title }}</h3>
        <p>{{ $description }}</p>
    </div>
</div>