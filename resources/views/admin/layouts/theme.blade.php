<style>
    :root {
        --admin-bg: #f1f5fb;
        --admin-surface: #ffffff;
        --admin-surface-soft: #f8fbff;
        --admin-border: #d7dfeb;
        --admin-text-primary: #0f172a;
        --admin-text-secondary: #475569;
        --admin-accent: #2563eb;
        --admin-accent-hover: #1d4ed8;
        --admin-accent-contrast: #ffffff;
    }

    html.dark {
        --admin-bg: #0b1220;
        --admin-surface: #111a2e;
        --admin-surface-soft: #18243a;
        --admin-border: #2a3b57;
        --admin-text-primary: #f8fafc;
        --admin-text-secondary: #c9d6e8;
        --admin-accent: #60a5fa;
        --admin-accent-hover: #93c5fd;
        --admin-accent-contrast: #0b1220;
    }

    .admin-theme {
        background-color: var(--admin-bg);
        color: var(--admin-text-secondary);
    }

    .admin-shell-gradient {
        background: linear-gradient(to bottom, rgba(37, 99, 235, 0.24), transparent);
    }

    .admin-surface {
        background-color: var(--admin-surface) !important;
        color: var(--admin-text-primary) !important;
    }

    .admin-surface-soft {
        background-color: var(--admin-surface-soft) !important;
        color: var(--admin-text-primary) !important;
    }

    .admin-hover-surface-soft:hover {
        background-color: var(--admin-surface-soft) !important;
    }

    .admin-border {
        border-color: var(--admin-border) !important;
    }

    .admin-text-primary {
        color: var(--admin-text-primary) !important;
    }

    .admin-text-secondary {
        color: var(--admin-text-secondary) !important;
    }

    .admin-navbar {
        background-color: var(--admin-surface) !important;
        border: none !important;
        border-bottom: 1px solid var(--admin-border) !important;
        border-radius: 0 !important;
        box-shadow: none !important;
        margin: 0 !important;
        width: 100% !important;
        height: 4rem !important; /* 64px, matches sidebar header h-16 */
        min-height: 4rem !important;
        padding-left: 1rem !important;
        padding-right: 1rem !important;
        padding-top: 0 !important;
        padding-bottom: 0 !important;
        position: sticky;
        top: 0;
        z-index: 30;
    }

    @media (min-width: 640px) {
        .admin-navbar {
            padding-left: 1.5rem !important;
            padding-right: 1.5rem !important;
        }
    }

    @media (min-width: 1024px) {
        .admin-navbar {
            padding-left: 2rem !important;
            padding-right: 2rem !important;
        }
    }

    /* YouTube-style Admin Sidenav */
    .admin-sidenav {
        background-color: var(--admin-surface) !important;
        border-right: 1px solid var(--admin-border) !important;
        box-shadow: none !important;
        border-radius: 0 !important;
        margin: 0 !important;
        width: 16rem !important;
        max-width: 16rem !important;
        height: 100vh !important;
        top: 0 !important;
        bottom: 0 !important;
        left: 0 !important;
        display: flex !important;
        flex-direction: column !important;
        scrollbar-width: thin;
        scrollbar-color: transparent transparent;
        transition: transform 0.25s cubic-bezier(0.4, 0, 0.2, 1), scrollbar-color 0.2s ease;
    }

    .admin-sidenav:hover {
        scrollbar-color: rgba(148, 163, 184, 0.4) transparent;
    }

    .admin-sidenav::-webkit-scrollbar {
        width: 6px;
    }

    .admin-sidenav::-webkit-scrollbar-track {
        background: transparent;
    }

    .admin-sidenav::-webkit-scrollbar-thumb {
        background: transparent;
        border-radius: 9999px;
    }

    .admin-sidenav:hover::-webkit-scrollbar-thumb {
        background: rgba(148, 163, 184, 0.35);
    }

    .admin-sidenav:hover::-webkit-scrollbar-thumb:hover {
        background: rgba(148, 163, 184, 0.55);
    }

    @media (max-width: 1023px) {
        .admin-sidenav {
            box-shadow: 4px 0 24px rgba(0, 0, 0, 0.3) !important;
            z-index: 9999 !important;
        }
    }

    @media (min-width: 1024px) {
        .admin-sidenav {
            transform: none !important;
        }

        .admin-main-content {
            margin-left: 16rem !important;
            width: calc(100% - 16rem) !important;
            max-width: calc(100% - 16rem) !important;
        }
    }

    /* YouTube-style Nav Item */
    .sidebar-nav-item {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 0.625rem 0.875rem;
        margin: 0.15rem 0.625rem;
        border-radius: 0.625rem; /* rounded-xl (10px) like YouTube */
        font-size: 0.875rem;
        font-weight: 500;
        text-decoration: none;
        transition: background-color 0.15s ease, color 0.15s ease;
        color: var(--admin-text-secondary);
        white-space: nowrap;
    }

    .sidebar-nav-item:hover {
        background-color: rgba(0, 0, 0, 0.05);
        color: var(--admin-text-primary);
    }

    html.dark .sidebar-nav-item:hover {
        background-color: rgba(255, 255, 255, 0.09);
        color: #f8fafc;
    }

    .sidebar-nav-item.active {
        background-color: rgba(0, 0, 0, 0.08);
        color: var(--admin-text-primary);
        font-weight: 600;
    }

    html.dark .sidebar-nav-item.active {
        background-color: rgba(255, 255, 255, 0.14);
        color: #ffffff;
        font-weight: 600;
    }

    .sidebar-nav-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 1.5rem;
        height: 1.5rem;
        flex-shrink: 0;
        font-size: 1.125rem;
        transition: transform 0.15s ease;
    }

    .sidebar-nav-item:hover .sidebar-nav-icon {
        transform: scale(1.08);
    }

    .sidebar-section-divider {
        height: 1px;
        margin: 0.625rem 0.75rem;
        background-color: var(--admin-border);
        opacity: 0.5;
    }

    .sidebar-section-title {
        padding: 0.625rem 1.125rem 0.25rem 1.125rem;
        font-size: 0.6875rem;
        font-weight: 700;
        letter-spacing: 0.06em;
        text-transform: uppercase;
        color: var(--admin-text-secondary);
        opacity: 0.75;
    }


    .admin-btn-primary {
        background-color: var(--admin-accent) !important;
        border: 1px solid var(--admin-accent) !important;
        color: var(--admin-accent-contrast) !important;
    }

    .admin-btn-primary:hover {
        background-color: var(--admin-accent-hover) !important;
        border-color: var(--admin-accent-hover) !important;
    }

    .admin-layout-space {
        padding: 1rem !important;
        width: 100%;
        max-width: 100%;
        box-sizing: border-box;
    }

    @media (min-width: 640px) {
        .admin-layout-space {
            padding: 1.5rem !important;
        }
    }

    @media (min-width: 1024px) {
        .admin-layout-space {
            padding: 2rem !important;
        }
    }
</style>
