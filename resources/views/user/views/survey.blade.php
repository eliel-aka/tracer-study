<!DOCTYPE html>
<html lang="en" class="scroll-smooth">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>
        Tracer Study
    </title>
    <link rel="shortcut icon" href="{{ asset('assets/images/logo.png') }}" type="image/x-icon" />
    <link rel="stylesheet" href="{{ asset('assets/css/swiper-bundle.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/animate.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/tailwind.css') }}" />
    @include('user.layouts.theme')

    <!-- ==== WOW JS ==== -->
    <script src="{{ asset('assets/js/wow.min.js') }}"></script>
    <script>
        new WOW().init();
    </script>
    <!-- <script src="https://cdn.tailwindcss.com"></script> -->


    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    
    <style>
        .question-box {
            background-color: var(--ui-surface-soft) !important;
            border-color: var(--ui-border) !important;
            color: var(--ui-text-primary) !important;
            min-width: 0 !important;
            max-width: 100% !important;
            overflow: hidden !important;
        }

        /* Multiple Choice Grid Responsive Container */
        .grid-table-container {
            width: 100%;
            max-width: 100%;
            min-width: 0;
            border-radius: 0.75rem;
            border: 1.5px solid #cbd5e1 !important;
            overflow: hidden;
            background-color: #ffffff !important;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }

        .dark .grid-table-container {
            border-color: #3e5270 !important;
            background-color: #111a2e !important;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.3);
        }

        .grid-table-wrapper {
            width: 100%;
            max-width: 100%;
            min-width: 0;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .grid-table-wrapper::-webkit-scrollbar {
            height: 6px;
        }

        .grid-table-wrapper::-webkit-scrollbar-track {
            background: rgba(0, 0, 0, 0.05);
            border-radius: 9999px;
        }

        .dark .grid-table-wrapper::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.05);
        }

        .grid-table-wrapper::-webkit-scrollbar-thumb {
            background: rgba(59, 130, 246, 0.45);
            border-radius: 9999px;
        }

        .grid-table-wrapper::-webkit-scrollbar-thumb:hover {
            background: rgba(59, 130, 246, 0.7);
        }

        /* Multiple Choice Grid - Clear, sharp borders for columns, rows, and cells */
        .grid-table {
            width: 100%;
            min-width: 580px;
            border-collapse: collapse !important;
            border-spacing: 0 !important;
            border: none !important;
        }

        /* Explicit, high-contrast borders for every single cell */
        .grid-table th,
        .grid-table td {
            border: 1px solid #cbd5e1 !important;
            box-sizing: border-box;
        }

        .dark .grid-table th,
        .dark .grid-table td {
            border: 1px solid #3e5270 !important;
        }

        /* Distinct Header Row Styling */
        .grid-table thead th {
            background-color: #f1f5f9 !important;
            border-bottom: 2.5px solid #94a3b8 !important;
            color: #0f172a !important;
            vertical-align: middle;
        }

        .dark .grid-table thead th {
            background-color: #18243a !important;
            border-bottom: 2.5px solid #475569 !important;
            color: #f8fafc !important;
        }

        /* Clear demarcation for the first column (Pernyataan) */
        .grid-table th:first-child,
        .grid-table td:first-child {
            border-right: 2px solid #94a3b8 !important;
        }

        .dark .grid-table th:first-child,
        .dark .grid-table td:first-child {
            border-right: 2px solid #475569 !important;
        }

        /* Row backgrounds & zebra striping */
        .grid-table tbody tr.row-even td {
            background-color: #ffffff !important;
        }

        .dark .grid-table tbody tr.row-even td {
            background-color: #111a2e !important;
        }

        .grid-table tbody tr.row-odd td {
            background-color: #f8fafc !important;
        }

        .dark .grid-table tbody tr.row-odd td {
            background-color: #162238 !important;
        }

        /* Row hover effect for comfortable horizontal tracking */
        .grid-table tbody tr:hover td {
            background-color: #eff6ff !important;
        }

        .dark .grid-table tbody tr:hover td {
            background-color: #1e304b !important;
        }

        /* Selected cell highlight */
        .grid-table tbody td.is-selected {
            background-color: #dbeafe !important;
        }

        .dark .grid-table tbody td.is-selected {
            background-color: #1d3557 !important;
        }

        .grid-score-badge {
            color: #ffffff !important;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.15);
        }

        .grid-col-label {
            color: #334155 !important;
        }

        .dark .grid-col-label {
            color: #cbd5e1 !important;
        }

        .question-box label,
        .question-box p,
        .question-box span,
        .question-box td,
        .question-box th {
            color: var(--ui-text-primary) !important;
        }

        /* Required Asterisk Highlight - ensure always bright red in both modes */
        .question-box span.required-asterisk,
        .question-box .text-red-500,
        .theme-question span.required-asterisk,
        .theme-question .text-red-500,
        .required-asterisk {
            color: #ef4444 !important;
            font-weight: 700 !important;
        }

        .dark .question-box span.required-asterisk,
        .dark .question-box .text-red-500,
        .dark .theme-question span.required-asterisk,
        .dark .theme-question .text-red-500,
        .dark .required-asterisk {
            color: #f87171 !important;
        }

        .submit-btn {
            background-color: var(--ui-accent) !important;
            color: var(--ui-accent-contrast) !important;
            border: 2px solid var(--ui-accent) !important;
        }

        .submit-btn:hover {
            background-color: var(--ui-accent-hover) !important;
            border-color: var(--ui-accent-hover) !important;
        }

        .submit-btn:focus {
            box-shadow: 0 0 0 3px var(--ui-focus) !important;
            outline: none !important;
        }

        /* Save Draft Button Styles */
        .save-draft-btn {
            background: linear-gradient(135deg, #059669 0%, #0d9488 100%) !important;
            color: #ffffff !important;
            border: 2px solid #059669 !important;
            position: relative;
            overflow: hidden;
        }

        .save-draft-btn:hover:not(:disabled) {
            background: linear-gradient(135deg, #047857 0%, #0f766e 100%) !important;
            border-color: #047857 !important;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(5, 150, 105, 0.35);
        }

        .save-draft-btn:active:not(:disabled) {
            transform: translateY(0);
        }

        .save-draft-btn:focus {
            box-shadow: 0 0 0 3px rgba(5, 150, 105, 0.3) !important;
            outline: none !important;
        }

        .save-draft-btn:disabled {
            opacity: 0.7;
            cursor: not-allowed;
        }

        .save-draft-btn .btn-spinner {
            width: 18px;
            height: 18px;
            border: 2px solid rgba(255,255,255,0.3);
            border-top-color: #fff;
            border-radius: 50%;
            animation: autosave-spin 0.7s linear infinite;
        }

        .save-draft-btn.saved-state {
            background: linear-gradient(135deg, #10b981 0%, #34d399 100%) !important;
            border-color: #10b981 !important;
        }

        /* Dark mode for save draft button */
        .dark .save-draft-btn {
            background: linear-gradient(135deg, #10b981 0%, #14b8a6 100%) !important;
            border-color: #10b981 !important;
        }
        .dark .save-draft-btn:hover:not(:disabled) {
            background: linear-gradient(135deg, #059669 0%, #0d9488 100%) !important;
            border-color: #059669 !important;
        }

        /* Autosave Indicator Styles */
        .autosave-indicator {
            position: fixed;
            bottom: 24px;
            left: 24px;
            z-index: 1000;
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 10px 18px;
            border-radius: 12px;
            font-size: 13px;
            font-weight: 500;
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.12);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            transform: translateY(0);
            opacity: 1;
        }

        .autosave-indicator.hidden-indicator {
            transform: translateY(20px);
            opacity: 0;
            pointer-events: none;
        }

        .autosave-indicator.saving {
            background: rgba(59, 130, 246, 0.12);
            border: 1px solid rgba(59, 130, 246, 0.25);
            color: #2563eb;
        }

        .autosave-indicator.saved {
            background: rgba(16, 185, 129, 0.12);
            border: 1px solid rgba(16, 185, 129, 0.25);
            color: #059669;
        }

        .autosave-indicator.error {
            background: rgba(239, 68, 68, 0.12);
            border: 1px solid rgba(239, 68, 68, 0.25);
            color: #dc2626;
        }

        .autosave-indicator.idle {
            background: rgba(107, 114, 128, 0.10);
            border: 1px solid rgba(107, 114, 128, 0.18);
            color: #6b7280;
        }

        .autosave-spinner {
            width: 16px;
            height: 16px;
            border: 2px solid currentColor;
            border-top-color: transparent;
            border-radius: 50%;
            animation: autosave-spin 0.8s linear infinite;
        }

        @keyframes autosave-spin {
            to { transform: rotate(360deg); }
        }

        @keyframes autosave-pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.6; }
        }

        .autosave-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: currentColor;
            animation: autosave-pulse 2s ease-in-out infinite;
        }

        /* Dark mode overrides */
        .dark .autosave-indicator.saving {
            background: rgba(59, 130, 246, 0.2);
            border-color: rgba(59, 130, 246, 0.35);
            color: #60a5fa;
        }
        .dark .autosave-indicator.saved {
            background: rgba(16, 185, 129, 0.2);
            border-color: rgba(16, 185, 129, 0.35);
            color: #34d399;
        }
        .dark .autosave-indicator.error {
            background: rgba(239, 68, 68, 0.2);
            border-color: rgba(239, 68, 68, 0.35);
            color: #f87171;
        }
        .dark .autosave-indicator.idle {
            background: rgba(156, 163, 175, 0.15);
            border-color: rgba(156, 163, 175, 0.25);
            color: #9ca3af;
        }

        /* Survey Main Section Spacing */
        .survey-main-section {
            padding-top: 2rem !important;
            padding-bottom: 2.5rem !important;
        }

        @media (min-width: 640px) {
            .survey-main-section {
                padding-top: 2.75rem !important;
                padding-bottom: 3.5rem !important;
            }
        }

        /* Survey Header Section */
        .survey-header-container {
            background-color: var(--ui-surface) !important;
            border: 1px solid var(--ui-border) !important;
            border-radius: 0.75rem;
            margin-bottom: 1.75rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }

        .survey-header-heading {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--ui-text-primary);
            line-height: 1.35;
            margin: 0 0 0.75rem 0;
        }

        @media (min-width: 640px) {
            .survey-header-heading {
                font-size: 1.375rem;
            }
        }

        .survey-header-badges {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 1.25rem;
        }

        .survey-pill {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            padding: 0.25rem 0.625rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
            line-height: 1;
            background-color: var(--ui-surface-soft);
            color: var(--ui-text-secondary);
            border: 1px solid var(--ui-border);
            white-space: nowrap;
        }

        .survey-pill svg {
            width: 13px;
            height: 13px;
            min-width: 13px;
            flex-shrink: 0;
        }

        .survey-pill-success {
            color: #10b981 !important;
            background-color: rgba(16, 185, 129, 0.1) !important;
            border-color: rgba(16, 185, 129, 0.3) !important;
        }

        .survey-header-description {
            font-size: 0.8125rem;
            line-height: 1.65;
            color: var(--ui-text-secondary);
            margin: 0;
            padding-top: 1.25rem;
            border-top: 1px solid var(--ui-border);
            text-align: left;
            word-break: break-word;
        }

        /* Gaji Input Field Styling */
        .gaji-input-wrapper {
            position: relative;
            width: 100%;
            display: flex;
            align-items: center;
        }

        .gaji-prefix {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            font-size: 0.9375rem;
            font-weight: 600;
            color: #64748b;
            pointer-events: none;
            user-select: none;
            z-index: 10;
        }

        .dark .gaji-prefix {
            color: #94a3b8;
        }

        .gaji-input-field {
            width: 100% !important;
            padding-left: 3.25rem !important; /* 52px ensures clear, neat spacing between Rp and salary digits */
            padding-right: 1rem !important;
            padding-top: 0.75rem !important;
            padding-bottom: 0.75rem !important;
            border-radius: 0.375rem;
            font-size: 0.95rem;
        }

        /* Survey Option Labels (Radio & Checkbox) */
        .survey-option-label {
            display: flex;
            align-items: center;
            padding: 0.5rem 0.75rem;
            border-radius: 0.375rem;
            cursor: pointer;
            transition: background-color 0.15s ease;
        }

        .survey-option-bullet {
            width: 1.125rem !important;
            height: 1.125rem !important;
            margin: 0 !important;
            margin-right: 0.75rem !important; /* 12px neat, clean space between bullet and label */
            flex-shrink: 0;
            cursor: pointer;
        }

        .survey-option-text {
            font-size: 0.9375rem;
            line-height: 1.45;
            cursor: pointer;
            user-select: none;
        }

        /* Multiple Choice Grid Responsive Views */
        .grid-desktop-view {
            display: block !important;
            width: 100%;
        }

        .grid-mobile-view {
            display: none !important;
        }

        @media (max-width: 767px) {
            .grid-desktop-view {
                display: none !important;
            }

            .grid-mobile-view {
                display: flex !important;
                flex-direction: column;
                gap: 1rem;
                width: 100%;
            }
        }

        /* Mobile Grid Statement Card */
        .grid-mobile-card {
            background-color: var(--ui-surface) !important;
            border: 1px solid var(--ui-border) !important;
            border-radius: 0.75rem;
            padding: 1rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            min-width: 0;
            width: 100%;
            box-sizing: border-box;
        }

        .dark .grid-mobile-card {
            background-color: #111a2e !important;
            border-color: #2a3b57 !important;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.3);
        }

        .grid-mobile-statement {
            display: flex;
            align-items: flex-start;
            gap: 0.5rem;
            margin-bottom: 0.75rem;
            padding-bottom: 0.75rem;
            border-bottom: 1px solid var(--ui-border);
        }

        .grid-mobile-stmt-number {
            font-size: 0.9375rem;
            font-weight: 700;
            color: #2563eb;
            line-height: 1.45;
            flex-shrink: 0;
        }

        .dark .grid-mobile-stmt-number {
            color: #60a5fa;
        }

        .grid-mobile-stmt-text {
            font-size: 0.9375rem;
            font-weight: 600;
            line-height: 1.45;
            color: var(--ui-text-primary);
            word-break: break-word;
        }

        .grid-mobile-options {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .grid-mobile-option-label {
            display: flex;
            align-items: center;
            padding: 0.625rem 0.75rem;
            border-radius: 0.5rem;
            border: 1px solid var(--ui-border);
            background-color: var(--ui-surface-soft);
            cursor: pointer;
            transition: all 0.15s ease;
            user-select: none;
        }

        .grid-mobile-option-label:hover {
            border-color: #93c5fd;
            background-color: rgba(59, 130, 246, 0.08);
        }

        .grid-mobile-option-label.is-selected {
            background-color: #eff6ff !important;
            border-color: #3b82f6 !important;
        }

        .dark .grid-mobile-option-label.is-selected {
            background-color: rgba(37, 99, 235, 0.2) !important;
            border-color: #60a5fa !important;
        }

        .grid-mobile-bullet {
            width: 1.125rem !important;
            height: 1.125rem !important;
            margin: 0 !important;
            margin-right: 0.75rem !important;
            flex-shrink: 0;
            cursor: pointer;
        }

        .grid-mobile-option-body {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            flex: 1;
            min-width: 0;
        }

        .grid-mobile-score {
            width: 22px;
            height: 22px;
            min-width: 22px;
            border-radius: 50%;
            background-color: #e2e8f0;
            color: #334155;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
            font-weight: 700;
            flex-shrink: 0;
            transition: all 0.15s ease;
        }

        .dark .grid-mobile-score {
            background-color: #1e293b;
            color: #cbd5e1;
        }

        .grid-mobile-option-label.is-selected .grid-mobile-score {
            background-color: #2563eb;
            color: #ffffff;
        }

        .dark .grid-mobile-option-label.is-selected .grid-mobile-score {
            background-color: #3b82f6;
            color: #ffffff;
        }

        .grid-mobile-text {
            font-size: 0.875rem;
            font-weight: 500;
            line-height: 1.4;
            color: var(--ui-text-primary);
            cursor: pointer;
            word-break: break-word;
        }
    </style>
</head>

<body class="user-theme">

    <!-- Preloader -->
    <div class="preloader">
        <div class="preloader-inner">
            <div class="preloader-icon">
                <span></span>
                <span></span>
            </div>
        </div>
    </div>
    <!-- /End Preloader -->

    @include('user.layouts.navigation')


    @yield('content')


    <!-- ====== form question Start (Full Page) ====== -->
    <section id="contact" class="survey-main-section relative min-h-screen dark:bg-dark overflow-x-hidden w-full max-w-full">
        <div class="absolute top-0 left-0 -z-[1] w-full dark:bg-dark h-full bg-white"></div>
        <div class="w-full h-full overflow-x-hidden max-w-full">
            <div class="flex justify-center items-start min-h-screen w-full max-w-full overflow-x-hidden">
                <!-- Survey Header -->
                <div class="w-full max-w-5xl mx-auto px-4 sm:px-6 min-w-0 max-w-full">
                    <div class="survey-header-container p-5 sm:p-7 md:p-8 lg:p-10">
                        <h1 class="survey-header-heading">
                            {{ $survey->nama }}
                        </h1>
                        
                        <div class="survey-header-badges">
                            <span class="survey-pill">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                    <line x1="16" y1="2" x2="16" y2="6"></line>
                                    <line x1="8" y1="2" x2="8" y2="6"></line>
                                    <line x1="3" y1="10" x2="21" y2="10"></line>
                                </svg>
                                <span>{{ $survey->tanggal_mulai . ' s/d ' . $survey->tanggal_selesai }}</span>
                            </span>
                            <span class="survey-pill survey-pill-success">
                                <svg fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                <span>Tersimpan otomatis</span>
                            </span>
                        </div>

                        @if(!empty($survey->deskripsi))
                            <p class="survey-header-description">
                                {{ $survey->deskripsi }}
                            </p>
                        @endif
                    </div>
                                    <!-- Survey Form -->
                    <div x-data="surveyFlow()" class="w-full" x-init="init()">
                        <!-- Autosave Indicator -->
                        <div class="autosave-indicator"
                             :class="{
                                 'saving': autosaveStatus === 'saving',
                                 'saved': autosaveStatus === 'saved',
                                 'error': autosaveStatus === 'error',
                                 'idle': autosaveStatus === 'idle',
                                 'hidden-indicator': autosaveStatus === 'hidden'
                             }">
                            <!-- Saving spinner -->
                            <template x-if="autosaveStatus === 'saving'">
                                <div class="autosave-spinner"></div>
                            </template>
                            <!-- Saved checkmark -->
                            <template x-if="autosaveStatus === 'saved'">
                                <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                                    <path d="M13.5 4.5L6.5 11.5L2.5 7.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </template>
                            <!-- Error icon -->
                            <template x-if="autosaveStatus === 'error'">
                                <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                                    <circle cx="8" cy="8" r="6" stroke="currentColor" stroke-width="1.5"/>
                                    <path d="M8 5v3.5M8 10.5v.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                                </svg>
                            </template>
                            <!-- Idle dot -->
                            <template x-if="autosaveStatus === 'idle'">
                                <div class="autosave-dot"></div>
                            </template>
                            <!-- Status text -->
                            <span x-text="autosaveMessage"></span>
                        </div>
                        <!-- Progress Bar -->
                        <div class="bg-white dark:bg-dark-2 rounded-lg shadow-lg p-5 sm:p-7 md:p-8 lg:p-10 mb-6 sm:mb-8 theme-surface theme-border">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm font-medium theme-text-primary">Progres Survey</span>
                                <span class="text-sm theme-text-secondary" x-text="Math.round(progress) + '%'"></span>
                            </div>
                            <div class="w-full rounded-full h-2 theme-surface-soft">
                                <div class="bg-blue-600 h-2 rounded-full transition-all duration-300" 
                                     :style="'width: ' + progress + '%'"></div>
                            </div>
                        </div>

                        <!-- Survey Content -->
                        <form @submit.prevent="handleSubmit" method="POST" class="w-full min-w-0 max-w-full" x-show="!isCompleted">
                            @csrf
                            <div class="bg-white dark:bg-dark-2 rounded-lg shadow-lg p-5 sm:p-7 md:p-8 lg:p-10 theme-surface theme-border min-w-0 w-full max-w-full overflow-hidden">
                                <!-- Current Block Header -->
                                <div class="mb-8 sm:mb-10" x-show="currentBlock">
                                    <h3 class="mb-4 text-xl font-semibold md:text-2xl theme-text-primary border-b border-blue-200 dark:border-dark-3 pb-3 theme-border" 
                                        x-text="currentBlock?.nama || 'Loading...'">
                                    </h3>
                                    <p class="text-sm theme-text-secondary" x-text="currentBlock?.deskripsi || ''"></p>
                                </div>

                                <!-- Identity Block Information Banner -->
                                <div x-show="isCurrentBlockIdentity()" class="mb-6 p-4 bg-sky-50 dark:bg-slate-800 border border-sky-200 dark:border-slate-700 rounded-lg shadow-xs flex items-start gap-3.5">
                                    <div class="p-2 bg-sky-100 dark:bg-sky-900/60 rounded-full text-sky-600 dark:text-sky-300 mt-0.5 flex-shrink-0">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-sm text-sky-950 dark:text-sky-100 mb-1 flex items-center gap-2">
                                            <span>Data Identitas Responden</span>
                                            <span class="text-[11px] font-semibold px-2 py-0.5 rounded bg-sky-200/70 text-sky-900 dark:bg-sky-900 dark:text-sky-200">Terverifikasi & Terkunci</span>
                                        </h4>
                                        <p class="text-xs text-sky-800 dark:text-sky-300 leading-relaxed">
                                            Sebagian besar isian di blok ini diambil secara otomatis dari profil akun Anda dan bersifat <strong>terkunci (read-only)</strong>. Beberapa isian tertentu mungkin dapat Anda lengkapi. Silakan periksa data Anda, lalu klik tombol <strong>Lanjutkan</strong> untuk melanjutkan pengisian survei.
                                        </p>
                                    </div>
                                </div>

                                <!-- Questions In Current Block -->
                                <template x-if="currentBlock?.metadata?.is_kompetensi && currentBlock.metadata.pertanyaan_utama && !currentBlock.metadata.pertanyaan_utama.includes('[indikator]')">
                                    <div class="mb-4 p-4 bg-sky-50 dark:bg-dark-3 rounded border border-blue-100 dark:border-dark-3">
                                        <span class="font-semibold text-base theme-text-primary" x-text="currentBlock.metadata.pertanyaan_utama"></span>
                                    </div>
                                </template>
                                <template x-for="question in currentQuestions" :key="question.id">
                                     <div class="mb-6 sm:mb-8 p-4 sm:p-5 bg-sky-50 border border-sky-100 dark:bg-dark-3 dark:border-dark-3 rounded-lg shadow-sm question-box theme-question min-w-0 w-full max-w-full overflow-hidden"
                                          x-show="currentBlock && currentQuestions.length > 0">
                                         
                                         <!-- Question Title -->
                                         <label :for="'question_' + question.id"
                                                class="font-semibold flex items-center justify-between flex-wrap gap-2 mb-2 sm:mb-3 text-base theme-text-primary">
                                             <div>
                                                 <span x-text="currentBlock?.metadata?.is_kompetensi && currentBlock.metadata.pertanyaan_utama ? currentBlock.metadata.pertanyaan_utama.replace('[indikator]', question.pertanyaan) : question.pertanyaan"></span>
                                                 <template x-if="question.is_required == 1 || question.is_required === true || question.is_required === '1'">
                                                     <span class="required-asterisk text-red-500 font-bold ml-1" style="color: #ef4444 !important;">*</span>
                                                 </template>
                                             </div>
                                             <template x-if="isQuestionLocked(question)">
                                                 <span class="inline-flex items-center gap-1.5 text-xs font-semibold px-2.5 py-1 rounded bg-slate-200 text-slate-700 dark:bg-slate-700 dark:text-slate-300">
                                                     <svg class="w-3 h-3 text-slate-600 dark:text-slate-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path></svg>
                                                     <span>Terkunci</span>
                                                 </span>
                                             </template>
                                         </label>
                                        
                                        <!-- Question Description -->
                                        <label :for="'question_' + question.id"
                                               class="block mb-3 sm:mb-4 text-sm theme-text-secondary"
                                               x-text="question.deskripsi_pertanyaan || ''"></label>

                                        <!-- Question Input Based on Type -->
                                        <div x-show="question.tipe === 'text' || (isCurrentBlockIdentity() && (question.tipe === 'radio' || isGenderQuestion(question)))">
                                            <input type="text" 
                                                   :name="'answer_' + question.id"
                                                   :id="'question_' + question.id"
                                                   x-model="answers[question.id]"
                                                   :disabled="isQuestionLocked(question)"
                                                   :readonly="isQuestionLocked(question)"
                                                   :class="isQuestionLocked(question) ? '!bg-slate-100 dark:!bg-slate-800 !text-slate-700 dark:!text-slate-200 !border-slate-300 dark:!border-slate-600 cursor-not-allowed font-medium' : ''"
                                                   @input="!isQuestionLocked(question) && markDirty()"
                                                   class="w-full p-3 bg-white dark:bg-dark-2 border border-blue-200 dark:border-dark-3 rounded-md theme-input" />
                                        </div>

                                        <div x-show="question.tipe === 'textarea'">
                                            <textarea :name="'answer_' + question.id"
                                                      :id="'question_' + question.id"
                                                      x-model="answers[question.id]"
                                                      :disabled="isQuestionLocked(question)"
                                                      :readonly="isQuestionLocked(question)"
                                                      :class="isQuestionLocked(question) ? '!bg-slate-100 dark:!bg-slate-800 !text-slate-700 dark:!text-slate-200 !border-slate-300 dark:!border-slate-600 cursor-not-allowed font-medium' : ''"
                                                      @input="!isQuestionLocked(question) && markDirty()"
                                                      rows="4"
                                                      class="w-full p-3 bg-white dark:bg-dark-2 border border-blue-200 dark:border-dark-3 rounded-md theme-input"></textarea>
                                        </div>

                                        <div x-show="question.tipe === 'number'">
                                            <input type="number" 
                                                   :name="'answer_' + question.id"
                                                   :id="'question_' + question.id"
                                                   x-model="answers[question.id]"
                                                   :disabled="isQuestionLocked(question)"
                                                   :readonly="isQuestionLocked(question)"
                                                   :class="isQuestionLocked(question) ? '!bg-slate-100 dark:!bg-slate-800 !text-slate-700 dark:!text-slate-200 !border-slate-300 dark:!border-slate-600 cursor-not-allowed font-medium' : ''"
                                                   @input="!isQuestionLocked(question) && markDirty()"
                                                   class="w-full p-3 bg-white dark:bg-dark-2 border border-blue-200 dark:border-dark-3 rounded-md theme-input" />
                                        </div>

                                        <div x-show="question.tipe === 'gaji'">
                                            <div class="gaji-input-wrapper" :class="isQuestionLocked(question) ? 'cursor-not-allowed opacity-90' : ''">
                                                <span class="gaji-prefix" :class="isQuestionLocked(question) ? '!bg-slate-200 dark:!bg-slate-700' : ''">Rp</span>
                                                <input type="text" 
                                                       :name="'answer_' + question.id"
                                                       :id="'question_' + question.id"
                                                       x-model="answers[question.id]"
                                                       :disabled="isQuestionLocked(question)"
                                                       :readonly="isQuestionLocked(question)"
                                                       :class="isQuestionLocked(question) ? '!bg-slate-100 dark:!bg-slate-800 !text-slate-700 dark:!text-slate-200 !border-slate-300 dark:!border-slate-600 cursor-not-allowed font-medium' : ''"
                                                       @input="!isQuestionLocked(question) && ($event.target.value = $event.target.value.replace(/[^0-9]/g, ''), answers[question.id] = $event.target.value, markDirty())"
                                                       class="gaji-input-field bg-white dark:bg-dark-2 border border-blue-200 dark:border-dark-3 theme-input"
                                                       placeholder="Contoh: 900000" />
                                            </div>
                                            <p x-show="answers[question.id]" class="mt-2 text-sm text-blue-600 font-medium">
                                                <span x-text="'Terbilang: Rp ' + Number(answers[question.id]).toLocaleString('id-ID')"></span>
                                            </p>
                                            <div x-show="question.min_gaji && answers[question.id] && !isNaN(parseInt(answers[question.id], 10)) && parseInt(answers[question.id], 10) < question.min_gaji" 
                                                 x-transition:enter="transition ease-out duration-200"
                                                 x-transition:enter-start="opacity-0 transform -translate-y-1"
                                                 x-transition:enter-end="opacity-100 transform translate-y-0"
                                                 class="mt-2 p-3 bg-amber-50 dark:bg-amber-900/30 border border-amber-300 dark:border-amber-700 text-amber-800 dark:text-amber-300 rounded-md text-xs sm:text-sm flex items-center gap-2 shadow-sm">
                                                <i class="fas fa-exclamation-triangle text-amber-500 text-sm flex-shrink-0"></i>
                                                <span>Peringatan: Nominal gaji yang dimasukkan kurang dari Rp <span x-text="Number(question.min_gaji).toLocaleString('id-ID')"></span>. Mohon pastikan nominal yang Anda masukkan sudah benar.</span>
                                            </div>
                                        </div>

                                        <div x-show="question.tipe === 'radio' && !(isCurrentBlockIdentity() && (question.tipe === 'radio' || isGenderQuestion(question)))">
                                            <div class="flex flex-col gap-y-2 mb-2 theme-text-primary"
                                                 :class="isQuestionLocked(question) ? 'cursor-not-allowed' : ''">
                                                <template x-for="option in question.template_jawaban" :key="option.id">
                                                    <label class="survey-option-label theme-text-primary"
                                                           :class="isQuestionLocked(question) ? '!cursor-not-allowed !bg-slate-100/60 dark:!bg-slate-800/60 border border-slate-300/80 dark:border-slate-700 pointer-events-none' : 'hover:bg-blue-100 dark:hover:bg-dark-2 cursor-pointer'"
                                                           @click.prevent="isQuestionLocked(question) ? null : selectRadioOption(question.id, option.id, option.pilihan_jawaban)">
                                                        <input type="radio" 
                                                               :name="'answer_' + question.id"
                                                               :value="String(option.id)"
                                                               :checked="answers[question.id] == option.id"
                                                               x-model="answers[question.id]"
                                                               :disabled="isQuestionLocked(question)"
                                                               class="survey-option-bullet text-blue-600 focus:ring-blue-500 dark:text-primary dark:focus:ring-primary"
                                                               :class="isQuestionLocked(question) ? '!cursor-not-allowed opacity-90' : ''">
                                                        <span class="survey-option-text" 
                                                              :class="isQuestionLocked(question) ? '!cursor-not-allowed' : ''"
                                                              x-text="option.pilihan_jawaban"></span>
                                                    </label>
                                                </template>
                                            </div>
                                        </div>

                                        <div x-show="question.tipe === 'multiple_choice_grid'" class="w-full max-w-full min-w-0 mt-3"
                                             :class="isQuestionLocked(question) ? 'cursor-not-allowed' : ''">
                                            <!-- Desktop Table View (>= 768px) -->
                                            <div class="grid-desktop-view">
                                                <div class="grid-table-container">
                                                    <div class="grid-table-wrapper">
                                                        <table class="grid-table min-w-full text-left">
                                                            <thead>
                                                                <tr>
                                                                    <th class="px-4 py-3.5 text-xs sm:text-sm font-semibold tracking-wide min-w-[190px] sm:min-w-[260px] text-left">
                                                                        <span>Pernyataan</span>
                                                                    </th>
                                                                    <template x-for="scale in getGridColumns(question)" :key="'header_' + question.id + '_' + scale.value">
                                                                        <th class="px-2.5 py-3 text-center min-w-[90px] sm:min-w-[110px] w-[95px] sm:w-[125px]">
                                                                            <div class="flex flex-col items-center justify-center gap-1.5">
                                                                                <div class="inline-flex shrink-0 items-center justify-center w-7 h-7 rounded-full bg-blue-100 text-blue-700 dark:bg-blue-900/60 dark:text-blue-300 font-bold text-xs sm:text-sm shadow-sm border border-blue-200 dark:border-blue-800" x-text="scale.value"></div>
                                                                                <div class="grid-col-label text-[11px] sm:text-xs font-semibold leading-snug text-slate-700 dark:text-slate-200" x-text="scale.label"></div>
                                                                            </div>
                                                                        </th>
                                                                    </template>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <template x-for="(row, rIdx) in question.template_jawaban" :key="'row_' + row.id">
                                                                    <tr class="transition-colors duration-150"
                                                                        :class="rIdx % 2 === 0 ? 'row-even' : 'row-odd'">
                                                                        <td class="px-4 py-3 sm:py-3.5 text-xs sm:text-sm font-medium text-slate-900 dark:text-slate-100 leading-relaxed align-middle" 
                                                                            x-text="row.pilihan_jawaban">
                                                                        </td>
                                                                        <template x-for="scale in getGridColumns(question)" :key="'cell_' + question.id + '_' + row.id + '_' + scale.value">
                                                                            <td class="px-2 py-2.5 text-center align-middle transition-colors"
                                                                                :class="[
                                                                                    isQuestionLocked(question) ? 'cursor-not-allowed opacity-80' : 'cursor-pointer',
                                                                                    getMultipleChoiceGridValue(question.id, row.id) == scale.value ? 'is-selected' : ''
                                                                                ]"
                                                                                @click="!isQuestionLocked(question) && selectMultipleChoiceGridOption(question.id, row.id, scale.value)">
                                                                                <label class="flex items-center justify-center w-full h-full min-h-[38px] m-0"
                                                                                       :class="isQuestionLocked(question) ? 'cursor-not-allowed pointer-events-none' : 'cursor-pointer'">
                                                                                    <input type="radio"
                                                                                           :name="'multiple_choice_grid_' + question.id + '_' + row.id"
                                                                                           :value="scale.value"
                                                                                           :checked="getMultipleChoiceGridValue(question.id, row.id) == scale.value"
                                                                                           :disabled="isQuestionLocked(question)"
                                                                                           @change="!isQuestionLocked(question) && selectMultipleChoiceGridOption(question.id, row.id, scale.value)"
                                                                                           class="w-4.5 h-4.5 text-blue-600 focus:ring-blue-500 dark:text-primary dark:focus:ring-primary transition-transform"
                                                                                           :class="isQuestionLocked(question) ? 'cursor-not-allowed' : 'cursor-pointer hover:scale-110'" />
                                                                                </label>
                                                                            </td>
                                                                        </template>
                                                                    </tr>
                                                                </template>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Mobile Stacked Statement Cards View (< 768px) -->
                                            <div class="grid-mobile-view">
                                                <template x-for="(row, rIdx) in question.template_jawaban" :key="'mobile_row_' + row.id">
                                                    <div class="grid-mobile-card">
                                                        <!-- Statement Header -->
                                                        <div class="grid-mobile-statement">
                                                            <span class="grid-mobile-stmt-number" x-text="(rIdx + 1) + '.'"></span>
                                                            <span class="grid-mobile-stmt-text" x-text="row.pilihan_jawaban"></span>
                                                        </div>

                                                        <!-- Options List (Radio Buttons per Column Scale) -->
                                                        <div class="grid-mobile-options">
                                                            <template x-for="scale in getGridColumns(question)" :key="'mobile_scale_' + question.id + '_' + row.id + '_' + scale.value">
                                                                <label class="grid-mobile-option-label"
                                                                       :class="[
                                                                           isQuestionLocked(question) ? 'cursor-not-allowed opacity-80 pointer-events-none' : 'cursor-pointer',
                                                                           getMultipleChoiceGridValue(question.id, row.id) == scale.value ? 'is-selected' : ''
                                                                       ]"
                                                                       @click="!isQuestionLocked(question) && selectMultipleChoiceGridOption(question.id, row.id, scale.value)">
                                                                    <input type="radio"
                                                                           :name="'multiple_choice_grid_mobile_' + question.id + '_' + row.id"
                                                                           :value="scale.value"
                                                                           :checked="getMultipleChoiceGridValue(question.id, row.id) == scale.value"
                                                                           :disabled="isQuestionLocked(question)"
                                                                           @change="!isQuestionLocked(question) && selectMultipleChoiceGridOption(question.id, row.id, scale.value)"
                                                                           class="grid-mobile-bullet text-blue-600 focus:ring-blue-500 dark:text-primary dark:focus:ring-primary"
                                                                           :class="isQuestionLocked(question) ? 'cursor-not-allowed' : ''" />
                                                                    <div class="grid-mobile-option-body">
                                                                        <span class="grid-mobile-score" x-text="scale.value"></span>
                                                                        <span class="grid-mobile-text" x-text="scale.label"></span>
                                                                    </div>
                                                                </label>
                                                            </template>
                                                        </div>
                                                    </div>
                                                </template>
                                            </div>
                                        </div>

                                        <div x-show="question.tipe === 'select'">
                                            <select :name="'answer_' + question.id"
                                                    :id="'question_' + question.id"
                                                    x-model="answers[question.id]"
                                                    :disabled="isQuestionLocked(question)"
                                                    :class="isQuestionLocked(question) ? '!bg-slate-100 dark:!bg-slate-800 !text-slate-700 dark:!text-slate-200 !border-slate-300 dark:!border-slate-600 cursor-not-allowed font-medium' : ''"
                                                    @change="!isQuestionLocked(question) && handleSelectChange(question.id, $event.target.value)"
                                                    class="w-full p-3 bg-white dark:bg-dark-2 border border-blue-200 dark:border-dark-3 rounded-md theme-input">
                                                <option value="" disabled>Choose</option>
                                                <template x-for="option in question.template_jawaban" :key="option.id">
                                                    <option :value="String(option.id)" x-text="option.pilihan_jawaban" :selected="answers[question.id] == option.id"></option>
                                                </template>
                                            </select>
                                        </div>

                                        <div x-show="question.tipe === 'checkbox'">
                                            <div class="flex flex-col gap-y-2 mb-2 theme-text-primary"
                                                 :class="isQuestionLocked(question) ? 'cursor-not-allowed' : ''">
                                                <template x-for="option in question.template_jawaban" :key="option.id">
                                                    <label :for="'option_' + question.id + '_' + option.id"
                                                           class="survey-option-label theme-text-primary"
                                                           :class="isQuestionLocked(question) ? '!cursor-not-allowed !bg-slate-100/60 dark:!bg-slate-800/60 border border-slate-300/80 dark:border-slate-700 pointer-events-none' : 'hover:bg-blue-100 dark:hover:bg-dark-2 cursor-pointer'"
                                                           @click.prevent="isQuestionLocked(question) ? null : null">
                                                        <input type="checkbox" 
                                                               :id="'option_' + question.id + '_' + option.id"
                                                               :name="'answer_' + question.id + '[]'"
                                                               :value="String(option.id)"
                                                               :checked="answers[question.id] && answers[question.id].includes(String(option.id))"
                                                               :disabled="isQuestionLocked(question)"
                                                               @change="!isQuestionLocked(question) && handleCheckboxChange(question.id, String(option.id), $event.target.checked)"
                                                               class="survey-option-bullet text-blue-600 border border-blue-300 dark:border-dark-3 focus:ring-blue-500 dark:text-primary dark:focus:ring-primary rounded"
                                                               :class="isQuestionLocked(question) ? '!cursor-not-allowed' : ''" />
                                                        <span class="survey-option-text" 
                                                              :class="isQuestionLocked(question) ? '!cursor-not-allowed' : ''"
                                                              x-text="option.pilihan_jawaban"></span>
                                                    </label>
                                                </template>
                                            </div>
                                        </div>

                                        <div x-show="question.tipe === 'date'">
                                            <input type="date" 
                                                   :name="'answer_' + question.id"
                                                   :id="'question_' + question.id"
                                                   x-model="answers[question.id]"
                                                   :disabled="isQuestionLocked(question)"
                                                   :readonly="isQuestionLocked(question)"
                                                   :class="isQuestionLocked(question) ? '!bg-slate-100 dark:!bg-slate-800 !text-slate-700 dark:!text-slate-200 !border-slate-300 dark:!border-slate-600 cursor-not-allowed font-medium' : ''"
                                                   @change="!isQuestionLocked(question) && markDirty()"
                                                   class="w-full p-3 bg-white dark:bg-dark-2 border border-blue-200 dark:border-dark-3 rounded-md theme-input" />
                                        </div>

                                        <div x-show="question.tipe === 'file'">
                                            <input type="file" 
                                                   :name="'answer_' + question.id"
                                                   :id="'question_' + question.id"
                                                   :disabled="isQuestionLocked(question)"
                                                   :class="isQuestionLocked(question) ? '!bg-slate-100 dark:!bg-slate-800 !text-slate-700 dark:!text-slate-200 !border-slate-300 dark:!border-slate-600 cursor-not-allowed font-medium opacity-75' : ''"
                                                   @change="!isQuestionLocked(question) && handleFileChange(question.id, $event)"
                                                   class="w-full p-3 bg-white dark:bg-dark-2 border border-blue-200 dark:border-dark-3 rounded-md theme-input file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:bg-blue-600 file:text-white dark:file:bg-primary" />
                                        </div>
                                    </div>
                                    </template>

                                <!-- Navigation Buttons -->
                                <div class="flex flex-col sm:flex-row sm:justify-between gap-4 mt-8 sm:mt-10 pt-6 border-t border-blue-200 dark:border-dark-3 theme-border">
                                    <div class="flex flex-col sm:flex-row gap-3">
                                        <button type="button" 
                                                @click="previousBlock()" 
                                                x-show="canGoPrevious"
                                                class="h-[46px] inline-flex items-center justify-center px-6 sm:px-8 py-2 text-base font-medium bg-blue-50 dark:bg-dark-3 hover:bg-blue-100 dark:hover:bg-dark-2 transition duration-300 ease-in-out rounded-md border border-blue-200 dark:border-dark-3 theme-btn-secondary">
                                            <i class="fas fa-arrow-left mr-2"></i>
                                            Sebelumnya
                                        </button>
                                    </div>
                                    
                                    <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 sm:justify-end">
                                        <!-- Simpan Jawaban Button -->
                                        <button type="button" 
                                                @click="manualSave()" 
                                                :disabled="isSaving"
                                                :class="{
                                                    'saved-state': manualSaveState === 'saved'
                                                }"
                                                class="h-[46px] save-draft-btn inline-flex items-center justify-center px-6 sm:px-8 py-2 text-base font-medium text-white transition-all duration-300 ease-in-out rounded-md">
                                            <!-- Default state -->
                                            <template x-if="manualSaveState === 'idle'">
                                                <span class="inline-flex items-center">
                                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/>
                                                    </svg>
                                                    Simpan Jawaban
                                                </span>
                                            </template>
                                            <!-- Saving state -->
                                            <template x-if="manualSaveState === 'saving'">
                                                <span class="inline-flex items-center">
                                                    <div class="btn-spinner mr-2"></div>
                                                    Menyimpan...
                                                </span>
                                            </template>
                                            <!-- Saved state -->
                                            <template x-if="manualSaveState === 'saved'">
                                                <span class="inline-flex items-center">
                                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                                    </svg>
                                                    Tersimpan!
                                                </span>
                                            </template>
                                            <!-- Error state -->
                                            <template x-if="manualSaveState === 'error'">
                                                <span class="inline-flex items-center">
                                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                    </svg>
                                                    Gagal, Coba Lagi
                                                </span>
                                            </template>
                                        </button>

                                        <button type="button" 
                                                @click="nextBlock()" 
                                                x-show="!isLastBlock"
                                                :disabled="!canProceed"
                                                :class="canProceed ? 'submit-btn' : 'theme-btn-disabled'"
                                                class="h-[46px] inline-flex items-center justify-center px-6 sm:px-8 py-2 text-base font-medium text-white transition duration-300 ease-in-out rounded-md">
                                            Selanjutnya
                                            <i class="fas fa-arrow-right ml-2"></i>
                                        </button>
                                        
                                        <button type="submit" 
                                                x-show="isLastBlock"
                                                :disabled="!canProceed"
                                                :class="canProceed ? 'submit-btn' : 'theme-btn-disabled'"
                                                class="h-[46px] inline-flex items-center justify-center px-6 sm:px-8 py-2 text-base font-medium text-white transition duration-300 ease-in-out rounded-md">
                                            Kirim Survey
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>

                        <!-- Completion Message -->
                        <div x-show="isCompleted" class="text-center">
                            <div class="bg-green-50 border border-green-200 rounded-lg p-8">
                                <div class="text-green-600 mb-4">
                                    <i class="fas fa-check-circle text-4xl"></i>
                                </div>
                                <h3 class="text-xl font-semibold text-green-800 mb-2">Survey Selesai!</h3>
                                <p class="text-green-700">Terima kasih atas partisipasi Anda dalam survey ini.</p>
                                <a href="/" class="inline-flex items-center justify-center px-6 py-2 mt-4 text-base font-medium text-white bg-green-600 hover:bg-green-700 transition duration-300 ease-in-out rounded-md">
                                    Kembali ke Beranda
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- ====== Contact End ====== -->


    @include('user.layouts.footer')

    <!-- ====== Back To Top Start -->
    <a href="javascript:void(0)"
        class="back-to-top fixed bottom-8 left-auto right-8 z-[999] hidden h-10 w-10 items-center justify-center rounded-md bg-primary text-white shadow-md transition duration-300 ease-in-out hover:bg-dark">
        <span class="mt-[6px] h-3 w-3 rotate-45 border-l border-t border-white"></span>
    </a>
    <!-- ====== Back To Top End -->



    <!-- ====== All Scripts -->

    <script src="{{ asset('assets/js/swiper-bundle.min.js') }}"></script>
    <script src="{{ asset('assets/js/main.js') }}"></script>
    <script>
        // Survey Flow Component
        function surveyFlow() {
            return {
                surveyId: {{ $survey->id }},
                surveyData: @json($surveyPertanyaan),
                blockStructure: @json($blockStructure ?? []),
                existingAnswers: @json($existingAnswers ?? []),
                allBlocks: [],
                currentBlockIndex: 0,
                currentBlock: null,
                currentQuestions: [],
                answers: {},
                progress: 0,
                isCompleted: false,
                questionHistory: [],

                // Autosave state
                autosaveStatus: 'hidden',  // 'hidden', 'idle', 'saving', 'saved', 'error'
                autosaveMessage: '',
                autosaveTimer: null,
                autosaveDebounceTimer: null,
                autosaveInterval: 15000, // 15 seconds
                autosaveHideTimer: null,
                lastSavedAnswers: '',
                isDirty: false,
                isSaving: false,
                manualSaveState: 'idle', // 'idle', 'saving', 'saved', 'error'

                init() {
                    console.log('Survey Flow Initialized', this.surveyData);
                    this.loadSurveyStructure();
                    this.loadExistingAnswers();
                    this.startSurvey();
                    this.initAutosave();
                },

                loadSurveyStructure() {
                    // Use block structure if available (new block-based surveys)
                    if (this.blockStructure && this.blockStructure.length > 0) {
                        console.log('Using block structure:', this.blockStructure);
                        this.allBlocks = this.blockStructure.map((block, index) => ({
                            id: block.id,
                            urutan: block.urutan,
                            nama: block.nama,
                            deskripsi: block.deskripsi,
                            metadata: block.metadata,
                            questions: (this.surveyData[block.id] || this.surveyData[block.nama] || []).map(q => ({
                                ...q,
                                template_jawaban: q.template_jawaban || q.templateJawaban || []
                            }))
                        }));
                    } else {
                        // Fallback: Convert surveyData object to blocks array (legacy support)
                        console.log('Using legacy structure, surveyData:', this.surveyData);
                        this.allBlocks = Object.entries(this.surveyData).map(([blockName, questions], index) => ({
                            id: index + 1,
                            urutan: index + 1,
                            nama: blockName,
                            deskripsi: '',
                            questions: questions.map(q => ({
                                ...q,
                                template_jawaban: q.templateJawaban || q.template_jawaban || []
                            }))
                        }));
                    }
                    console.log('Loaded blocks with structure:', this.allBlocks);

                    // Pastikan pertanyaan pada blok identitas yang bertipe radio atau pertanyaan jenis kelamin dinormalisasi menjadi text
                    this.allBlocks.forEach((block, bIdx) => {
                        const isIdentity = !!(block.metadata?.is_identity_block || (block.urutan === 1 && bIdx === 0));
                        if (isIdentity && block.questions) {
                            block.questions.forEach(q => {
                                if (q.tipe === 'radio' || (q.pertanyaan && (q.pertanyaan.toLowerCase().includes('jenis kelamin') || q.pertanyaan.toLowerCase().includes('gender')))) {
                                    q.tipe = 'text';
                                }
                            });
                        }
                    });
                    
                    // Debug: Check if navigation targets are loaded
                    this.allBlocks.forEach(block => {
                        block.questions.forEach(question => {
                            if (question.tipe === 'radio' && question.template_jawaban && question.template_jawaban.length > 0) {
                                console.log(`Single-choice question "${question.pertanyaan}" has options:`, 
                                    question.template_jawaban.map(opt => ({
                                        text: opt.pilihan_jawaban,
                                        target: opt.navigation_target
                                    }))
                                );
                            }
                        });
                    });
                },

                startSurvey() {
                    if (this.allBlocks.length === 0) {
                        console.error('No blocks found');
                        return;
                    }
                    
                    // Try to resume history from localStorage
                    const savedHistory = localStorage.getItem('survey_history_' + this.surveyId);
                    if (savedHistory) {
                        try {
                            this.questionHistory = JSON.parse(savedHistory);
                        } catch (e) {
                            this.questionHistory = [];
                        }
                    } else {
                        this.questionHistory = [];
                    }

                    // Try to resume from localStorage
                    const savedBlockIndex = localStorage.getItem('survey_resume_block_' + this.surveyId);
                    if (savedBlockIndex !== null && parseInt(savedBlockIndex) < this.allBlocks.length) {
                        this.currentBlockIndex = parseInt(savedBlockIndex);
                    } else {
                        this.currentBlockIndex = 0;
                    }
                    
                    this.loadCurrentBlock();
                    this.updateProgress(); // Initialize progress at start
                },

                loadCurrentBlock() {
                    // Save position to localStorage
                    localStorage.setItem('survey_resume_block_' + this.surveyId, this.currentBlockIndex);
                    
                    console.log('=== LOADING CURRENT BLOCK ===');
                    console.log('Current block index:', this.currentBlockIndex, 'Total blocks:', this.allBlocks.length);
                    
                    if (this.currentBlockIndex >= this.allBlocks.length) {
                        console.log('ðŸ No more blocks, completing survey');
                        this.completeSurvey();
                        return;
                    }

                    this.currentBlock = this.allBlocks[this.currentBlockIndex];
                    this.currentQuestions = this.currentBlock.questions || [];
                    
                    console.log('âœ… Block loaded:', {
                        blockName: this.currentBlock.nama,
                        blockId: this.currentBlock.id,
                        blockOrder: this.currentBlock.urutan,
                        questionsCount: this.currentQuestions.length,
                        firstQuestion: this.currentQuestions[0]?.pertanyaan || 'No questions'
                    });
                },

                scrollToTop() {
                    try {
                        window.scrollTo({
                            top: 0,
                            left: 0,
                            behavior: 'smooth'
                        });
                    } catch (e) {
                        window.scrollTo(0, 0);
                    }
                },

                async nextBlock() {
                    this.scrollToTop();
                    this.addToHistory();

                    // Autosave on block navigation
                    if (this.isDirty) {
                        await this.performAutosave();
                    }

                    const navigationTarget = this.resolveCurrentBlockNavigationTarget();

                    if (navigationTarget === 'end') {
                        await this.handleSubmit();
                        return;
                    }

                    if (navigationTarget && typeof navigationTarget === 'number') {
                        this.jumpToBlock(navigationTarget);
                        return;
                    }

                    this.currentBlockIndex++;
                    this.loadCurrentBlock();
                    this.updateProgress();

                    this.$nextTick(() => {
                        this.scrollToTop();
                    });
                },

                previousBlock() {
                    this.scrollToTop();

                    // Autosave on block navigation (fire-and-forget)
                    if (this.isDirty) {
                        this.performAutosave();
                    }

                    if (this.questionHistory.length > 0) {
                        const lastPosition = this.questionHistory.pop();
                        localStorage.setItem('survey_history_' + this.surveyId, JSON.stringify(this.questionHistory));
                        this.currentBlockIndex = lastPosition.blockIndex;
                    } else if (this.currentBlockIndex > 0) {
                        // Fallback: If history was lost (e.g. cross-device resume), just step back one block
                        this.currentBlockIndex--;
                    }
                    
                    this.loadCurrentBlock();
                    this.updateProgress();

                    this.$nextTick(() => {
                        this.scrollToTop();
                    });
                },

                jumpToBlock(blockId) {
                    this.scrollToTop();
                    console.log('=== JUMPING TO BLOCK ===');
                    console.log('Target block ID:', blockId);
                    console.log('Available blocks:', this.allBlocks.map(b => ({ id: b.id, nama: b.nama, urutan: b.urutan })));
                    
                    // Find block by ID
                    const blockIndex = this.allBlocks.findIndex(block => block.id === blockId);
                    
                    if (blockIndex !== -1) {
                        const targetBlock = this.allBlocks[blockIndex];
                        console.log('✅ Block found, jumping to:', {
                            blockId: blockId,
                            blockIndex: blockIndex,
                            blockName: targetBlock.nama,
                            blockOrder: targetBlock.urutan,
                            questionsCount: targetBlock.questions?.length || 0
                        });
                        
                        this.currentBlockIndex = blockIndex;
                        this.loadCurrentBlock();
                        this.updateProgress();
                        
                        console.log('✅ Jump completed. Current state:', {
                            currentBlockIndex: this.currentBlockIndex,
                            currentBlockName: this.currentBlock?.nama,
                            currentQuestions: this.currentQuestions?.length || 0
                        });

                        this.$nextTick(() => {
                            this.scrollToTop();
                        });
                    } else {
                        console.error('❌ Block not found for jump:', { 
                            blockId,
                            availableBlocks: this.allBlocks.map(b => ({ id: b.id, nama: b.nama }))
                        });
                    }
                },

                addToHistory() {
                    this.questionHistory.push({
                        blockIndex: this.currentBlockIndex
                    });
                    
                    if (this.questionHistory.length > 50) {
                        this.questionHistory = this.questionHistory.slice(-25);
                    }
                    
                    // Save history to localStorage to support resuming backward navigation
                    localStorage.setItem('survey_history_' + this.surveyId, JSON.stringify(this.questionHistory));
                },

                resolveCurrentBlockNavigationTarget() {
                    if (!this.currentQuestions || this.currentQuestions.length === 0) {
                        return null;
                    }

                    // Prioritaskan rule dari pertanyaan terakhir dalam blok yang punya navigation target.
                    for (let idx = this.currentQuestions.length - 1; idx >= 0; idx--) {
                        const question = this.currentQuestions[idx];
                        const target = this.checkBranchingRules(question);
                        if (target) {
                            return target;
                        }
                    }

                    return null;
                },

                checkBranchingRules(question) {
                    console.log('=== CHECKING BRANCHING RULES ===');
                    console.log('Question:', {
                        id: question.id,
                        type: question.tipe,
                        text: question.pertanyaan
                    });

                    if (!question || !['radio', 'select'].includes(question.tipe)) {
                        console.log('âŒ No branching rules: not radio/select type');
                        return null;
                    }

                    const selectedAnswerId = this.answers[question.id];
                    console.log('Selected answer ID:', selectedAnswerId);
                    
                    if (!selectedAnswerId) {
                        console.log('âŒ No selected answer');
                        return null;
                    }

                    // Find the selected option
                    const selectedOption = question.template_jawaban.find(opt => opt.id == selectedAnswerId);
                    console.log('Available options:', question.template_jawaban);
                    
                    if (!selectedOption) {
                        console.log('âŒ Selected option not found', { 
                            selectedAnswerId,
                            availableOptions: question.template_jawaban.map(opt => ({ id: opt.id, text: opt.pilihan_jawaban }))
                        });
                        return null;
                    }

                    console.log('âœ… Selected option found:', {
                        option_id: selectedOption.id,
                        option_text: selectedOption.pilihan_jawaban,
                        navigation_target: selectedOption.navigation_target
                    });

                    if (!selectedOption.navigation_target || selectedOption.navigation_target === '' || selectedOption.navigation_target === null) {
                        console.log('âŒ No navigation target set for this option');
                        return null;
                    }

                    const navigationTarget = selectedOption.navigation_target;
                    console.log('Processing navigation target:', navigationTarget);

                    // Handle different navigation target formats
                    if (navigationTarget === 'end') {
                        console.log('ðŸ Navigation target is END SURVEY');
                        return 'end';
                    } else if (navigationTarget === 'next') {
                        console.log('âž¡ï¸ Navigation target is NEXT (normal flow)');
                        return null;
                    } else if (navigationTarget.startsWith('block_')) {
                        // Extract block number from 'block_X' format
                        const blockNumber = parseInt(navigationTarget.substring(6));
                        console.log('ðŸŽ¯ Navigation target is specific block:', blockNumber);
                        
                        console.log('Available blocks for search:', this.allBlocks.map(b => ({ 
                            id: b.id, 
                            nama: b.nama, 
                            urutan: b.urutan 
                        })));
                        
                        // Find the block by its order (urutan)
                        const targetBlock = this.allBlocks.find(block => block.urutan === blockNumber);
                        
                        if (targetBlock) {
                            console.log('âœ… Target block found:', {
                                block_id: targetBlock.id,
                                block_name: targetBlock.nama,
                                block_order: targetBlock.urutan
                            });
                            return targetBlock.id;
                        } else {
                            console.log('âŒ Target block not found for order:', blockNumber);
                            console.log('Available block orders:', this.allBlocks.map(b => b.urutan));
                        }
                    } else {
                        console.log('â“ Unknown navigation target format:', navigationTarget);
                    }

                    console.log('âŒ No matching navigation rule found');
                    return null;
                },

                loadExistingAnswers() {
                    // Load existing answers if user is resuming the survey
                    if (this.existingAnswers && Object.keys(this.existingAnswers).length > 0) {
                        Object.entries(this.existingAnswers).forEach(([questionId, answer]) => {
                            const question = this.findQuestionById(questionId);
                            
                            if (!question) {
                                this.answers[questionId] = answer;
                                return;
                            }

                            if (question.tipe === 'multiple_choice_grid') {
                                try {
                                    const parsed = typeof answer === 'string' ? JSON.parse(answer) : answer;
                                    this.answers[questionId] = (parsed && typeof parsed === 'object') ? parsed : {};
                                } catch (error) {
                                    this.answers[questionId] = {};
                                }
                            } else if (question.tipe === 'checkbox') {
                                if (Array.isArray(answer)) {
                                    this.answers[questionId] = answer;
                                } else if (typeof answer === 'string' && answer.trim() !== '') {
                                    this.answers[questionId] = answer.split(',').map(s => s.trim()).filter(s => s !== '');
                                } else if (answer) {
                                    this.answers[questionId] = [String(answer)];
                                } else {
                                    this.answers[questionId] = [];
                                }
                            } else {
                                // Regular answers (radio, text, etc.)
                                this.answers[questionId] = answer;
                            }
                        });
                        console.log('Normalized existing answers:', this.answers);
                    }

                    // Fallback: pastikan setiap pertanyaan yang memiliki default_value (seperti pertanyaan identitas) terisi di answers
                    if (this.allBlocks && this.allBlocks.length > 0) {
                        this.allBlocks.forEach((block, bIdx) => {
                            const isIdentity = !!(block.metadata?.is_identity_block || (block.urutan === 1 && bIdx === 0));
                            (block.questions || []).forEach(q => {
                                if (isIdentity && q.default_value !== undefined && q.default_value !== null && q.default_value !== '') {
                                    this.answers[q.id] = String(q.default_value);
                                } else if (q.default_value !== undefined && q.default_value !== null && (this.answers[q.id] === undefined || this.answers[q.id] === null || this.answers[q.id] === '')) {
                                    this.answers[q.id] = String(q.default_value);
                                }

                                // Jika di blok identitas jawaban masih tersimpan sebagai ID pilihan (misal ID 10/11), ubah jadi teks pilihan
                                if (isIdentity && this.answers[q.id] && q.template_jawaban && q.template_jawaban.length > 0) {
                                    const opt = q.template_jawaban.find(o => String(o.id) === String(this.answers[q.id]));
                                    if (opt) {
                                        this.answers[q.id] = opt.pilihan_jawaban;
                                    }
                                }
                            });
                        });
                    }
                },

                isCurrentBlockIdentity() {
                    return !!(this.currentBlock?.metadata?.is_identity_block || (this.currentBlock?.urutan === 1 && this.currentBlockIndex === 0));
                },

                isGenderQuestion(question) {
                    if (!question || !question.pertanyaan) return false;
                    const text = question.pertanyaan.toLowerCase();
                    return text.includes('jenis kelamin') || text.includes('gender') || text.includes('kelamin');
                },

                isQuestionLocked(question) {
                    if (!question) return false;
                    if (this.isCurrentBlockIdentity() && (question.pertanyaan || '').trim().toLowerCase() === 'alamat satuan kerja') {
                        return false;
                    }
                    return !!(question.is_readonly || this.isCurrentBlockIdentity());
                },

                selectRadioOption(questionId, optionId, optionText) {
                    const activeQuestion = this.currentQuestions.find(q => q.id == questionId);
                    if (this.isQuestionLocked(activeQuestion)) {
                        return;
                    }

                    this.answers[questionId] = optionId;
                    this.markDirty();
                    console.log('Selected radio option:', {
                        questionId: questionId,
                        optionId: optionId,
                        optionText: optionText,
                        currentAnswers: this.answers
                    });
                    
                    // Find the selected option and log its navigation target
                    if (activeQuestion && activeQuestion.template_jawaban) {
                        const selectedOption = activeQuestion.template_jawaban.find(opt => opt.id == optionId);
                        if (selectedOption) {
                            console.log('Selected option details:', {
                                option: selectedOption,
                                navigation_target: selectedOption.navigation_target
                            });
                        }
                    }
                },

                handleSelectChange(questionId, value) {
                    const activeQuestion = this.currentQuestions.find(q => q.id == questionId);
                    if (this.isQuestionLocked(activeQuestion)) {
                        return;
                    }

                    this.answers[questionId] = value;
                    this.markDirty();
                },

                selectMultipleChoiceGridOption(questionId, rowId, value) {
                    const activeQuestion = this.currentQuestions.find(q => q.id == questionId);
                    if (this.isQuestionLocked(activeQuestion)) {
                        return;
                    }

                    if (!this.answers[questionId] || typeof this.answers[questionId] !== 'object' || Array.isArray(this.answers[questionId])) {
                        this.answers[questionId] = {};
                    }

                    this.answers[questionId][rowId] = Number(value);
                    this.markDirty();
                },

                getMultipleChoiceGridValue(questionId, rowId) {
                    const questionAnswer = this.answers[questionId];
                    if (!questionAnswer || typeof questionAnswer !== 'object' || Array.isArray(questionAnswer)) {
                        return '';
                    }

                    return questionAnswer[rowId] ?? '';
                },

                getGridColumns(question) {
                    const fallback = [
                        'Sangat Tidak Setuju',
                        'Tidak Setuju',
                        'Netral',
                        'Setuju',
                        'Sangat Setuju',
                    ];

                    const sourceColumns = Array.isArray(question?.grid_columns) && question.grid_columns.length >= 2
                        ? question.grid_columns
                        : fallback;

                    return sourceColumns
                        .map((label, index) => ({
                            value: index + 1,
                            label: String(label || '').trim(),
                        }))
                        .filter((item) => item.label.length > 0);
                },

                findQuestionById(questionId) {
                    for (const block of this.allBlocks) {
                        const question = (block.questions || []).find(q => String(q.id) === String(questionId));
                        if (question) {
                            return question;
                        }
                    }

                    return null;
                },

                handleCheckboxChange(questionId, optionId, checked) {
                    const activeQuestion = this.currentQuestions.find(q => q.id == questionId);
                    if (this.isQuestionLocked(activeQuestion)) {
                        return;
                    }

                    if (!this.answers[questionId]) {
                        this.answers[questionId] = [];
                    }
                    
                    if (checked) {
                        if (!this.answers[questionId].includes(optionId)) {
                            this.answers[questionId].push(optionId);
                        }
                    } else {
                        this.answers[questionId] = this.answers[questionId].filter(id => id !== optionId);
                    }
                    this.markDirty();
                },

                handleFileChange(questionId, event) {
                    const activeQuestion = this.currentQuestions.find(q => q.id == questionId);
                    if (this.isQuestionLocked(activeQuestion)) {
                        return;
                    }

                    const file = event.target.files[0];
                    if (file) {
                        this.answers[questionId] = file;
                        this.markDirty();
                    }
                },

                updateProgress() {
                    const totalQuestions = this.allBlocks.reduce((total, block) => total + block.questions.length, 0);
                    let completedQuestions = 0;

                    this.allBlocks.forEach((block) => {
                        (block.questions || []).forEach((question) => {
                            if (this.isQuestionAnswered(question)) {
                                completedQuestions++;
                            }
                        });
                    });
                    
                    // Calculate progress percentage
                    this.progress = totalQuestions > 0 ? Math.min((completedQuestions / totalQuestions) * 100, 100) : 0;
                    
                    console.log('Progress update:', {
                        currentBlock: this.currentBlockIndex,
                        completedQuestions: completedQuestions,
                        totalQuestions: totalQuestions,
                        progress: this.progress
                    });
                },

                completeSurvey() {
                    this.isCompleted = true;
                    this.progress = 100;
                    localStorage.removeItem('survey_resume_block_' + this.surveyId);
                    localStorage.removeItem('survey_history_' + this.surveyId);
                },

                isQuestionAnswered(question) {
                    if (!question) return false;

                    const answer = this.answers[question.id];

                    switch (question.tipe) {
                        case 'radio':
                        case 'select':
                            return answer !== undefined && answer !== null && answer !== '';
                        case 'multiple_choice_grid': {
                            if (!answer || typeof answer !== 'object' || Array.isArray(answer)) {
                                return false;
                            }

                            const expectedRows = (question.template_jawaban || []).map(option => String(option.id));
                            if (expectedRows.length === 0) {
                                return false;
                            }

                            return expectedRows.every((rowId) => {
                                const value = answer[rowId];
                                const columns = this.getGridColumns(question);
                                const maxVal = columns.length;
                                return Number.isInteger(Number(value)) && Number(value) >= 1 && Number(value) <= maxVal;
                            });
                        }
                        case 'checkbox':
                            return Array.isArray(answer) && answer.length > 0;
                        case 'text':
                        case 'textarea':
                        case 'number':
                        case 'gaji':
                        case 'date':
                            return answer !== undefined && answer !== null && answer.toString().trim() !== '';
                        case 'file':
                            return answer instanceof File || (typeof answer === 'string' && answer.trim() !== '');
                        default:
                            return true;
                    }
                },

                async handleSubmit() {
                    try {
                        // Prepare form data
                        const formData = new FormData();
                        
                        // Add CSRF token
                        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || 
                                         document.querySelector('input[name="_token"]')?.value;
                        if (csrfToken) {
                            formData.append('_token', csrfToken);
                        }

                        // Add answers with correct field names for backend
                        Object.entries(this.answers).forEach(([questionId, answer]) => {
                            if (answer === null || answer === undefined) return;

                            if (Array.isArray(answer)) {
                                // Checkbox answers
                                if (answer.length === 0) {
                                    formData.append(`answer_${questionId}`, '');
                                } else {
                                    answer.forEach(value => {
                                        formData.append(`answer_${questionId}[]`, value);
                                    });
                                }
                            } else if (answer instanceof File) {
                                // File upload
                                formData.append(`answer_${questionId}`, answer);
                            } else if (answer && typeof answer === 'object') {
                                // Multiple choice grid answers
                                formData.append(`answer_${questionId}`, JSON.stringify(answer));
                            } else {
                                // Regular answers (including empty strings to clear DB)
                                formData.append(`answer_${questionId}`, answer);
                            }
                        });

                        // Submit to server
                        const response = await fetch(`/user/survey/${this.surveyId}`, {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                            }
                        });

                        if (response.ok) {
                            const result = await response.json();
                            if (result.success) {
                                console.log('âœ… Survey submitted successfully:', result.message);
                                this.completeSurvey();
                                // Show success message for 3 seconds then redirect
                                setTimeout(() => {
                                    window.location.href = result.redirect || '/user/profile';
                                }, 3000);
                            } else {
                                throw new Error(result.message || 'Survey submission failed');
                            }
                        } else {
                            const errorResult = await response.json();
                            throw new Error(errorResult.message || 'Survey submission failed');
                        }
                    } catch (error) {
                        console.error('Error submitting survey:', error);
                        alert('Terjadi kesalahan saat mengirim survey. Silakan coba lagi.');
                    }
                },

                get canGoPrevious() {
                    return this.questionHistory.length > 0 || this.currentBlockIndex > 0;
                },

                get canProceed() {
                    // If no questions in this block, allow proceeding
                    if (!this.currentQuestions || this.currentQuestions.length === 0) {
                        return true;
                    }

                    // Only required questions must be answered to proceed
                    return this.currentQuestions.every((question) => {
                        // Robust check for required status (handles boolean, integer, or string "0"/"1")
                        const isRequired = question.is_required === true || 
                                         question.is_required === 1 || 
                                         question.is_required === "1";
                                         
                        if (!isRequired) return true;
                        return this.isQuestionAnswered(question);
                    });
                },

                get isLastBlock() {
                    return this.currentBlockIndex >= this.allBlocks.length - 1;
                },

                // ======== AUTOSAVE METHODS ========

                initAutosave() {
                    // Snapshot current answers as the "last saved" baseline
                    this.lastSavedAnswers = this.getAnswersSnapshot();
                    
                    // Show idle status briefly then hide
                    if (Object.keys(this.existingAnswers).length > 0) {
                        this.setAutosaveStatus('idle', 'Jawaban tersimpan sebelumnya dimuat');
                        this.scheduleHideIndicator(4000);
                    }

                    // Start periodic autosave timer
                    this.autosaveTimer = setInterval(() => {
                        if (this.isDirty && !this.isCompleted) {
                            this.performAutosave();
                        }
                    }, this.autosaveInterval);

                    // Autosave on text input changes (debounced)
                    this.$watch('answers', () => {
                        this.updateProgress();
                    }, { deep: true });

                    // Save before user leaves the page
                    window.addEventListener('beforeunload', (e) => {
                        if (this.isDirty && !this.isCompleted) {
                            this.performAutosaveSync();
                            e.preventDefault();
                            e.returnValue = 'Anda memiliki jawaban yang belum tersimpan. Yakin ingin meninggalkan halaman?';
                        }
                    });

                    // Also listen for visibility change (tab switch on mobile)
                    document.addEventListener('visibilitychange', () => {
                        if (document.visibilityState === 'hidden' && this.isDirty && !this.isCompleted) {
                            this.performAutosaveSync();
                        }
                    });

                    console.log('Autosave initialized (interval: ' + this.autosaveInterval/1000 + 's)');
                },

                markDirty() {
                    this.isDirty = true;
                    
                    // Debounced autosave: save 3 seconds after last change
                    if (this.autosaveDebounceTimer) {
                        clearTimeout(this.autosaveDebounceTimer);
                    }
                    this.autosaveDebounceTimer = setTimeout(() => {
                        if (this.isDirty && !this.isCompleted) {
                            this.performAutosave();
                        }
                    }, 3000);
                },

                getAnswersSnapshot() {
                    try {
                        return JSON.stringify(this.answers);
                    } catch(e) {
                        return '';
                    }
                },

                hasAnswersChanged() {
                    return this.getAnswersSnapshot() !== this.lastSavedAnswers;
                },

                buildAutosaveFormData() {
                    const formData = new FormData();
                    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                    if (csrfToken) {
                        formData.append('_token', csrfToken);
                    }

                    // Add current block index for resume functionality
                    formData.append('current_block_index', this.currentBlockIndex);

                    // Add all answers
                    Object.entries(this.answers).forEach(([questionId, answer]) => {
                        if (answer === null || answer === undefined) return;
                        
                        if (Array.isArray(answer)) {
                            if (answer.length === 0) {
                                formData.append(`answer_${questionId}`, '');
                            } else {
                                answer.forEach(value => {
                                    formData.append(`answer_${questionId}[]`, value);
                                });
                            }
                        } else if (answer instanceof File) {
                            // Skip files for autosave (too heavy)
                            return;
                        } else if (answer && typeof answer === 'object') {
                            formData.append(`answer_${questionId}`, JSON.stringify(answer));
                        } else {
                            // Allow empty strings to clear DB values
                            formData.append(`answer_${questionId}`, answer);
                        }
                    });

                    return formData;
                },

                async performAutosave(isManual = false) {
                    // Skip if nothing changed or survey completed (but allow manual save to always proceed)
                    if (!isManual && (!this.hasAnswersChanged() || this.isCompleted)) {
                        this.isDirty = false;
                        return;
                    }

                    if (this.isCompleted) return;

                    this.isSaving = true;
                    if (!isManual) {
                        this.setAutosaveStatus('saving', 'Menyimpan jawaban...');
                    }

                    try {
                        const formData = this.buildAutosaveFormData();

                        const response = await fetch(`/user/survey/${this.surveyId}/autosave`, {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                            }
                        });

                        if (response.ok) {
                            const result = await response.json();
                            if (result.success && !result.skipped) {
                                this.lastSavedAnswers = this.getAnswersSnapshot();
                                this.isDirty = false;
                                
                                if (isManual) {
                                    this.manualSaveState = 'saved';
                                    setTimeout(() => { this.manualSaveState = 'idle'; }, 2500);
                                }
                                
                                this.setAutosaveStatus('saved', `Tersimpan ${result.saved_at}`);
                                this.scheduleHideIndicator(4000);
                                console.log('Autosave success:', result);
                            } else if (result.skipped) {
                                this.isDirty = false;
                                if (isManual) {
                                    this.manualSaveState = 'saved';
                                    setTimeout(() => { this.manualSaveState = 'idle'; }, 2500);
                                }
                                console.log('Autosave skipped:', result.message);
                            }
                        } else {
                            throw new Error(`HTTP ${response.status}`);
                        }
                    } catch (error) {
                        console.error('Autosave error:', error);
                        if (isManual) {
                            this.manualSaveState = 'error';
                            setTimeout(() => { this.manualSaveState = 'idle'; }, 4000);
                        }
                        this.setAutosaveStatus('error', 'Gagal menyimpan otomatis');
                        this.scheduleHideIndicator(6000);
                    } finally {
                        this.isSaving = false;
                    }
                },

                async manualSave() {
                    if (this.isSaving) return;
                    this.manualSaveState = 'saving';
                    await this.performAutosave(true);
                },

                // Synchronous autosave for beforeunload (uses sendBeacon)
                performAutosaveSync() {
                    if (!this.hasAnswersChanged() || this.isCompleted) return;

                    try {
                        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                        const payload = { _token: csrfToken, current_block_index: this.currentBlockIndex };
                        
                        Object.entries(this.answers).forEach(([questionId, answer]) => {
                            if (answer === null || answer === undefined || answer instanceof File) return;
                            
                            if (Array.isArray(answer)) {
                                payload[`answer_${questionId}`] = answer.length > 0 ? answer.join(',') : '';
                            } else if (typeof answer === 'object') {
                                payload[`answer_${questionId}`] = JSON.stringify(answer);
                            } else {
                                payload[`answer_${questionId}`] = answer;
                            }
                        });

                        const blob = new Blob([JSON.stringify(payload)], { type: 'application/json' });
                        navigator.sendBeacon(`/user/survey/${this.surveyId}/autosave`, blob);
                        console.log('Autosave beacon sent');
                    } catch (error) {
                        console.error('Autosave beacon error:', error);
                    }
                },

                setAutosaveStatus(status, message) {
                    this.autosaveStatus = status;
                    this.autosaveMessage = message;
                    
                    // Clear any pending hide timer
                    if (this.autosaveHideTimer) {
                        clearTimeout(this.autosaveHideTimer);
                        this.autosaveHideTimer = null;
                    }
                },

                scheduleHideIndicator(delay) {
                    if (this.autosaveHideTimer) {
                        clearTimeout(this.autosaveHideTimer);
                    }
                    this.autosaveHideTimer = setTimeout(() => {
                        this.autosaveStatus = 'hidden';
                    }, delay);
                },

                // Clean up timers when component is destroyed
                destroy() {
                    if (this.autosaveTimer) clearInterval(this.autosaveTimer);
                    if (this.autosaveDebounceTimer) clearTimeout(this.autosaveDebounceTimer);
                    if (this.autosaveHideTimer) clearTimeout(this.autosaveHideTimer);
                }
            }
        }

        // ==== for menu scroll
        const pageLink = document.querySelectorAll(".ud-menu-scroll");

        pageLink.forEach((elem) => {
            elem.addEventListener("click", (e) => {
                e.preventDefault();
                document.querySelector(elem.getAttribute("href")).scrollIntoView({
                    behavior: "smooth",
                    offsetTop: 1 - 60,
                });
            });
        });

        // section menu active
        function onScroll(event) {
            const sections = document.querySelectorAll(".ud-menu-scroll");
            const scrollPos =
                window.pageYOffset ||
                document.documentElement.scrollTop ||
                document.body.scrollTop;

            for (let i = 0; i < sections.length; i++) {
                const currLink = sections[i];
                const val = currLink.getAttribute("href");
                const refElement = document.querySelector(val);
                const scrollTopMinus = scrollPos + 73;
                if (
                    refElement.offsetTop <= scrollTopMinus &&
                    refElement.offsetTop + refElement.offsetHeight > scrollTopMinus
                ) {
                    document
                        .querySelector(".ud-menu-scroll")
                        .classList.remove("active");
                    currLink.classList.add("active");
                } else {
                    currLink.classList.remove("active");
                }
            }
        }

        window.document.addEventListener("scroll", onScroll);

        // Testimonial
        const testimonialSwiper = new Swiper(".testimonial-carousel", {
            slidesPerView: 1,
            spaceBetween: 30,

            // Navigation arrows
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev",
            },

            breakpoints: {
                640: {
                    slidesPerView: 2,
                    spaceBetween: 30,
                },
                1024: {
                    slidesPerView: 3,
                    spaceBetween: 30,
                },
                1280: {
                    slidesPerView: 3,
                    spaceBetween: 30,
                },
            },
        });
    </script>
</body>

</html>



