const sidebar = document.getElementById("sidebar");
const sidebarOverlay = document.getElementById("sidebarOverlay");
const menuToggle = document.getElementById("menuToggle");
const sidebarClose = document.getElementById("sidebarClose");

function openSidebar() {
    sidebar?.classList.add("open");
    sidebarOverlay?.classList.add("show");
}

function closeSidebar() {
    sidebar?.classList.remove("open");
    sidebarOverlay?.classList.remove("show");
}

menuToggle?.addEventListener("click", openSidebar);
sidebarClose?.addEventListener("click", closeSidebar);
sidebarOverlay?.addEventListener("click", closeSidebar);

document.querySelectorAll(".sidebar-link").forEach((link) => {
    link.addEventListener("click", function () {
        document.querySelectorAll(".sidebar-link").forEach((item) => {
            item.classList.remove("active");
        });

        this.classList.add("active");

        if (window.innerWidth <= 900) {
            closeSidebar();
        }
    });
});

document.querySelectorAll("[data-open-modal]").forEach((button) => {
    button.addEventListener("click", () => {
        const modalId = button.getAttribute("data-open-modal");
        document.getElementById(modalId)?.classList.add("show");
    });
});

document.querySelectorAll("[data-close-modal]").forEach((button) => {
    button.addEventListener("click", () => {
        button.closest(".modal-overlay")?.classList.remove("show");
    });
});

document.querySelectorAll(".modal-overlay").forEach((overlay) => {
    overlay.addEventListener("click", (event) => {
        if (event.target === overlay) {
            overlay.classList.remove("show");
        }
    });
});

function showToast(id = "appToast") {
    const toast = document.getElementById(id);

    if (!toast) return;

    toast.classList.add("show");

    setTimeout(() => {
        toast.classList.remove("show");
    }, 3000);
}

/* STEP 6 CLIENT PROFILE INTERACTIONS */

// Business Context live counter
document.querySelectorAll(".business-context-input").forEach((textarea) => {
    const counter = textarea
        .closest(".form-group")
        ?.querySelector(".char-counter span");

    function updateCounter() {
        if (!counter) return;

        const max = textarea.getAttribute("maxlength") || 5000;
        counter.textContent = `${textarea.value.length}/${max} characters`;
    }

    textarea.addEventListener("input", updateCounter);
    updateCounter();
});

// AI field live character counters
document.querySelectorAll(".ai-field textarea").forEach((textarea) => {
    const counter = textarea
        .closest(".ai-field")
        ?.querySelector(".ai-field-footer span");

    function updateAiCounter() {
        if (!counter) return;

        const max = textarea.getAttribute("maxlength") || 500;
        counter.textContent = `${textarea.value.length}/${max}`;
    }

    textarea.addEventListener("input", updateAiCounter);
    updateAiCounter();
});

// Demo add persona button behavior
document.querySelectorAll("[data-add-persona]").forEach((button) => {
    button.addEventListener("click", () => {
        alert(
            "Persona added in UI demo. Backend saving will be connected later.",
        );
    });
});

// Demo delete buttons
document.querySelectorAll(".mini-btn.danger").forEach((button) => {
    button.addEventListener("click", (event) => {
        const confirmed = confirm(
            "Are you sure? This action needs confirmation.",
        );

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

document.querySelectorAll("[data-login-submit]").forEach((button) => {
    button.addEventListener("click", () => {
        goToPage("/agency/dashboard");
    });
});

document.querySelectorAll("[data-first-login-submit]").forEach((button) => {
    button.addEventListener("click", () => {
        goToPage("/agency/dashboard");
    });
});

document.querySelectorAll("[data-back-login]").forEach((button) => {
    button.addEventListener("click", () => {
        goToPage("/login");
    });
});

document.querySelectorAll("[data-save-password]").forEach((button) => {
    button.addEventListener("click", () => {
        if (typeof showToast === "function") {
            showToast("appToast");
        }
    });
});

/* DASHBOARD NAVIGATION */

document.querySelectorAll("[data-create-campaign]").forEach((button) => {
    button.addEventListener("click", () => {
        goToPage("/agency/campaigns/create");
    });
});

document.querySelectorAll("[data-create-client]").forEach((button) => {
    button.addEventListener("click", () => {
        goToPage("/agency/clients/create");
    });
});

document.querySelectorAll("[data-view-clients]").forEach((button) => {
    button.addEventListener("click", () => {
        goToPage("/agency/clients");
    });
});

document.querySelectorAll("[data-view-client]").forEach((button) => {
    button.addEventListener("click", () => {
        goToPage("/agency/clients/show");
    });
});

document.querySelectorAll("[data-agency-dashboard]").forEach((button) => {
    button.addEventListener("click", () => {
        goToPage("/agency/dashboard");
    });
});

document.querySelectorAll("[data-admin-dashboard]").forEach((button) => {
    button.addEventListener("click", () => {
        goToPage("/admin/dashboard");
    });
});

/* ADMIN DEMO ACTIONS */

document.querySelectorAll("[data-admin-view-user]").forEach((button) => {
    button.addEventListener("click", () => {
        alert("User detail page will be added in the admin advanced step.");
    });
});

document.querySelectorAll("[data-reset-temp-password]").forEach((button) => {
    button.addEventListener("click", () => {
        const confirmed = confirm(
            "Issue a new temporary password for this user?",
        );

        if (confirmed && typeof showToast === "function") {
            showToast("appToast");
        }
    });
});

document.querySelectorAll("[data-suspend-user]").forEach((button) => {
    button.addEventListener("click", () => {
        confirm("Suspend this user account?");
    });
});

document.querySelectorAll("[data-reactivate-user]").forEach((button) => {
    button.addEventListener("click", () => {
        confirm("Reactivate this user account?");
    });
});

/* MODAL FORM DEMO SUBMITS */

document.querySelectorAll("[data-create-agency-submit]").forEach((button) => {
    button.addEventListener("click", () => {
        document.getElementById("createAgencyModal")?.classList.remove("show");

        if (typeof showToast === "function") {
            showToast("appToast");
        }
    });
});

document.querySelectorAll("[data-create-campaign-submit]").forEach((button) => {
    button.addEventListener("click", () => {
        document
            .getElementById("createCampaignModal")
            ?.classList.remove("show");

        if (typeof showToast === "function") {
            showToast("appToast");
        }
    });
});

/* CLIENT PROFILE DEMO ACTIONS */

document.querySelectorAll("[data-save-client]").forEach((button) => {
    button.addEventListener("click", () => {
        if (typeof showToast === "function") {
            showToast("appToast");
        }

        setTimeout(() => {
            goToPage("/agency/clients/show");
        }, 700);
    });
});

document.querySelectorAll("[data-save-draft]").forEach((button) => {
    button.addEventListener("click", () => {
        if (typeof showToast === "function") {
            showToast("appToast");
        }
    });
});

document.querySelectorAll("[data-edit-profile]").forEach((button) => {
    button.addEventListener("click", () => {
        goToPage("/agency/clients/create");
    });
});

document.querySelectorAll("[data-deactivate-client]").forEach((button) => {
    button.addEventListener("click", (event) => {
        event.preventDefault();

        const confirmed = confirm(
            "Deactivate this client profile? Existing campaigns will remain unchanged because campaigns keep snapshots of client data.",
        );

        if (!confirmed) return;

        if (typeof showToast === "function") {
            showToast("appToast");
        }
    });
});

document.querySelectorAll("[data-reactivate-client]").forEach((button) => {
    button.addEventListener("click", () => {
        const confirmed = confirm("Reactivate this client profile?");

        if (!confirmed) return;

        if (typeof showToast === "function") {
            showToast("appToast");
        }
    });
});

let hasUnsavedPostChanges = false;

document.querySelectorAll("[data-toggle-post]").forEach((button) => {
    button.addEventListener("click", () => {
        const card = button.closest("[data-post-card]");

        if (!card) return;

        card.classList.toggle("open");
    });
});

document.querySelectorAll(".post-editable").forEach((field) => {
    field.addEventListener("input", () => {
        const card = field.closest("[data-post-card]");

        if (!card) return;

        card.classList.add("has-unsaved");
        hasUnsavedPostChanges = true;
    });
});

document.querySelectorAll("[data-save-post]").forEach((button) => {
    button.addEventListener("click", () => {
        const card = button.closest("[data-post-card]");

        if (!card) return;

        card.classList.remove("has-unsaved");
        card.classList.add("is-edited");

        hasUnsavedPostChanges = false;

        const headerRight = card.querySelector(".post-summary-right");

        if (headerRight && !card.querySelector(".post-indicator.edited")) {
            const indicator = document.createElement("span");
            indicator.className = "post-indicator edited";
            indicator.textContent = "Edited";
            headerRight.prepend(indicator);
        }

        if (typeof showToast === "function") {
            showToast("appToast");
        }
    });
});

document.querySelectorAll("[data-copy-post]").forEach((button) => {
    button.addEventListener("click", async () => {
        const card = button.closest("[data-post-card]");

        if (!card) return;

        const caption = card.querySelector("[data-post-caption]")?.value || "";
        const hashtags =
            card.querySelector("[data-post-hashtags]")?.value || "";

        const text = `${caption}\n\n${hashtags}`;

        try {
            await navigator.clipboard.writeText(text);

            if (typeof showToast === "function") {
                showToast("appToast");
            }
        } catch (error) {
            alert("Copy failed. Please copy manually.");
        }
    });
});

window.addEventListener("beforeunload", (event) => {
    if (!hasUnsavedPostChanges) return;

    event.preventDefault();
    event.returnValue = "";
});

/* STEP 9 ADMIN ADVANCED TOOLS */

// Admin tabs
document.querySelectorAll("[data-admin-tab]").forEach((button) => {
    button.addEventListener("click", () => {
        const targetId = button.getAttribute("data-admin-tab");

        document.querySelectorAll("[data-admin-tab]").forEach((tab) => {
            tab.classList.remove("active");
        });

        document.querySelectorAll(".admin-tab-panel").forEach((panel) => {
            panel.classList.remove("active");
        });

        button.classList.add("active");
        document.getElementById(targetId)?.classList.add("active");
    });
});

// Prompt preview
document.querySelectorAll("[data-test-prompt]").forEach((button) => {
    button.addEventListener("click", () => {
        document.getElementById("promptPreviewModal")?.classList.add("show");
    });
});

// Save prompt draft
document.querySelectorAll("[data-save-prompt]").forEach((button) => {
    button.addEventListener("click", () => {
        if (typeof showToast === "function") {
            showToast("appToast");
        }
    });
});

// Promote prompt
document.querySelectorAll("[data-promote-prompt]").forEach((button) => {
    button.addEventListener("click", () => {
        const confirmed = confirm("Promote this prompt draft to production?");

        if (!confirmed) return;

        if (typeof showToast === "function") {
            showToast("appToast");
        }
    });
});

// Variable insert into nearest prompt textarea
document.querySelectorAll(".variable-list button").forEach((button) => {
    button.addEventListener("click", () => {
        const textarea = document.querySelector(
            ".admin-tab-panel.active .prompt-textarea",
        );

        if (!textarea) return;

        const variable = button.textContent.trim();
        const start = textarea.selectionStart;
        const end = textarea.selectionEnd;

        textarea.value =
            textarea.value.substring(0, start) +
            variable +
            textarea.value.substring(end);

        textarea.focus();
        textarea.selectionStart = textarea.selectionEnd =
            start + variable.length;
    });
});

// Save settings
document.querySelectorAll("[data-save-settings]").forEach((button) => {
    button.addEventListener("click", () => {
        if (typeof showToast === "function") {
            showToast("appToast");
        }
    });
});

// Open log detail
document.querySelectorAll("[data-open-log]").forEach((button) => {
    button.addEventListener("click", () => {
        document.getElementById("logDetailModal")?.classList.add("show");
    });
});

/* STEP 10 FINAL SAAS POLISH */

// Active sidebar link by current URL
const currentPath = window.location.pathname;

document.querySelectorAll(".sidebar-link[data-route]").forEach((link) => {
    const route = link.dataset.route;

    if (!route) return;

    const isExact = currentPath === route;
    const isChild = currentPath.startsWith(route + "/") && !link.dataset.exact;

    if (isExact || isChild) {
        document
            .querySelectorAll(".sidebar-link")
            .forEach((item) => item.classList.remove("active"));

        link.classList.add("active");
    }
});

// Dropdown helpers
const profileToggle = document.querySelector("[data-toggle-profile]");
const profileDropdown = document.getElementById("profileDropdown");

function closeAllDropdowns() {
    profileDropdown?.classList.remove("show");
}

profileToggle?.addEventListener("click", (event) => {
    event.stopPropagation();
    profileDropdown?.classList.toggle("show");
});

document.addEventListener("click", () => {
    closeAllDropdowns();
});

document.querySelectorAll(".dropdown-panel").forEach((dropdown) => {
    dropdown.addEventListener("click", (event) => {
        event.stopPropagation();
    });
});

// Reusable confirmation modal
let pendingConfirmAction = null;

function openConfirmModal({
    title = "Are you sure?",
    message = "This action needs confirmation.",
    onConfirm = null,
}) {
    const modal = document.getElementById("confirmModal");
    const titleEl = document.getElementById("confirmTitle");
    const messageEl = document.getElementById("confirmMessage");

    if (titleEl) titleEl.textContent = title;
    if (messageEl) messageEl.textContent = message;

    pendingConfirmAction = onConfirm;
    modal?.classList.add("show");
}

function closeConfirmModal() {
    document.getElementById("confirmModal")?.classList.remove("show");
    pendingConfirmAction = null;
}

document.querySelectorAll("[data-cancel-confirm]").forEach((button) => {
    button.addEventListener("click", closeConfirmModal);
});

document.querySelectorAll("[data-confirm-action]").forEach((button) => {
    button.addEventListener("click", () => {
        if (typeof pendingConfirmAction === "function") {
            pendingConfirmAction();
        }

        closeConfirmModal();
    });
});

document.querySelectorAll("[data-deactivate-client]").forEach((button) => {
    button.addEventListener("click", (event) => {
        event.preventDefault();

        openConfirmModal({
            title: "Deactivate client profile?",
            message:
                "Existing campaigns will remain unchanged because campaigns keep snapshots of client data. This profile will no longer be used for new campaigns unless reactivated.",
            onConfirm: () => {
                if (typeof showToast === "function") {
                    showToast("appToast");
                }
            },
        });
    });
});

document.querySelectorAll("[data-suspend-user]").forEach((button) => {
    button.addEventListener("click", () => {
        openConfirmModal({
            title: "Suspend this account?",
            message:
                "The user will not be able to sign in until the account is reactivated.",
            onConfirm: () => {
                if (typeof showToast === "function") {
                    showToast("appToast");
                }
            },
        });
    });
});

document.querySelectorAll(".danger-shortcut").forEach((button) => {
    button.addEventListener("click", () => {
        openConfirmModal({
            title: "Suspend this account?",
            message:
                "The agency will lose access to the platform until the founder reactivates the account.",
            onConfirm: () => {
                if (typeof showToast === "function") {
                    showToast("appToast");
                }
            },
        });
    });
});

// Add fade-in animation to main page content
document
    .querySelectorAll(
        ".agency-page, .admin-page, .clients-page, .client-create-page, .client-show-page, .campaign-builder-page, .campaign-output-page, .admin-prompts-page, .admin-settings-page, .admin-logs-page",
    )
    .forEach((page) => {
        page.classList.add("fade-in");
    });

/* FINAL PROMPT HISTORY MODAL */

document.querySelectorAll("[data-view-prompt-history]").forEach((button) => {
    button.addEventListener("click", () => {
        document.getElementById("promptHistoryModal")?.classList.add("show");
    });
});

document.querySelectorAll("[data-toggle-version]").forEach((button) => {
    button.addEventListener("click", () => {
        const drawer = button.closest(".version-drawer");

        if (!drawer) return;

        drawer.classList.toggle("open");
    });
});

/* LOADING FUNC */

window.showAiLoading = function (
    title = "AI is working...",
    message = "Please wait while MARKETHING generates your content.",
) {
    const overlay = document.getElementById("aiLoadingOverlay");

    if (!overlay) {
        return;
    }

    overlay.querySelector("#aiLoadingTitle").textContent = title;
    overlay.querySelector("#aiLoadingMessage").textContent = message;

    overlay.classList.add("show");
    document.body.classList.add("modal-open");
};

window.hideAiLoading = function () {
    const overlay = document.getElementById("aiLoadingOverlay");

    if (!overlay) {
        return;
    }

    overlay.classList.remove("show");
    document.body.classList.remove("modal-open");
};

window.pollCampaignStatus = function (campaignId, generateButton) {
    const interval = setInterval(async () => {
        try {
            const response = await fetch(
                `/agency/campaigns/${campaignId}/status`,
            );

            const data = await response.json();

            if (data.status === "generated") {
                clearInterval(interval);

                window.location.href = `/agency/campaigns/${campaignId}`;
            }

            if (data.status === "failed") {
                clearInterval(interval);

                alert("Campaign generation failed.");

                hideAiLoading();
                generateButton.disabled = false;
                generateButton.textContent = "Generate Campaign";
            }
        } catch (error) {
            console.error(error);
        }
    }, 5000);
};

window.submitCampaignAsync = async function (form, generateButton) {
    const formData = new FormData(form);

    try {
        const response = await fetch(form.action, {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": document.querySelector('input[name="_token"]')
                    .value,

                Accept: "application/json",
            },
            body: formData,
        });

        const data = await response.json();

        if (!response.ok) {
            if (response.status === 422) {
                const message = data.message || "Please check your input.";

                alert(message);

                hideAiLoading();
                generateButton.disabled = false;
                generateButton.textContent = "Generate Campaign";

                return;
            }

            throw new Error(data.message || "Campaign failed");
        }

        pollCampaignStatus(data.campaign_id, generateButton);
    } catch (error) {
        hideAiLoading();
        alert(error.message);

        generateButton.disabled = false;
        generateButton.textContent = "Generate Campaign";
    }
};
