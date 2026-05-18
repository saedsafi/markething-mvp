<div
    class="modal-overlay {{ $class ?? '' }}"
    id="{{ $id ?? 'appModal' }}"
>

    <div class="modal-card">

        <div class="modal-header">

            <div>

                <h2>
                    {{ $title }}
                </h2>

                @isset($subtitle)

                    <p>
                        {{ $subtitle }}
                    </p>

                @endisset

            </div>

            <button
                class="modal-close"
                type="button"
                data-close-modal
            >
                ×
            </button>

        </div>

        <div class="modal-body">

            {{ $slot }}

        </div>

    </div>

</div>