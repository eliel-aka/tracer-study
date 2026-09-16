<style>
    :root {
        --ui-bg: #eef3fb;
        --ui-surface: #ffffff;
        --ui-surface-soft: #f4f8ff;
        --ui-border: #cfd8e6;
        --ui-text-primary: #0f172a;
        --ui-text-secondary: #334155;
        --ui-accent: #1d4ed8;
        --ui-accent-hover: #1e40af;
        --ui-accent-contrast: #ffffff;
        --ui-success-bg: #ecfdf3;
        --ui-success-text: #166534;
        --ui-danger-bg: #fef2f2;
        --ui-danger-text: #b91c1c;
        --ui-focus: rgba(29, 78, 216, 0.28);
        --ui-disabled-bg: #cbd5e1;
        --ui-disabled-border: #94a3b8;
        --ui-disabled-text: #334155;
    }

    html.dark {
        --ui-bg: #0b1220;
        --ui-surface: #111a2e;
        --ui-surface-soft: #18243a;
        --ui-border: #2a3b57;
        --ui-text-primary: #f8fafc;
        --ui-text-secondary: #d1dae8;
        --ui-accent: #60a5fa;
        --ui-accent-hover: #93c5fd;
        --ui-accent-contrast: #0b1220;
        --ui-success-bg: #0f2f22;
        --ui-success-text: #86efac;
        --ui-danger-bg: #3a1016;
        --ui-danger-text: #fca5a5;
        --ui-focus: rgba(147, 197, 253, 0.38);
        --ui-disabled-bg: #334155;
        --ui-disabled-border: #475569;
        --ui-disabled-text: #cbd5e1;
    }

    .user-theme {
        background-color: var(--ui-bg);
        color: var(--ui-text-primary);
    }

    .theme-text-primary {
        color: var(--ui-text-primary) !important;
    }

    .theme-text-secondary {
        color: var(--ui-text-secondary) !important;
    }

    .theme-surface {
        background-color: var(--ui-surface) !important;
        border-color: var(--ui-border) !important;
        color: var(--ui-text-primary) !important;
    }

    .theme-surface-soft {
        background-color: var(--ui-surface-soft) !important;
        border-color: var(--ui-border) !important;
        color: var(--ui-text-primary) !important;
    }

    .theme-border {
        border-color: var(--ui-border) !important;
    }

    .theme-table {
        border-color: var(--ui-border) !important;
    }

    .theme-table th,
    .theme-table td {
        border-color: var(--ui-border) !important;
    }

    .theme-table thead tr {
        background-color: var(--ui-surface-soft) !important;
        color: var(--ui-text-primary) !important;
    }

    .theme-table tbody,
    .theme-table tbody td,
    .theme-table tbody span,
    .theme-table tbody p {
        color: var(--ui-text-primary);
    }

    .theme-input {
        background-color: var(--ui-surface) !important;
        color: var(--ui-text-primary) !important;
        border-color: var(--ui-border) !important;
    }

    .theme-input::placeholder {
        color: var(--ui-text-secondary) !important;
        opacity: 0.85;
    }

    .theme-input:focus {
        border-color: var(--ui-accent) !important;
        box-shadow: 0 0 0 3px var(--ui-focus) !important;
        outline: none !important;
    }

    .theme-btn-primary {
        background-color: var(--ui-accent) !important;
        border: 1px solid var(--ui-accent) !important;
        color: var(--ui-accent-contrast) !important;
    }

    .theme-btn-primary:hover {
        background-color: var(--ui-accent-hover) !important;
        border-color: var(--ui-accent-hover) !important;
    }

    .theme-btn-secondary {
        background-color: var(--ui-surface-soft) !important;
        border: 1px solid var(--ui-border) !important;
        color: var(--ui-text-primary) !important;
    }

    .theme-btn-secondary:hover {
        filter: brightness(0.98);
    }

    .theme-btn-disabled {
        background-color: var(--ui-disabled-bg) !important;
        border: 1px solid var(--ui-disabled-border) !important;
        color: var(--ui-disabled-text) !important;
        cursor: not-allowed !important;
        opacity: 1 !important;
    }

    .theme-alert-success {
        background-color: var(--ui-success-bg) !important;
        color: var(--ui-success-text) !important;
    }

    .theme-alert-danger {
        background-color: var(--ui-danger-bg) !important;
        color: var(--ui-danger-text) !important;
    }

    .theme-nav-icon {
        color: var(--ui-text-primary) !important;
    }

    .theme-footer {
        background-color: var(--ui-surface) !important;
        color: var(--ui-text-primary) !important;
        border-top: 1px solid var(--ui-border);
    }

    .theme-footer-muted {
        color: var(--ui-text-secondary) !important;
    }

    .theme-footer-link {
        color: var(--ui-text-secondary) !important;
    }

    .theme-footer-link:hover {
        color: var(--ui-accent) !important;
    }

    .theme-question,
    .theme-question label,
    .theme-question p,
    .theme-question span,
    .theme-question th,
    .theme-question td {
        color: var(--ui-text-primary) !important;
    }

    .theme-question .required-asterisk,
    .theme-question .text-red-500 {
        color: #ef4444 !important;
    }

    html.dark .theme-question .required-asterisk,
    html.dark .theme-question .text-red-500 {
        color: #f87171 !important;
    }

    .theme-question {
        background-color: var(--ui-surface-soft) !important;
        border-color: var(--ui-border) !important;
    }

    .theme-question table,
    .theme-question thead tr,
    .theme-question tbody tr {
        background-color: transparent !important;
        border-color: var(--ui-border) !important;
    }

    .theme-muted-date {
        color: var(--ui-text-secondary) !important;
    }

    /* Spacing utilities using an 8px-based rhythm */
    .theme-section-space {
        padding-top: 20px;
        padding-bottom: 32px;
    }

    .theme-card-space {
        padding: 16px;
    }

    .theme-stack-sm > * + * {
        margin-top: 6px;
    }

    .theme-stack-md > * + * {
        margin-top: 12px;
    }

    .theme-stack-lg > * + * {
        margin-top: 20px;
    }

    @media (min-width: 640px) {
        .theme-section-space {
            padding-top: 24px;
            padding-bottom: 36px;
        }

        .theme-card-space {
            padding: 20px;
        }
    }

    @media (min-width: 1024px) {
        .theme-section-space {
            padding-top: 28px;
            padding-bottom: 40px;
        }

        .theme-card-space {
            padding: 24px;
        }
    }

    /* User Profile & Survey Dashboard Custom Classes */
    .user-banner-bar {
        background-color: var(--ui-surface) !important;
        border-bottom: 1px solid var(--ui-border) !important;
        padding: 1.25rem 0;
        position: relative;
        z-index: 10;
    }

    .user-banner-container {
        display: flex;
        align-items: center;
        gap: 1.125rem;
        width: 100%;
    }

    .user-banner-avatar {
        width: 52px;
        height: 52px;
        min-width: 52px;
        border-radius: 50%;
        background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
        color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.375rem;
        font-weight: 700;
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25);
        flex-shrink: 0;
    }

    .user-banner-content {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
    }

    .user-banner-title-row {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 0.625rem;
    }

    .user-banner-name {
        font-size: 1.375rem;
        font-weight: 700;
        color: var(--ui-text-primary);
        line-height: 1.2;
        margin: 0;
    }

    @media (min-width: 640px) {
        .user-banner-name {
            font-size: 1.625rem;
        }
    }

    .user-banner-subtitle {
        font-size: 0.8125rem;
        color: var(--ui-text-secondary);
        margin: 0;
        line-height: 1.4;
    }

    .user-profile-card {
        background-color: var(--ui-surface) !important;
        border: 1px solid var(--ui-border) !important;
        border-radius: 0.875rem !important;
        padding: 1.25rem 1.5rem !important;
        margin-bottom: 1.5rem !important;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05) !important;
    }

    .user-profile-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding-bottom: 0.875rem;
        margin-bottom: 1rem;
        border-bottom: 1px solid var(--ui-border);
        gap: 0.75rem;
        flex-wrap: wrap;
    }

    .user-profile-title-group {
        display: flex;
        align-items: center;
        gap: 0.625rem;
    }

    .user-profile-icon {
        width: 2rem;
        height: 2rem;
        border-radius: 0.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .user-role-badge {
        display: inline-flex;
        align-items: center;
        padding: 0.25rem 0.625rem;
        font-size: 0.75rem;
        font-weight: 600;
        border-radius: 9999px;
        background-color: rgba(37, 99, 235, 0.12);
        color: #2563eb;
        border: 1px solid rgba(37, 99, 235, 0.25);
        line-height: 1;
    }

    html.dark .user-role-badge {
        background-color: rgba(96, 165, 250, 0.15);
        color: #93c5fd;
        border-color: rgba(96, 165, 250, 0.3);
    }

    /* 4-column responsive info grid */
    .user-info-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 0.75rem;
    }

    @media (max-width: 1023px) {
        .user-info-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 639px) {
        .user-info-grid {
            grid-template-columns: 1fr;
        }
    }

    .user-info-tile {
        background-color: var(--ui-surface-soft) !important;
        border: 1px solid var(--ui-border) !important;
        border-radius: 0.5rem;
        padding: 0.625rem 0.75rem;
        display: flex;
        flex-direction: column;
        justify-content: center;
        min-height: 3.75rem;
        transition: border-color 0.15s ease;
    }

    .user-info-tile:hover {
        border-color: rgba(37, 99, 235, 0.4) !important;
    }

    .user-info-label {
        font-size: 0.6875rem;
        font-weight: 700;
        letter-spacing: 0.05em;
        text-transform: uppercase;
        color: var(--ui-text-secondary);
        margin-bottom: 0.25rem;
        line-height: 1;
    }

    .user-info-value {
        font-size: 0.875rem;
        font-weight: 600;
        color: var(--ui-text-primary);
        word-break: break-word;
        line-height: 1.3;
    }

    /* Survey Table Styling */
    .user-survey-table {
        width: 100%;
        border-collapse: collapse;
        border: 1px solid var(--ui-border);
        border-radius: 0.5rem;
        overflow: hidden;
    }

    .user-survey-table th {
        background-color: var(--ui-surface-soft) !important;
        color: var(--ui-text-primary);
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        padding: 0.75rem 1rem;
        border-bottom: 1px solid var(--ui-border);
        border-right: 1px solid var(--ui-border);
    }

    .user-survey-table th:last-child {
        border-right: none;
    }

    .user-survey-table td {
        padding: 0.75rem 1rem;
        border-bottom: 1px solid var(--ui-border);
        border-right: 1px solid var(--ui-border);
        vertical-align: middle;
        font-size: 0.875rem;
    }

    .user-survey-table td:last-child {
        border-right: none;
    }

    .user-survey-table tbody tr:last-child td {
        border-bottom: none;
    }

    .user-survey-table tbody tr:hover {
        background-color: var(--ui-surface-soft);
    }

    /* Status Badges */
    .badge-status-active {
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
        padding: 0.25rem 0.625rem;
        font-size: 0.6875rem;
        font-weight: 700;
        border-radius: 9999px;
        background-color: #10b981;
        color: #ffffff;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        line-height: 1;
    }

    .badge-status-done {
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
        padding: 0.25rem 0.625rem;
        font-size: 0.6875rem;
        font-weight: 700;
        border-radius: 9999px;
        background-color: #64748b;
        color: #ffffff;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        line-height: 1;
    }

    /* Action Buttons */
    .btn-survey-action {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.375rem;
        padding: 0.4rem 0.875rem;
        font-size: 0.75rem;
        font-weight: 700;
        border-radius: 0.375rem;
        text-decoration: none;
        transition: all 0.15s ease;
        white-space: nowrap;
        border: 1px solid transparent;
        line-height: 1.2;
    }

    .btn-survey-fill {
        background-color: #059669;
        color: #ffffff !important;
    }

    .btn-survey-fill:hover {
        background-color: #047857;
    }

    .btn-survey-edit {
        background-color: #4f46e5;
        color: #ffffff !important;
    }

    .btn-survey-edit:hover {
        background-color: #4338ca;
    }

    .btn-survey-disabled {
        background-color: var(--ui-surface-soft);
        color: var(--ui-text-secondary) !important;
        border-color: var(--ui-border);
    }

    .btn-survey-expired {
        background-color: rgba(239, 68, 68, 0.12);
        color: #ef4444 !important;
        border-color: rgba(239, 68, 68, 0.25);
    }

    .user-info-actions {
        grid-column: 1 / -1;
        margin-top: 0.75rem;
        display: flex;
        gap: 0.625rem;
        flex-wrap: wrap;
    }

    .user-info-actions.hidden,
    #actionButtons.hidden {
        display: none !important;
    }

    .view-mode.hidden,
    .edit-mode.hidden {
        display: none !important;
    }

    .user-info-tile .edit-mode input,
    .user-info-tile .edit-mode .select2-container {
        width: 100% !important;
    }

    /* Responsive Survey Display (Desktop: Table only, Mobile: Cards only) */
    .user-survey-desktop-view {
        display: block !important;
        overflow-x: auto;
    }

    .user-survey-mobile-view {
        display: none !important;
    }

    @media (max-width: 1023px) {
        .user-survey-desktop-view {
            display: none !important;
        }

        .user-survey-mobile-view {
            display: flex !important;
            flex-direction: column;
            gap: 0.75rem;
        }
    }
</style>
