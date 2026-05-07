<div class="persona-card">

    <div class="persona-card-top">
        <div class="persona-avatar">
            {{ $initials ?? 'AP' }}
        </div>

        <div>
            <h3>{{ $name }}</h3>
            <p>{{ $description }}</p>
        </div>
    </div>

    <div class="persona-meta">
        <span>{{ $age ?? '25-35' }}</span>
        <span>{{ $channel ?? 'Instagram' }}</span>
        <span>{{ $status ?? 'Active' }}</span>
    </div>

    <div class="persona-actions">
        <button class="mini-btn" type="button">Edit</button>
        <button class="mini-btn danger" type="button">Delete</button>
    </div>

</div>