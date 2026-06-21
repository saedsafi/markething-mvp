@props([
    'label',
    'name',
    'value' => '',
    'required' => false,
    'helper' => 'The generated answer is only a suggestion and can be edited before saving.',
    'placeholder' => 'Write your answer...',
    'footer' => 'AI Assist uses the Business Context and your client account information to draft this field.',
    'max' => 500,
    'disabled' => null,
    'questionKey' => null,
    'clientId' => null,
])

@php
    $isDisabled =
        $disabled === true ||
        $disabled === 1 ||
        $disabled === '1';

    $hasQuestionKey = filled($questionKey);
@endphp

<div
    class="ai-field"
    data-ai-field
    data-question-key="{{ $questionKey }}"
    data-client-id="{{ $clientId }}"
    data-character-limit="{{ $max }}"
>
    <div class="ai-field-header">

        <label class="form-label {{ $required ? 'required' : '' }}">
            {{ $label }}
        </label>

        <button
            type="button"
            class="ai-assist-btn"
            data-open-ai-assist
            data-ai-label="{{ $label }}"
            data-ai-helper="{{ $helper }}"
            data-question-key="{{ $questionKey }}"
            data-client-id="{{ $clientId }}"
            data-character-limit="{{ $max }}"
            data-requires-business-context="{{ $isDisabled ? '1' : '0' }}"
            @if (! $hasQuestionKey)
                disabled
            @endif
        >
            ✦ Help me answer this
        </button>

    </div>

    <textarea
        class="form-textarea"
        name="{{ $name }}"
        @required($required)
        placeholder="{{ $placeholder }}"
        maxlength="{{ $max }}"
        data-ai-target-field
        data-ai-current-clicks="0"
    >{{ old($name, $value) }}</textarea>

    <div class="ai-field-footer">

        <p>
            {{ $footer }}
        </p>

        <span data-character-counter>
            {{ mb_strlen(old($name, $value ?? '')) }}/{{ $max }}
        </span>

    </div>

    <p class="ai-soft-warning hidden" data-ai-soft-warning>
        Try refining your business context above for better drafts.
    </p>


</div>