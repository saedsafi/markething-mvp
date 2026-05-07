<div class="ai-field">

    <div class="ai-field-header">
        <label class="form-label">{{ $label }}</label>

        <button
            type="button"
            class="ai-assist-btn"
            {{ isset($disabled) && $disabled ? 'disabled' : '' }}
        >
            ✦ Help me answer this
        </button>
    </div>

    <textarea
        class="form-textarea"
        placeholder="{{ $placeholder ?? 'Write your answer...' }}"
        maxlength="{{ $max ?? 500 }}"
    >{{ $value ?? '' }}</textarea>

    <div class="ai-field-footer">
        <p>{{ $helper ?? 'AI Assist uses the Business Context above to draft a helpful answer.' }}</p>
        <span>0/{{ $max ?? 500 }}</span>
    </div>

</div>