<div class="ai-field">

    <div class="ai-field-header">
        <label class="form-label">{{ $label }}</label>

        <button
            type="button"
            class="ai-assist-btn"
            data-open-ai-assist
            data-ai-label="{{ $label }}"
            data-ai-helper="{{ $helper ?? 'Add any extra details that may help MARKETHING draft a better answer.' }}"
            {{ isset($disabled) && $disabled ? 'disabled' : '' }}
        >
            ✦ Help me answer this
        </button>
    </div>

    <textarea
        class="form-textarea"
        placeholder="{{ $placeholder ?? 'Write your answer...' }}"
        maxlength="{{ $max ?? 500 }}"
        data-ai-target-field
    >{{ $value ?? '' }}</textarea>

    <div class="ai-field-footer">
        <p>{{ $footer ?? 'AI Assist uses the Business Context and your extra popup input to draft this field.' }}</p>
        <span>0/{{ $max ?? 500 }}</span>
    </div>

    <p class="ai-soft-warning hidden">
        Try refining your business context above for better drafts.
    </p>

</div>