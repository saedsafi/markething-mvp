<div class="prompt-editor-card">

    <div class="prompt-editor-header">
        <div>
            <span class="hero-badge">{{ $badge ?? 'Prompt' }}</span>
            <h2>{{ $title }}</h2>
            <p>{{ $description }}</p>
        </div>

        <div class="prompt-version">
            <span>Current Version</span>
            <strong>{{ $version ?? 'v1.0' }}</strong>
        </div>
    </div>

    <div class="prompt-editor-body">

        <div class="form-group">
            <label class="form-label">Prompt Content</label>

            <textarea class="form-textarea prompt-textarea" placeholder="Write prompt here...">{{ $slot }}</textarea>
        </div>

        <div class="prompt-actions">
            <button class="btn btn-secondary" type="button" data-test-prompt>
                Test Preview
            </button>

            <button class="btn btn-secondary" type="button" data-save-draft-prompt>
                Save Draft
            </button>

            <button class="btn btn-primary" type="button" data-promote-prompt>
                Promote To Production
            </button>
        </div>

    </div>

</div>