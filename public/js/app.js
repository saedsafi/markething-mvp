const sidebar = document.getElementById('sidebar');
const sidebarOverlay = document.getElementById('sidebarOverlay');
const menuToggle = document.getElementById('menuToggle');
const sidebarClose = document.getElementById('sidebarClose');

function openSidebar() {
    sidebar?.classList.add('open');
    sidebarOverlay?.classList.add('show');
}

function closeSidebar() {
    sidebar?.classList.remove('open');
    sidebarOverlay?.classList.remove('show');
}

menuToggle?.addEventListener('click', openSidebar);
sidebarClose?.addEventListener('click', closeSidebar);
sidebarOverlay?.addEventListener('click', closeSidebar);

document.querySelectorAll('.sidebar-link').forEach(link => {
    link.addEventListener('click', function () {
        document.querySelectorAll('.sidebar-link').forEach(item => {
            item.classList.remove('active');
        });

        this.classList.add('active');

        if (window.innerWidth <= 900) {
            closeSidebar();
        }
    });
});

document.querySelectorAll('[data-open-modal]').forEach(button => {
    button.addEventListener('click', () => {
        const modalId = button.getAttribute('data-open-modal');
        document.getElementById(modalId)?.classList.add('show');
    });
});

document.querySelectorAll('[data-close-modal]').forEach(button => {
    button.addEventListener('click', () => {
        button.closest('.modal-overlay')?.classList.remove('show');
    });
});

document.querySelectorAll('.modal-overlay').forEach(overlay => {
    overlay.addEventListener('click', event => {
        if (event.target === overlay) {
            overlay.classList.remove('show');
        }
    });
});

function showToast(id = 'appToast') {
    const toast = document.getElementById(id);

    if (!toast) return;

    toast.classList.add('show');

    setTimeout(() => {
        toast.classList.remove('show');
    }, 3000);
}

/* STEP 6 CLIENT PROFILE INTERACTIONS */

// Business Context live counter
document.querySelectorAll('.business-context-input').forEach((textarea) => {
    const counter = textarea.closest('.form-group')?.querySelector('.char-counter span');

    function updateCounter() {
        if (!counter) return;

        const max = textarea.getAttribute('maxlength') || 5000;
        counter.textContent = `${textarea.value.length}/${max} characters`;
    }

    textarea.addEventListener('input', updateCounter);
    updateCounter();
});

// AI field live character counters
document.querySelectorAll('.ai-field textarea').forEach((textarea) => {
    const counter = textarea.closest('.ai-field')?.querySelector('.ai-field-footer span');

    function updateAiCounter() {
        if (!counter) return;

        const max = textarea.getAttribute('maxlength') || 500;
        counter.textContent = `${textarea.value.length}/${max}`;
    }

    textarea.addEventListener('input', updateAiCounter);
    updateAiCounter();
});

/* V2 PATCH 2 — AI ASSIST POPUP FLOW */

let activeAiField = null;
let activeAiButton = null;
const aiAssistClickCounts = new WeakMap();

document.querySelectorAll('[data-open-ai-assist]').forEach((button) => {
    button.addEventListener('click', () => {
        const aiField = button.closest('.ai-field');
        const textarea = aiField?.querySelector('[data-ai-target-field]');

        if (!aiField || !textarea) return;

        activeAiField = aiField;
        activeAiButton = button;

        const label = button.getAttribute('data-ai-label') || 'Selected field';
        const helper = button.getAttribute('data-ai-helper') || 'Add any extra details that may help MARKETHING draft a better answer.';

        const labelElement = document.getElementById('aiAssistFieldLabel');
        const helperElement = document.getElementById('aiAssistHelperText');
        const extraInput = document.getElementById('aiAssistExtraInput');

        if (labelElement) labelElement.textContent = label;
        if (helperElement) helperElement.textContent = helper;
        if (extraInput) extraInput.value = '';

        document.getElementById('aiAssistModal')?.classList.add('show');
    });
});

document.getElementById('submitAiAssist')?.addEventListener('click', () => {
    if (!activeAiField || !activeAiButton) return;

    const textarea = activeAiField.querySelector('[data-ai-target-field]');
    const modal = document.getElementById('aiAssistModal');

    if (!textarea) return;

    if (textarea.value.trim() !== '') {
        const shouldReplace = confirm('This will replace your current text. Continue?');

        if (!shouldReplace) return;
    }

    modal?.classList.remove('show');

    const currentCount = aiAssistClickCounts.get(activeAiField) || 0;
    const nextCount = currentCount + 1;

    aiAssistClickCounts.set(activeAiField, nextCount);

    if (nextCount >= 3) {
        activeAiField.querySelector('.ai-soft-warning')?.classList.remove('hidden');
    }

    activeAiButton.disabled = true;
    activeAiButton.classList.add('is-loading');
    activeAiButton.textContent = '✦ Drafting...';

    activeAiField.classList.add('is-drafting');
    textarea.setAttribute('readonly', true);

    setTimeout(() => {
        const extraInstructions = document.getElementById('aiAssistExtraInput')?.value.trim();

        textarea.value =
            extraInstructions
                ? `Draft based on your business context and extra guidance: ${extraInstructions}. This answer is clear, brand-aware, and ready to edit.`
                : 'This answer was drafted using the Business Context and is ready for the user to edit before saving.';

        textarea.dispatchEvent(new Event('input'));

        textarea.removeAttribute('readonly');

        activeAiField.classList.remove('is-drafting');

        activeAiButton.disabled = false;
        activeAiButton.classList.remove('is-loading');
        activeAiButton.textContent = '✦ Help me answer this';

        if (typeof showToast === 'function') {
            showToast('aiAssistToast');
        }

        activeAiField = null;
        activeAiButton = null;
    }, 1400);
});

/* Disable AI Assist until Business Context has text */

const businessContextInput = document.querySelector('.business-context-input');

function syncAiAssistAvailability() {
    const hasContext = businessContextInput && businessContextInput.value.trim().length > 0;

    document.querySelectorAll('[data-open-ai-assist]').forEach((button) => {
        button.disabled = !hasContext;
        button.title = hasContext
            ? ''
            : 'Add a description of the business at the top of the profile to enable AI assist.';
    });
}

businessContextInput?.addEventListener('input', syncAiAssistAvailability);
syncAiAssistAvailability();

// Demo add persona button behavior
document.querySelectorAll('[data-add-persona]').forEach((button) => {
    button.addEventListener('click', () => {
        alert('Persona added in UI demo. Backend saving will be connected later.');
    });
});

// Demo delete buttons
document.querySelectorAll('.mini-btn.danger').forEach((button) => {
    button.addEventListener('click', (event) => {
        const confirmed = confirm('Are you sure? This action needs confirmation.');

        if (!confirmed) {
            event.preventDefault();
        }
    });
});

/* GLOBAL PAGE NAVIGATION HELPERS */

function goToPage(url) {
    window.location.href = url;
}

/* AUTH PAGE DEMO NAVIGATION */

document.querySelectorAll('[data-login-submit]').forEach((button) => {
    button.addEventListener('click', () => {
        goToPage('/agency/dashboard');
    });
});

document.querySelectorAll('[data-first-login-submit]').forEach((button) => {
    button.addEventListener('click', () => {
        goToPage('/agency/dashboard');
    });
});

document.querySelectorAll('[data-back-login]').forEach((button) => {
    button.addEventListener('click', () => {
        goToPage('/login');
    });
});

document.querySelectorAll('[data-save-password]').forEach((button) => {
    button.addEventListener('click', () => {
        if (typeof showToast === 'function') {
            showToast('appToast');
        }
    });
});

/* DASHBOARD NAVIGATION */

document.querySelectorAll('[data-create-campaign]').forEach((button) => {
    button.addEventListener('click', () => {
        goToPage('/agency/campaigns/create');
    });
});

document.querySelectorAll('[data-create-client]').forEach((button) => {
    button.addEventListener('click', () => {
        goToPage('/agency/clients/create');
    });
});

document.querySelectorAll('[data-view-clients]').forEach((button) => {
    button.addEventListener('click', () => {
        goToPage('/agency/clients');
    });
});

document.querySelectorAll('[data-view-client]').forEach((button) => {
    button.addEventListener('click', () => {
        goToPage('/agency/clients/show');
    });
});

document.querySelectorAll('[data-agency-dashboard]').forEach((button) => {
    button.addEventListener('click', () => {
        goToPage('/agency/dashboard');
    });
});

document.querySelectorAll('[data-admin-dashboard]').forEach((button) => {
    button.addEventListener('click', () => {
        goToPage('/admin/dashboard');
    });
});

/* ADMIN DEMO ACTIONS */

document.querySelectorAll('[data-admin-view-user]').forEach((button) => {
    button.addEventListener('click', () => {
        alert('User detail page will be added in the admin advanced step.');
    });
});

document.querySelectorAll('[data-reset-temp-password]').forEach((button) => {
    button.addEventListener('click', () => {
        const confirmed = confirm('Issue a new temporary password for this user?');

        if (confirmed && typeof showToast === 'function') {
            showToast('appToast');
        }
    });
});

document.querySelectorAll('[data-suspend-user]').forEach((button) => {
    button.addEventListener('click', () => {
        confirm('Suspend this user account?');
    });
});

document.querySelectorAll('[data-reactivate-user]').forEach((button) => {
    button.addEventListener('click', () => {
        confirm('Reactivate this user account?');
    });
});

/* MODAL FORM DEMO SUBMITS */

document.querySelectorAll('[data-create-agency-submit]').forEach((button) => {
    button.addEventListener('click', () => {
        document.getElementById('createAgencyModal')?.classList.remove('show');

        if (typeof showToast === 'function') {
            showToast('appToast');
        }
    });
});

document.querySelectorAll('[data-create-campaign-submit]').forEach((button) => {
    button.addEventListener('click', () => {
        document.getElementById('createCampaignModal')?.classList.remove('show');

        if (typeof showToast === 'function') {
            showToast('appToast');
        }
    });
});

/* CLIENT PROFILE DEMO ACTIONS */

document.querySelectorAll('[data-save-client]').forEach((button) => {
    button.addEventListener('click', () => {
        if (typeof showToast === 'function') {
            showToast('appToast');
        }

        setTimeout(() => {
            goToPage('/agency/clients/show');
        }, 700);
    });
});

document.querySelectorAll('[data-save-draft]').forEach((button) => {
    button.addEventListener('click', () => {
        if (typeof showToast === 'function') {
            showToast('appToast');
        }
    });
});

document.querySelectorAll('[data-edit-profile]').forEach((button) => {
    button.addEventListener('click', () => {
        goToPage('/agency/clients/create');
    });
});

document.querySelectorAll('[data-deactivate-client]').forEach((button) => {
    button.addEventListener('click', (event) => {
        event.preventDefault();

        const confirmed = confirm(
            'Deactivate this client profile? Existing campaigns will remain unchanged because campaigns keep snapshots of client data.'
        );

        if (!confirmed) return;

        if (typeof showToast === 'function') {
            showToast('appToast');
        }
    });
});

document.querySelectorAll('[data-reactivate-client]').forEach((button) => {
    button.addEventListener('click', () => {
        const confirmed = confirm('Reactivate this client profile?');

        if (!confirmed) return;

        if (typeof showToast === 'function') {
            showToast('appToast');
        }
    });
});

/* STEP 7 CAMPAIGN BUILDER */

/*

const campaignSections = document.querySelectorAll('[data-campaign-section]');
const campaignNextButtons = document.querySelectorAll('[data-campaign-next]');
const campaignPrevButtons = document.querySelectorAll('[data-campaign-prev]');
const campaignStepCards = document.querySelectorAll('.campaign-step-card');

let currentCampaignStep = 1;
let campaignFormTouched = false;

function showCampaignStep(step) {
    campaignSections.forEach((section) => {
        const sectionStep = Number(section.getAttribute('data-campaign-section'));
        section.classList.toggle('hidden', sectionStep !== step);
    });

    campaignStepCards.forEach((card, index) => {
        card.classList.toggle('active', index + 1 === step);
    });

    currentCampaignStep = step;

    if (step === 4) {
        updateCampaignReview();
    }

    window.scrollTo({
        top: 0,
        behavior: 'smooth'
    });
}

campaignNextButtons.forEach((button) => {
    button.addEventListener('click', () => {
        campaignFormTouched = true;

        if (currentCampaignStep === 3 && !validateCampaignDetails()) {
            return;
        }

        if (currentCampaignStep < 4) {
            showCampaignStep(currentCampaignStep + 1);
        }
    });
});

campaignPrevButtons.forEach((button) => {
    button.addEventListener('click', () => {
        if (currentCampaignStep > 1) {
            showCampaignStep(currentCampaignStep - 1);
        }
    });
});

document.querySelectorAll('#campaignBuilderForm input, #campaignBuilderForm textarea, #campaignBuilderForm select').forEach((field) => {
    field.addEventListener('input', () => {
        campaignFormTouched = true;
        updatePostLimitHint();
    });

    field.addEventListener('change', () => {
        campaignFormTouched = true;
        updatePostLimitHint();
    });
});

function getSelectedChannels() {
    return Array.from(document.querySelectorAll('input[name="channels[]"]:checked'))
        .map((input) => input.value);
}

function getCampaignDurationDays() {
    const startInput = document.getElementById('campaignStartDate');
    const endInput = document.getElementById('campaignEndDate');

    if (!startInput?.value || !endInput?.value) return 0;

    const start = new Date(startInput.value);
    const end = new Date(endInput.value);

    const difference = end - start;

    if (difference <= 0) return -1;

    return Math.ceil(difference / (1000 * 60 * 60 * 24)) + 1;
}

function getMaxPostsAllowed() {
    const durationDays = getCampaignDurationDays();
    const channelCount = getSelectedChannels().length;

    if (durationDays <= 0 || channelCount === 0) return 0;

    return durationDays * channelCount;
}

function updatePostLimitHint() {
    const hint = document.getElementById('postLimitHint');
    const postCount = document.getElementById('postCount');

    if (!hint || !postCount) return;

    const maxPosts = getMaxPostsAllowed();

    if (maxPosts === 0) {
        hint.textContent = 'Select dates and channels to calculate the maximum allowed posts.';
        return;
    }

    hint.textContent = `Maximum allowed posts for this setup: ${maxPosts}.`;
    postCount.setAttribute('max', String(maxPosts));
}

function validateCampaignDetails() {
    const name = document.getElementById('campaignName');
    const start = document.getElementById('campaignStartDate');
    const end = document.getElementById('campaignEndDate');
    const postCount = document.getElementById('postCount');
    const validationBox = document.getElementById('dateValidationBox');

    const durationDays = getCampaignDurationDays();
    const channels = getSelectedChannels();
    const maxPosts = getMaxPostsAllowed();
    const requestedPosts = Number(postCount?.value || 0);

    if (!name?.value.trim()) {
        alert('Please enter a campaign name.');
        return false;
    }

    if (!start?.value || !end?.value) {
        alert('Please select a start date and end date.');
        return false;
    }

    if (durationDays <= 0 || durationDays > 90) {
        validationBox?.classList.remove('hidden');
        return false;
    }

    validationBox?.classList.add('hidden');

    if (channels.length === 0) {
        alert('Please select at least one channel.');
        return false;
    }

    if (requestedPosts < 1) {
        alert('Please enter at least 1 post.');
        return false;
    }

    if (requestedPosts > maxPosts) {
        alert(`Too many posts. Maximum allowed for this date range and channels is ${maxPosts}.`);
        return false;
    }

    return true;
}

function updateCampaignReview() {
    const objective = document.getElementById('campaignObjective')?.value || 'Not selected';
    const start = document.getElementById('campaignStartDate')?.value || '';
    const end = document.getElementById('campaignEndDate')?.value || '';
    const posts = document.getElementById('postCount')?.value || '0';
    const channels = getSelectedChannels();

    const reviewObjective = document.getElementById('reviewObjective');
    const reviewDates = document.getElementById('reviewDates');
    const reviewChannels = document.getElementById('reviewChannels');
    const reviewPosts = document.getElementById('reviewPosts');

    if (reviewObjective) reviewObjective.textContent = objective;
    if (reviewDates) reviewDates.textContent = start && end ? `${start} → ${end}` : 'Not selected';
    if (reviewChannels) reviewChannels.textContent = channels.length ? channels.join(' + ') : 'Not selected';
    if (reviewPosts) reviewPosts.textContent = posts;
}

const generateCampaignBtn = document.getElementById('generateCampaignBtn');

let generationTimerInterval = null;

generateCampaignBtn?.addEventListener('click', () => {
    if (!validateCampaignDetails()) {
        return;
    }

    const overlay = document.getElementById('generationOverlay');
    const timerText = document.getElementById('generationTimerText');
    const timerCount = document.getElementById('generationTimerCount');
    const errorBox = document.getElementById('generationError');
    const closeErrorBtn = document.getElementById('closeGenerationError');

    let elapsedSeconds = 0;

    overlay?.classList.add('show');
    errorBox?.classList.add('hidden');
    closeErrorBtn?.classList.add('hidden');

    if (timerText) timerText.textContent = 'Preparing generation...';
    if (timerCount) timerCount.textContent = '0s';

    clearInterval(generationTimerInterval);

    generationTimerInterval = setInterval(() => {
        elapsedSeconds += 1;

        if (timerCount) {
            timerCount.textContent = `${elapsedSeconds}s`;
        }

        if (timerText) {
            if (elapsedSeconds < 20) {
                timerText.textContent = 'Preparing campaign data...';
            } else if (elapsedSeconds < 45) {
                timerText.textContent = 'Generating campaign content...';
            } else if (elapsedSeconds < 60) {
                timerText.textContent = 'Validating generated posts...';
            } else {
                timerText.textContent = 'Finalizing campaign view...';
            }
        }
    }, 1000);


    const shouldSimulateTimeout = false;

    if (shouldSimulateTimeout) {
        setTimeout(() => {
            clearInterval(generationTimerInterval);

            if (timerText) {
                timerText.textContent = 'Generation failed.';
            }

            errorBox?.classList.remove('hidden');
            closeErrorBtn?.classList.remove('hidden');

            campaignFormTouched = true;
        }, 8000);

        return;
    }

    setTimeout(() => {
        clearInterval(generationTimerInterval);

        campaignFormTouched = false;
        window.location.href = '/agency/campaigns/show';
    }, 6000);
});

document.getElementById('closeGenerationError')?.addEventListener('click', () => {
    document.getElementById('generationOverlay')?.classList.remove('show');
});

window.addEventListener('beforeunload', (event) => {
    const isCampaignBuilder = document.getElementById('campaignBuilderForm');

    if (!isCampaignBuilder || !campaignFormTouched) return;

    event.preventDefault();
    event.returnValue = '';
}); */

/* STEP 8 CAMPAIGN OUTPUT VIEWER */

let hasUnsavedPostChanges = false;

document.querySelectorAll('[data-toggle-post]').forEach((button) => {
    button.addEventListener('click', () => {
        const card = button.closest('[data-post-card]');

        if (!card) return;

        card.classList.toggle('open');
    });
});

document.querySelectorAll('.post-editable').forEach((field) => {
    field.addEventListener('input', () => {
        const card = field.closest('[data-post-card]');

        if (!card) return;

        card.classList.add('has-unsaved');
        hasUnsavedPostChanges = true;
    });
});

document.querySelectorAll('[data-save-post]').forEach((button) => {
    button.addEventListener('click', () => {
        const card = button.closest('[data-post-card]');

        if (!card) return;

        card.classList.remove('has-unsaved');
        card.classList.add('is-edited');

        hasUnsavedPostChanges = false;

        const headerRight = card.querySelector('.post-summary-right');

        if (headerRight && !card.querySelector('.post-indicator.edited')) {
            const indicator = document.createElement('span');
            indicator.className = 'post-indicator edited';
            indicator.textContent = 'Edited';
            headerRight.prepend(indicator);
        }

        if (typeof showToast === 'function') {
            showToast('appToast');
        }
    });
});

document.querySelectorAll('[data-copy-post]').forEach((button) => {
    button.addEventListener('click', async () => {
        const card = button.closest('[data-post-card]');

        if (!card) return;

        const caption = card.querySelector('[data-post-caption]')?.value || '';
        const hashtags = card.querySelector('[data-post-hashtags]')?.value || '';

        const text = `${caption}\n\n${hashtags}`;

        try {
            await navigator.clipboard.writeText(text);

            if (typeof showToast === 'function') {
                showToast('appToast');
            }
        } catch (error) {
            alert('Copy failed. Please copy manually.');
        }
    });
});


window.addEventListener('beforeunload', (event) => {
    if (!hasUnsavedPostChanges) return;

    event.preventDefault();
    event.returnValue = '';
});

/* STEP 9 ADMIN ADVANCED TOOLS */

// Admin tabs
document.querySelectorAll('[data-admin-tab]').forEach((button) => {
    button.addEventListener('click', () => {
        const targetId = button.getAttribute('data-admin-tab');

        document.querySelectorAll('[data-admin-tab]').forEach((tab) => {
            tab.classList.remove('active');
        });

        document.querySelectorAll('.admin-tab-panel').forEach((panel) => {
            panel.classList.remove('active');
        });

        button.classList.add('active');
        document.getElementById(targetId)?.classList.add('active');
    });
});

// Prompt preview
document.querySelectorAll('[data-test-prompt]').forEach((button) => {
    button.addEventListener('click', () => {
        document.getElementById('promptPreviewModal')?.classList.add('show');
    });
});

// Save prompt draft
document.querySelectorAll('[data-save-prompt]').forEach((button) => {
    button.addEventListener('click', () => {
        if (typeof showToast === 'function') {
            showToast('appToast');
        }
    });
});

// Promote prompt
document.querySelectorAll('[data-promote-prompt]').forEach((button) => {
    button.addEventListener('click', () => {
        const confirmed = confirm('Promote this prompt draft to production?');

        if (!confirmed) return;

        if (typeof showToast === 'function') {
            showToast('appToast');
        }
    });
});

// Variable insert into nearest prompt textarea
document.querySelectorAll('.variable-list button').forEach((button) => {
    button.addEventListener('click', () => {
        const textarea = document.querySelector('.admin-tab-panel.active .prompt-textarea');

        if (!textarea) return;

        const variable = button.textContent.trim();
        const start = textarea.selectionStart;
        const end = textarea.selectionEnd;

        textarea.value =
            textarea.value.substring(0, start) +
            variable +
            textarea.value.substring(end);

        textarea.focus();
        textarea.selectionStart = textarea.selectionEnd = start + variable.length;
    });
});

// Save settings
document.querySelectorAll('[data-save-settings]').forEach((button) => {
    button.addEventListener('click', () => {
        if (typeof showToast === 'function') {
            showToast('appToast');
        }
    });
});

// Open log detail
document.querySelectorAll('[data-open-log]').forEach((button) => {
    button.addEventListener('click', () => {
        document.getElementById('logDetailModal')?.classList.add('show');
    });
});

/* STEP 10 FINAL SAAS POLISH */

// Active sidebar link by current URL
const currentPath = window.location.pathname;

document.querySelectorAll('.sidebar-link[data-route]').forEach((link) => {
    const route = link.getAttribute('data-route');

    if (!route) return;

    if (currentPath === route || currentPath.startsWith(route + '/')) {
        document.querySelectorAll('.sidebar-link').forEach((item) => {
            item.classList.remove('active');
        });

        link.classList.add('active');
    }
});

// Dropdown helpers
const profileToggle = document.querySelector('[data-toggle-profile]');
const profileDropdown = document.getElementById('profileDropdown');

function closeAllDropdowns() {
    profileDropdown?.classList.remove('show');
}

profileToggle?.addEventListener('click', (event) => {
    event.stopPropagation();
    profileDropdown?.classList.toggle('show');
});

document.addEventListener('click', () => {
    closeAllDropdowns();
});

document.querySelectorAll('.dropdown-panel').forEach((dropdown) => {
    dropdown.addEventListener('click', (event) => {
        event.stopPropagation();
    });
});

// Reusable confirmation modal
let pendingConfirmAction = null;

function openConfirmModal({
    title = 'Are you sure?',
    message = 'This action needs confirmation.',
    onConfirm = null
}) {
    const modal = document.getElementById('confirmModal');
    const titleEl = document.getElementById('confirmTitle');
    const messageEl = document.getElementById('confirmMessage');

    if (titleEl) titleEl.textContent = title;
    if (messageEl) messageEl.textContent = message;

    pendingConfirmAction = onConfirm;
    modal?.classList.add('show');
}

function closeConfirmModal() {
    document.getElementById('confirmModal')?.classList.remove('show');
    pendingConfirmAction = null;
}

document.querySelectorAll('[data-cancel-confirm]').forEach((button) => {
    button.addEventListener('click', closeConfirmModal);
});

document.querySelectorAll('[data-confirm-action]').forEach((button) => {
    button.addEventListener('click', () => {
        if (typeof pendingConfirmAction === 'function') {
            pendingConfirmAction();
        }

        closeConfirmModal();
    });
});

document.querySelectorAll('[data-deactivate-client]').forEach((button) => {
    button.addEventListener('click', (event) => {
        event.preventDefault();

        openConfirmModal({
            title: 'Deactivate client profile?',
            message: 'Existing campaigns will remain unchanged because campaigns keep snapshots of client data. This profile will no longer be used for new campaigns unless reactivated.',
            onConfirm: () => {
                if (typeof showToast === 'function') {
                    showToast('appToast');
                }
            }
        });
    });
});

document.querySelectorAll('[data-suspend-user]').forEach((button) => {
    button.addEventListener('click', () => {
        openConfirmModal({
            title: 'Suspend this account?',
            message: 'The user will not be able to sign in until the account is reactivated.',
            onConfirm: () => {
                if (typeof showToast === 'function') {
                    showToast('appToast');
                }
            }
        });
    });
});

document.querySelectorAll('.danger-shortcut').forEach((button) => {
    button.addEventListener('click', () => {
        openConfirmModal({
            title: 'Suspend this account?',
            message: 'The agency will lose access to the platform until the founder reactivates the account.',
            onConfirm: () => {
                if (typeof showToast === 'function') {
                    showToast('appToast');
                }
            }
        });
    });
});

// Add fade-in animation to main page content
document.querySelectorAll(
    '.agency-page, .admin-page, .clients-page, .client-create-page, .client-show-page, .campaign-builder-page, .campaign-output-page, .admin-prompts-page, .admin-settings-page, .admin-logs-page'
).forEach((page) => {
    page.classList.add('fade-in');
});

/* FINAL PROMPT HISTORY MODAL */

document.querySelectorAll('[data-view-prompt-history]').forEach((button) => {
    button.addEventListener('click', () => {
        document.getElementById('promptHistoryModal')?.classList.add('show');
    });
});

document.querySelectorAll('[data-toggle-version]').forEach((button) => {
    button.addEventListener('click', () => {
        const drawer = button.closest('.version-drawer');

        if (!drawer) return;

        drawer.classList.toggle('open');
    });
});

/* LOADING FUNC */

window.showAiLoading = function (
    title = 'AI is working...',
    message = 'Please wait while MARKETHING generates your content.'
) {
    const overlay = document.getElementById('aiLoadingOverlay');

    if (!overlay) {
        return;
    }

    overlay.querySelector('#aiLoadingTitle').textContent = title;
    overlay.querySelector('#aiLoadingMessage').textContent = message;

    overlay.classList.add('show');
    document.body.classList.add('modal-open');
};

window.hideAiLoading = function () {
    const overlay = document.getElementById('aiLoadingOverlay');

    if (!overlay) {
        return;
    }

    overlay.classList.remove('show');
    document.body.classList.remove('modal-open');
};

const steps = document.querySelectorAll('[data-client-step]');
const nextBtn = document.querySelector('[data-next-step]');
const prevBtn = document.querySelector('[data-prev-step]');
const submitBtn = document.querySelector('[data-submit-client]');
const progressFill = document.querySelector('[data-step-progress-fill]');
const progressItems = document.querySelectorAll('[data-step-progress-item]');

let currentStep = 0;

function showStep(index) {
    steps.forEach((step, i) => {
        step.classList.toggle('active', i === index);
    });

    progressItems.forEach((item, i) => {
        item.classList.toggle('done', i < index);
        item.classList.toggle('active', i === index);
    });

    if (progressFill) {
        progressFill.style.width =
            ((index + 1) / steps.length * 100) + '%';
    }

    prevBtn.style.display = index === 0 ? 'none' : 'inline-flex';
    nextBtn.style.display = index === steps.length - 1 ? 'none' : 'inline-flex';
    submitBtn.style.display = index === steps.length - 1 ? 'inline-flex' : 'none';
}

nextBtn?.addEventListener('click', () => {
    if (currentStep < steps.length - 1) {
        currentStep++;
        showStep(currentStep);
    }
});

prevBtn?.addEventListener('click', () => {
    if (currentStep > 0) {
        currentStep--;
        showStep(currentStep);
    }
});

showStep(currentStep);