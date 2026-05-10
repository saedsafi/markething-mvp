<button
    {{ $attributes->merge([
        'type' => $type ?? 'button',
        'class' => 'btn ' . ($variant ?? 'btn-primary')
    ]) }}
>
    {{ $slot }}
</button>