<!-- YouTube-style Admin Sidenav -->
<aside
  class="admin-sidenav fixed inset-y-0 left-0 flex flex-col w-64 p-0 m-0 overflow-hidden antialiased transition-transform duration-200 -translate-x-full border-0 z-990 lg:translate-x-0"
  aria-expanded="false">
  
  <!-- Brand Header (YouTube style top bar) -->
  <div class="flex items-center justify-between h-16 px-4 shrink-0 border-b border-slate-200/60 dark:border-slate-800/80" style="border-bottom-color: var(--admin-border) !important;">
    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 no-underline">
      <img src="{{ asset('assets/images/logo/logo.png') }}"
        class="h-8 w-8 object-contain transition-all duration-200 ease-nav-brand"
        alt="Tracer Study Logo" />
      <div class="flex flex-col">
        <div class="flex items-center gap-1.5">
          <span class="text-sm font-bold tracking-tight admin-text-primary leading-tight">{{ __('Tracer Study') }}</span>
          <span class="text-[10px] font-semibold text-blue-600 dark:text-blue-400 bg-blue-500/10 dark:bg-blue-400/15 px-1.5 py-0.5 rounded leading-none">Admin</span>
        </div>
        <span class="text-[11px] text-slate-400 dark:text-slate-500 font-medium leading-tight">Politeknik Statistika STIS</span>
      </div>
    </a>
    <!-- Close button for mobile screen -->
    <button type="button" class="p-2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 lg:hidden rounded-lg focus:outline-none" sidenav-close aria-label="Close sidebar">
      <i class="fas fa-times text-base"></i>
    </button>
  </div>

  <!-- Navigation list (Scrollable) -->
  <div class="flex-1 py-3 overflow-y-auto">
    <!-- Menu Utama -->
    <div class="px-1">
      <a href="{{ route('admin.dashboard') }}" class="sidebar-nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
        <span class="sidebar-nav-icon">
          <i class="ni ni-tv-2 text-emerald-500"></i>
        </span>
        <span class="truncate">{{ __('Dashboard') }}</span>
      </a>

      <a href="{{ route('admin.monitoring.index') }}" class="sidebar-nav-item {{ request()->routeIs('admin.monitoring.*') ? 'active' : '' }}">
        <span class="sidebar-nav-icon">
          <i class="ni ni-world-2 text-red-500"></i>
        </span>
        <span class="truncate">{{ __('Monitoring') }}</span>
      </a>
    </div>

    <!-- Divider & Section: Tracer Study -->
    <div class="sidebar-section-divider"></div>
    <div class="sidebar-section-title">{{ __('Tracer Study') }}</div>
    <div class="px-1">
      <a href="{{ route('admin.survey.index') }}" class="sidebar-nav-item {{ request()->routeIs('admin.survey.*') ? 'active' : '' }}">
        <span class="sidebar-nav-icon">
          <i class="ni ni-calendar-grid-58 text-orange-500"></i>
        </span>
        <span class="truncate">{{ __('Manajemen Survei') }}</span>
      </a>

      <a href="{{ route('admin.manajemenSatker.index') }}" class="sidebar-nav-item {{ request()->routeIs('admin.manajemenSatker.*') ? 'active' : '' }}">
        <span class="sidebar-nav-icon">
          <i class="ni ni-building text-cyan-500"></i>
        </span>
        <span class="truncate">{{ __('Manajemen Satker') }}</span>
      </a>

      <a href="{{ route('admin.lulusan.index') }}" class="sidebar-nav-item {{ request()->routeIs('admin.lulusan.*') ? 'active' : '' }}">
        <span class="sidebar-nav-icon">
          <i class="fas fa-user-graduate text-emerald-500"></i>
        </span>
        <span class="truncate">{{ __('Manajemen Lulusan') }}</span>
      </a>

      <a href="{{ route('admin.penggunaLulusan.index') }}" class="sidebar-nav-item {{ request()->routeIs('admin.penggunaLulusan.*') ? 'active' : '' }}">
        <span class="sidebar-nav-icon">
          <i class="fas fa-briefcase text-teal-500"></i>
        </span>
        <span class="truncate">{{ __('Manajemen Pengguna Lulusan') }}</span>
      </a>
    </div>

    <!-- Divider & Section: Pengaturan -->
    <div class="sidebar-section-divider"></div>
    <div class="sidebar-section-title">{{ __('Pengaturan') }}</div>
    <div class="px-1">
      <a href="{{ route('admin.template_email.index') }}" class="sidebar-nav-item {{ request()->routeIs('admin.template_email.*') ? 'active' : '' }}">
        <span class="sidebar-nav-icon">
          <i class="ni ni-email-83 text-blue-500"></i>
        </span>
        <span class="truncate">{{ __('Template Email') }}</span>
      </a>

      <a href="{{ route('admin.profile.edit') }}" class="sidebar-nav-item {{ request()->routeIs('admin.profile.edit') ? 'active' : '' }}">
        <span class="sidebar-nav-icon">
          <i class="ni ni-single-02 text-purple-500"></i>
        </span>
        <span class="truncate">{{ __('Manajemen Profil Admin') }}</span>
      </a>
    </div>
  </div>
</aside>
<!-- end sidenav -->