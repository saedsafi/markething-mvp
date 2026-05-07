<button
    type="{{ $type ?? 'button' }}"
    class="btn {{ $variant ?? 'btn-primary' }}"
>
    {{ $slot }}
</button>