<div class="toast" id="{{ $id ?? 'appToast' }}">
    <div class="toast-icon">✓</div>

    <div>
        <strong>{{ $title ?? 'Success' }}</strong>
        <p>{{ $message ?? 'Action completed successfully.' }}</p>
    </div>
</div>