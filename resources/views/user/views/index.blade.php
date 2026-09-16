<!DOCTYPE html>
<html lang="en" class="scroll-smooth">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>
        Tracer Study
    </title>
    <link rel="shortcut icon" href="{{ asset('assets/images/logo.png') }}" type="image/x-icon" />
    <link rel="stylesheet" href="{{ asset('assets/css/swiper-bundle.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/animate.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/tailwind.css') }}" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    @include('user.layouts.theme')

    <style>
        .select2-container--default .select2-selection--single {
            border: 2px solid #e5e7eb;
            border-radius: 0.375rem;
            height: 38px;
            padding: 4px 8px;
            font-size: 0.875rem;
            transition: border-color 0.2s;
        }
        .select2-container--default .select2-selection--single:focus,
        .select2-container--default.select2-container--open .select2-selection--single {
            border-color: #3b82f6;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 36px;
        }
        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: #3b82f6;
        }
        .select2-dropdown {
            border-radius: 0.375rem;
            border: 2px solid #e5e7eb;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }
        .select2-search--dropdown .select2-search__field {
            border-radius: 0.375rem;
            border: 1px solid #d1d5db;
            padding: 6px 8px;
        }
        .dark .select2-container--default .select2-selection--single {
            background-color: #1e293b;
            border-color: #475569;
            color: #e2e8f0;
        }
        .dark .select2-dropdown {
            background-color: #1e293b;
            border-color: #475569;
        }
        .dark .select2-container--default .select2-results__option {
            color: #e2e8f0;
        }
        .dark .select2-search--dropdown .select2-search__field {
            background-color: #0f172a;
            border-color: #475569;
            color: #e2e8f0;
        }
    </style>

    <!-- ==== WOW JS ==== -->
    <script src="{{ asset('assets/js/wow.min.js') }}"></script>
    <script>
        new WOW().init();
    </script>
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

    <!-- ====== Banner Section Start -->
    <div class="user-banner-bar">
        <div class="container px-4">
            <div class="user-banner-container">
                <div class="user-banner-avatar">
                    @if($user->hasRole('lulusan') && $user->lulusan)
                        {{ strtoupper(substr($user->lulusan->nama, 0, 1)) }}
                    @elseif($user->hasRole('penggunaLulusan') && $user->penggunaLulusan)
                        {{ strtoupper(substr($user->penggunaLulusan->nama, 0, 1)) }}
                    @else
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    @endif
                </div>
                <div class="user-banner-content">
                    <div class="user-banner-title-row">
                        <h1 class="user-banner-name">
                            Halo, 
                            @if($user->hasRole('lulusan') && $user->lulusan)
                                {{ $user->lulusan->nama }}
                            @elseif($user->hasRole('penggunaLulusan') && $user->penggunaLulusan)
                                {{ $user->penggunaLulusan->nama }}
                            @else
                                {{ $user->name }}
                            @endif
                        </h1>
                        <span class="user-role-badge">
                            @if($user->hasRole('lulusan'))
                                Alumni / Lulusan
                            @elseif($user->hasRole('penggunaLulusan'))
                                Pengguna Lulusan
                            @else
                                Responden
                            @endif
                        </span>
                    </div>
                    <p class="user-banner-subtitle">
                        Portal Tracer Study Politeknik Statistika STIS. Silakan lengkapi data profil dan isi survei Anda.
                    </p>
                </div>
            </div>
        </div>
    </div>
    <!-- ====== Banner Section End -->

    <!-- ====== Info Survei & User Start ====== -->
    <section id="contact" class="relative theme-section-space">
        <div class="container px-4">
            
            <!-- Card 1: Data Diri Responden -->
            <div class="user-profile-card">
                <div class="user-profile-header">
                    <div class="user-profile-title-group">
                        <span class="user-profile-icon" style="background-color: rgba(37, 99, 235, 0.12); color: #2563eb;">
                            <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                            </svg>
                        </span>
                        <div>
                            <h2 style="font-size: 1.125rem; font-weight: 700; color: var(--ui-text-primary); margin: 0; line-height: 1.3;">
                                Data Diri Responden
                            </h2>
                            <p style="font-size: 0.75rem; color: var(--ui-text-secondary); margin: 0;">
                                Informasi identitas dan kepegawaian Anda
                            </p>
                        </div>
                    </div>
                    <button id="editToggle" onclick="toggleEdit()" class="theme-btn-primary" style="padding: 0.45rem 0.9rem; font-size: 0.8125rem; font-weight: 600; border-radius: 0.5rem; display: inline-flex; align-items: center; gap: 0.375rem; cursor: pointer;">
                        <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        <span>Edit Profil</span>
                    </button>
                </div>

                <!-- Display Success/Error Messages -->
                @if(session('success'))
                    <div id="successMessage" class="mb-4 p-3.5 border-l-4 border-green-400 rounded-r-md shadow-sm theme-alert-success text-xs sm:text-sm">
                        <div class="flex items-center">
                            <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="font-medium">{{ session('success') }}</span>
                        </div>
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-4 p-3.5 border-l-4 border-red-400 rounded-r-md shadow-sm theme-alert-danger text-xs sm:text-sm">
                        <div class="flex items-center">
                            <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="font-medium">{{ session('error') }}</span>
                        </div>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-4 p-3.5 border-l-4 border-red-400 rounded-r-md shadow-sm theme-alert-danger text-xs sm:text-sm">
                        <div class="flex items-start">
                            <svg class="w-4 h-4 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                            </svg>
                            <div>
                                <p class="font-medium mb-1">Terdapat kesalahan:</p>
                                <ul class="list-disc list-inside space-y-0.5">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- User Information Form -->
                <form id="userInfoForm" action="{{ route('user.profile.update') }}" method="POST">
                    @csrf
                    
                    <div class="user-info-grid">
                        <!-- 1. Nama Lengkap -->
                        <div class="user-info-tile">
                            <span class="user-info-label">Nama Lengkap</span>
                            <span class="user-info-value">
                                @if($user->hasRole('lulusan') && $user->lulusan)
                                    {{ $user->lulusan->nama }}
                                @elseif($user->hasRole('penggunaLulusan') && $user->penggunaLulusan)
                                    {{ $user->penggunaLulusan->nama }}
                                @else
                                    {{ $user->name }}
                                @endif
                            </span>
                        </div>

                        <!-- 2. Email -->
                        <div class="user-info-tile">
                            <span class="user-info-label">Email</span>
                            <span class="user-info-value" style="word-break: break-all;">{{ $user->email }}</span>
                        </div>

                        <!-- 3. NIP Baru (18 Digit) -->
                        <div class="user-info-tile">
                            <span class="user-info-label">NIP Baru (18 Digit)</span>
                            <span class="user-info-value">
                                @if($user->hasRole('lulusan') && $user->lulusan)
                                    {{ $user->lulusan->nip_baru ?? $user->lulusan->nip ?? '-' }}
                                @elseif($user->hasRole('penggunaLulusan') && $user->penggunaLulusan)
                                    {{ $user->penggunaLulusan->nip_baru ?? $user->penggunaLulusan->nip ?? '-' }}
                                @else
                                    -
                                @endif
                            </span>
                        </div>

                        <!-- 4. NIP Lama (9 Digit) -->
                        <div class="user-info-tile">
                            <span class="user-info-label">NIP Lama (9 Digit)</span>
                            <span class="user-info-value">
                                @if($user->hasRole('lulusan') && $user->lulusan)
                                    {{ $user->lulusan->nip_lama ?? '-' }}
                                @elseif($user->hasRole('penggunaLulusan') && $user->penggunaLulusan)
                                    {{ $user->penggunaLulusan->nip_lama ?? '-' }}
                                @else
                                    -
                                @endif
                            </span>
                        </div>

                        <!-- 5. Jabatan -->
                        <div class="user-info-tile">
                            <span class="user-info-label">Jabatan</span>
                            <div class="view-mode">
                                <span class="user-info-value">
                                    @if($user->hasRole('lulusan') && $user->lulusan)
                                        {{ $user->lulusan->jabatan ?? '-' }}
                                    @elseif($user->hasRole('penggunaLulusan') && $user->penggunaLulusan)
                                        {{ $user->penggunaLulusan->jabatan ?? '-' }}
                                    @else
                                        -
                                    @endif
                                </span>
                            </div>
                            <div class="edit-mode hidden">
                                <select name="jabatan" id="user_jabatan" class="user-searchable-select w-full">
                                    <option value="">-- Pilih Jabatan --</option>
                                    @foreach($masterJabatan as $j)
                                        <option value="{{ $j->nama }}" 
                                            @if($user->hasRole('lulusan') && $user->lulusan && $user->lulusan->jabatan == $j->nama) selected
                                            @elseif($user->hasRole('penggunaLulusan') && $user->penggunaLulusan && $user->penggunaLulusan->jabatan == $j->nama) selected
                                            @endif
                                        >{{ $j->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- 6. Satuan Kerja -->
                        <div class="user-info-tile">
                            <span class="user-info-label">Satuan Kerja</span>
                            <div class="view-mode">
                                <span class="user-info-value">
                                    @if($user->hasRole('lulusan') && $user->lulusan)
                                        {{ $user->lulusan->satuan_kerja ?? '-' }}
                                    @elseif($user->hasRole('penggunaLulusan') && $user->penggunaLulusan)
                                        {{ $user->penggunaLulusan->satuan_kerja ?? '-' }}
                                    @else
                                        -
                                    @endif
                                </span>
                            </div>
                            <div class="edit-mode hidden">
                                <select name="satuan_kerja" id="user_satuan_kerja" class="user-searchable-select w-full">
                                    <option value="">-- Pilih Satuan Kerja --</option>
                                    @foreach($masterSatuanKerja as $sk)
                                        <option value="{{ $sk->nama }}" 
                                            @if($user->hasRole('lulusan') && $user->lulusan && $user->lulusan->satuan_kerja == $sk->nama) selected
                                            @elseif($user->hasRole('penggunaLulusan') && $user->penggunaLulusan && $user->penggunaLulusan->satuan_kerja == $sk->nama) selected
                                            @endif
                                        >{{ $sk->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- 7. Unit Kerja -->
                        <div class="user-info-tile">
                            <span class="user-info-label">Unit Kerja</span>
                            <div class="view-mode">
                                <span class="user-info-value">
                                    @if($user->hasRole('lulusan') && $user->lulusan)
                                        {{ $user->lulusan->unit_kerja ?? '-' }}
                                    @elseif($user->hasRole('penggunaLulusan') && $user->penggunaLulusan)
                                        {{ $user->penggunaLulusan->unit_kerja ?? '-' }}
                                    @else
                                        -
                                    @endif
                                </span>
                            </div>
                            <div class="edit-mode hidden">
                                <select name="unit_kerja" id="user_unit_kerja" class="user-searchable-select w-full">
                                    <option value="">-- Pilih Unit Kerja --</option>
                                    @foreach($masterUnitKerja as $uk)
                                        <option value="{{ $uk->nama }}" 
                                            @if($user->hasRole('lulusan') && $user->lulusan && $user->lulusan->unit_kerja == $uk->nama) selected
                                            @elseif($user->hasRole('penggunaLulusan') && $user->penggunaLulusan && $user->penggunaLulusan->unit_kerja == $uk->nama) selected
                                            @endif
                                        >{{ $uk->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- 8. No Handphone -->
                        <div class="user-info-tile">
                            <span class="user-info-label">No. Handphone</span>
                            <div class="view-mode">
                                <span class="user-info-value">
                                    @if($user->hasRole('lulusan') && $user->lulusan)
                                        {{ $user->lulusan->no_hp ?? '-' }}
                                    @elseif($user->hasRole('penggunaLulusan') && $user->penggunaLulusan)
                                        {{ $user->penggunaLulusan->no_hp ?? '-' }}
                                    @else
                                        -
                                    @endif
                                </span>
                            </div>
                            <div class="edit-mode hidden">
                                <input type="text" name="no_hp" 
                                    value="@if($user->hasRole('lulusan') && $user->lulusan){{ $user->lulusan->no_hp }}@elseif($user->hasRole('penggunaLulusan') && $user->penggunaLulusan){{ $user->penggunaLulusan->no_hp }}@endif"
                                    class="w-full px-2.5 py-1.5 text-xs sm:text-sm border rounded-md transition-colors theme-input"
                                    placeholder="Masukkan nomor HP">
                            </div>
                        </div>

                        <!-- Action buttons for Edit Mode (Simpan & Batal) -->
                        <div id="actionButtons" class="user-info-actions hidden" style="display: none;">
                            <button type="submit" 
                                class="theme-btn-primary"
                                style="padding: 0.5rem 1rem; font-size: 0.8125rem; font-weight: 600; border-radius: 0.5rem; display: inline-flex; align-items: center; gap: 0.375rem; cursor: pointer;">
                                <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span>Simpan Perubahan</span>
                            </button>
                            <button type="button" onclick="cancelEdit()" 
                                class="theme-btn-secondary"
                                style="padding: 0.5rem 1rem; font-size: 0.8125rem; font-weight: 600; border-radius: 0.5rem; display: inline-flex; align-items: center; gap: 0.375rem; cursor: pointer;">
                                <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                <span>Batal</span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Card 2: Informasi Survei -->
            <div class="user-profile-card">
                <div class="user-profile-header">
                    <div class="user-profile-title-group">
                        <span class="user-profile-icon" style="background-color: rgba(16, 185, 129, 0.12); color: #10b981;">
                            <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"></path>
                            </svg>
                        </span>
                        <div>
                            <h2 style="font-size: 1.125rem; font-weight: 700; color: var(--ui-text-primary); margin: 0; line-height: 1.3;">
                                Informasi Survei
                            </h2>
                            <p style="font-size: 0.75rem; color: var(--ui-text-secondary); margin: 0;">
                                Daftar survei Tracer Study yang ditugaskan kepada Anda
                            </p>
                        </div>
                    </div>
                    <div>
                        <span style="display: inline-flex; align-items: center; gap: 0.375rem; padding: 0.3rem 0.75rem; font-size: 0.75rem; font-weight: 600; border-radius: 9999px; background-color: var(--ui-surface-soft); color: var(--ui-text-secondary); border: 1px solid var(--ui-border);">
                            <span style="width: 7px; height: 7px; border-radius: 50%; background-color: #10b981;"></span>
                            {{ count($survey) }} Survei Ditemukan
                        </span>
                    </div>
                </div>

                <!-- Desktop Table Layout (shown only on desktop) -->
                <div class="user-survey-desktop-view">
                    <table class="user-survey-table">
                        <thead>
                            <tr>
                                <th style="width: 5%; text-align: center;">No</th>
                                <th style="width: 44%; text-align: left;">Nama Survei</th>
                                <th style="width: 25%; text-align: center;">Periode Pelaksanaan</th>
                                <th style="width: 12%; text-align: center;">Status</th>
                                <th style="width: 14%; text-align: center;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($survey as $srvy)
                                <tr>
                                    <td style="text-align: center; color: var(--ui-text-secondary); font-weight: 600; font-size: 0.8125rem;">
                                        {{ $loop->iteration }}
                                    </td>
                                    <td>
                                        <div style="font-weight: 700; color: var(--ui-text-primary); font-size: 0.9375rem; line-height: 1.35;">
                                            {{ $srvy->nama }}
                                        </div>
                                    </td>
                                    <td style="text-align: center;">
                                        <div style="display: inline-flex; align-items: center; gap: 0.375rem; font-size: 0.8125rem; color: var(--ui-text-secondary);">
                                            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                                <line x1="16" y1="2" x2="16" y2="6"></line>
                                                <line x1="8" y1="2" x2="8" y2="6"></line>
                                                <line x1="3" y1="10" x2="21" y2="10"></line>
                                            </svg>
                                            <span>{{ $srvy->tanggal_mulai }} s/d {{ $srvy->tanggal_selesai }}</span>
                                        </div>
                                    </td>
                                    <td style="text-align: center;">
                                        @if ($srvy->status_aktif == 'Aktif')
                                            <span class="badge-status-active">
                                                <svg width="6" height="6" fill="currentColor" viewBox="0 0 8 8">
                                                    <circle cx="4" cy="4" r="4"/>
                                                </svg>
                                                Aktif
                                            </span>
                                        @else
                                            <span class="badge-status-done">
                                                Selesai
                                            </span>
                                        @endif
                                    </td>
                                    <td style="text-align: center;">
                                        @if ($srvy->status == 0)
                                            @if ($srvy->status_aktif == 'Aktif')
                                                <a href="{{ route('user.survey.survey', ['id' => $srvy->id]) }}" class="btn-survey-action btn-survey-fill">
                                                    <svg width="14" height="14" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-8.293l-3-3a1 1 0 00-1.414 1.414L10.586 9H7a1 1 0 100 2h3.586l-1.293 1.293a1 1 0 101.414 1.414l3-3a1 1 0 000-1.414z" clip-rule="evenodd"></path>
                                                    </svg>
                                                    <span>Isi Survei</span>
                                                </a>
                                            @else
                                                <span class="btn-survey-action btn-survey-expired">
                                                    <svg width="14" height="14" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                                    </svg>
                                                    <span>Kadaluwarsa</span>
                                                </span>
                                            @endif
                                        @else
                                            @if ($srvy->status_aktif == 'Aktif')
                                                <a href="{{ route('user.survey.survey', ['id' => $srvy->id]) }}" class="btn-survey-action btn-survey-edit">
                                                    <svg width="14" height="14" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path>
                                                    </svg>
                                                    <span>Edit Jawaban</span>
                                                </a>
                                            @else
                                                <span class="btn-survey-action btn-survey-disabled">
                                                    <svg width="14" height="14" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                                    </svg>
                                                    <span>Selesai</span>
                                                </span>
                                            @endif
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" style="text-align: center; padding: 2.5rem 1rem; color: var(--ui-text-secondary);">
                                        <div style="display: flex; flex-direction: column; align-items: center; gap: 0.5rem;">
                                            <svg width="36" height="36" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5" style="opacity: 0.4;">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                            <span style="font-size: 0.875rem;">Tidak ada survei yang tersedia saat ini</span>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Mobile Card Layout (shown only on mobile/tablet) -->
                <div class="user-survey-mobile-view">
                    @forelse ($survey as $srvy)
                        <div style="background-color: var(--ui-surface-soft); border: 1px solid var(--ui-border); border-radius: 0.5rem; padding: 1rem;">
                            <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 0.5rem; margin-bottom: 0.5rem;">
                                <h3 style="font-size: 0.875rem; font-weight: 700; color: var(--ui-text-primary); margin: 0; line-height: 1.3;">
                                    {{ $srvy->nama }}
                                </h3>
                                @if ($srvy->status_aktif == 'Aktif')
                                    <span class="badge-status-active">Aktif</span>
                                @else
                                    <span class="badge-status-done">Selesai</span>
                                @endif
                            </div>
                            <div style="font-size: 0.75rem; color: var(--ui-text-secondary); margin-bottom: 0.75rem;">
                                {{ $srvy->tanggal_mulai }} s/d {{ $srvy->tanggal_selesai }}
                            </div>
                            <div>
                                @if ($srvy->status == 0)
                                    @if ($srvy->status_aktif == 'Aktif')
                                        <a href="{{ route('user.survey.survey', ['id' => $srvy->id]) }}" class="btn-survey-action btn-survey-fill" style="width: 100%;">
                                            <svg width="14" height="14" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-8.293l-3-3a1 1 0 00-1.414 1.414L10.586 9H7a1 1 0 100 2h3.586l-1.293 1.293a1 1 0 101.414 1.414l3-3a1 1 0 000-1.414z" clip-rule="evenodd"></path>
                                            </svg>
                                            <span>Isi Survei</span>
                                        </a>
                                    @else
                                        <div class="btn-survey-action btn-survey-disabled" style="width: 100%; color: #ef4444 !important;">
                                            Kadaluwarsa
                                        </div>
                                    @endif
                                @else
                                    @if ($srvy->status_aktif == 'Aktif')
                                        <a href="{{ route('user.survey.survey', ['id' => $srvy->id]) }}" class="btn-survey-action btn-survey-edit" style="width: 100%;">
                                            <svg width="14" height="14" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path>
                                            </svg>
                                            <span>Edit Jawaban</span>
                                        </a>
                                    @else
                                        <div class="btn-survey-action btn-survey-disabled" style="width: 100%;">
                                            Selesai
                                        </div>
                                    @endif
                                @endif
                            </div>
                        </div>
                    @empty
                        <div style="text-align: center; padding: 1.5rem; color: var(--ui-text-secondary); background-color: var(--ui-surface-soft); border-radius: 0.5rem; border: 1px solid var(--ui-border);">
                            Tidak ada survei yang tersedia saat ini
                        </div>
                    @endforelse
                </div>
            </div>

        </div>
    </section>
    <!-- ====== Info Survei & User End ====== -->

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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
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

        // Auto-hide success message after 30 seconds
        document.addEventListener('DOMContentLoaded', function() {
            const successMessage = document.getElementById('successMessage');
            if (successMessage) {
                setTimeout(function() {
                    successMessage.style.transition = 'opacity 0.5s ease-out';
                    successMessage.style.opacity = '0';
                    setTimeout(function() {
                        successMessage.remove();
                    }, 500);
                }, 30000); // 30 seconds
            }
        });

        // Start edit mode
        function startEdit() {
            const editButton = document.getElementById('editToggle');
            const actionButtons = document.getElementById('actionButtons');
            const viewModeElements = document.querySelectorAll('.view-mode');
            const editModeElements = document.querySelectorAll('.edit-mode');

            // Hide view text, show edit inputs
            viewModeElements.forEach(el => {
                el.classList.add('hidden');
                el.style.display = 'none';
            });
            editModeElements.forEach(el => {
                el.classList.remove('hidden');
                el.style.display = 'block';
            });

            // Hide the top Edit button while editing (so only ONE Batal button exists at the bottom)
            if (editButton) {
                editButton.classList.add('hidden');
                editButton.style.display = 'none';
            }

            // Show bottom action buttons (Simpan Perubahan & Batal)
            if (actionButtons) {
                actionButtons.classList.remove('hidden');
                actionButtons.style.display = 'flex';
            }

            // Refresh Select2 dropdowns
            setTimeout(function() {
                $('.user-searchable-select').each(function() {
                    if (!$(this).hasClass('select2-hidden-accessible')) {
                        $(this).select2({
                            placeholder: 'Ketik untuk mencari...',
                            allowClear: true,
                            width: '100%'
                        });
                    }
                });
            }, 50);
        }

        // Cancel editing and revert to view mode
        function cancelEdit() {
            const editButton = document.getElementById('editToggle');
            const actionButtons = document.getElementById('actionButtons');
            const viewModeElements = document.querySelectorAll('.view-mode');
            const editModeElements = document.querySelectorAll('.edit-mode');

            // Reset form to original values
            const form = document.getElementById('userInfoForm');
            if (form) {
                form.reset();
            }

            // Reset Select2 dropdowns to their original values
            $('.user-searchable-select').each(function() {
                var originalValue = $(this).find('option[selected]').val() || '';
                $(this).val(originalValue).trigger('change');
            });

            // Show view text, hide edit inputs
            viewModeElements.forEach(el => {
                el.classList.remove('hidden');
                el.style.display = '';
            });
            editModeElements.forEach(el => {
                el.classList.add('hidden');
                el.style.display = 'none';
            });

            // Hide bottom action buttons
            if (actionButtons) {
                actionButtons.classList.add('hidden');
                actionButtons.style.display = 'none';
            }

            // Show top Edit button again
            if (editButton) {
                editButton.classList.remove('hidden');
                editButton.style.display = 'inline-flex';
            }
        }

        // Alias toggleEdit to startEdit
        function toggleEdit() {
            startEdit();
        }

        @if ($errors->any())
            document.addEventListener('DOMContentLoaded', function() {
                startEdit();
            });
        @endif

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

        // Initialize Select2 for user-side searchable dropdowns
        $(document).ready(function() {
            function initSelect2() {
                $('.user-searchable-select').each(function() {
                    if (!$(this).hasClass('select2-hidden-accessible')) {
                        $(this).select2({
                            placeholder: 'Ketik untuk mencari...',
                            allowClear: true,
                            width: '100%'
                        });
                    }
                });
            }

            // Initialize on page load (for when edit mode is toggled)
            initSelect2();

            // Re-initialize when edit mode is toggled (since hidden elements may need refresh)
            const observer = new MutationObserver(function(mutations) {
                mutations.forEach(function(mutation) {
                    if (mutation.type === 'attributes' && mutation.attributeName === 'class') {
                        const target = mutation.target;
                        if (target.classList.contains('edit-mode') && !target.classList.contains('hidden')) {
                            setTimeout(initSelect2, 50);
                        }
                    }
                });
            });

            document.querySelectorAll('.edit-mode').forEach(function(el) {
                observer.observe(el, { attributes: true });
            });
        });
    </script>
</body>

</html>

