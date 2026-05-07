<div class="config-card">

    <div class="config-card-header">
        <div>
            <h3>{{ $title }}</h3>
            <p>{{ $description }}</p>
        </div>

        <span>{{ $badge ?? 'Config' }}</span>
    </div>

    <div class="config-card-body">
        {{ $slot }}
    </div>

</div>