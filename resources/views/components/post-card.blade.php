<div class="post-card" data-post-card>

    <button class="post-card-header" type="button" data-toggle-post>

        <div class="post-summary-left">
            <span class="post-number">{{ $number }}</span>

            <div>
                <h3>{{ $title }}</h3>
                <p>{{ $date }} · {{ $channel }} · {{ $media }}</p>
            </div>
        </div>

        <div class="post-summary-right">
            @isset($edited)
                <span class="post-indicator edited">Edited</span>
            @endisset

            @isset($regenerated)
                <span class="post-indicator regenerated">Regenerated</span>
            @endisset

            <span class="post-chevron">⌄</span>
        </div>

    </button>

    <div class="post-card-body">

        <div class="form-group">
            <label class="form-label">Caption</label>
            <textarea class="form-textarea post-editable" data-post-caption>{{ $caption }}</textarea>
        </div>

        <div class="form-group">
            <label class="form-label">Hashtags</label>
            <textarea class="form-textarea post-editable" data-post-hashtags>{{ $hashtags }}</textarea>
        </div>

        <div class="form-group">
            <label class="form-label">Creative Direction</label>
            <textarea class="form-textarea post-editable" data-post-creative>{{ $creative }}</textarea>
        </div>

        <div class="post-card-actions">

            <button class="btn btn-secondary" type="button" data-copy-post>
                Copy Post
            </button>

            <button class="btn btn-secondary" type="button" data-regenerate-post>
                ✦ Regenerate
            </button>

            <button class="btn btn-primary" type="button" data-save-post>
                Save Changes
            </button>

        </div>

    </div>

</div>