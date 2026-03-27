<!-- sidenav  -->
<aside
  class="fixed inset-y-0 flex-wrap items-center justify-between block w-full p-0 my-4 overflow-y-auto antialiased transition-transform duration-200 -translate-x-full bg-white border-0 shadow-xl dark:shadow-none dark:bg-slate-850 max-w-64 ease-nav-brand z-990 lg:ml-6 rounded-2xl lg:left-0 lg:translate-x-0"
  aria-expanded="false">
  <div class="h-19">
    <i class="absolute top-0 right-0 p-4 opacity-50 cursor-pointer fas fa-times dark:text-white text-slate-400 lg:hidden"
      sidenav-close></i>
    <a class="block px-8 py-6 m-0 text-sm whitespace-nowrap dark:text-white text-slate-700" href="" target="_blank">
      <img src="{{ asset('assets/images/logo/logo.png') }}"
        class="inline h-full max-w-full transition-all duration-200 dark:hidden ease-nav-brand max-h-8"
        alt="main_logo" />
      <img src="{{ asset('assets/images/logo/logo.png') }}"
        class="hidden h-full max-w-full transition-all duration-200 dark:inline ease-nav-brand max-h-8"
        alt="main_logo" />
      <span class="ml-1 font-semibold transition-all duration-200 ease-nav-brand">{{ __('Tracer Study') }}</span>
    </a>
  </div>

  <div class="items-center block w-auto h-auto grow basis-full">
    <ul class="flex flex-col pl-0 mb-0 gap-y-0.5">

      <hr class="h-px my-1 mx-4 bg-transparent bg-gradient-to-r from-transparent via-black/40 to-transparent dark:via-white/30" />

      <li class="w-full">
        <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'bg-blue-500/13' : '' }} py-2 dark:text-white dark:opacity-80 text-sm ease-nav-brand my-0 mx-2 flex items-center whitespace-nowrap rounded-lg px-3 font-semibold text-slate-700 transition-colors">
          <div class="mr-2 flex h-7 w-7 shrink-0 items-center justify-center rounded-lg bg-center fill-current stroke-0 text-center">
            <i class="text-sm leading-normal text-emerald-500 ni ni-tv-2"></i>
          </div>
          <span class="duration-300 opacity-100 pointer-events-none ease truncate">{{ __('Dashboard') }}</span>
        </a>
      </li>

      <hr class="h-px my-1 mx-4 bg-transparent bg-gradient-to-r from-transparent via-black/40 to-transparent dark:via-white/30" />

      <li class="w-full">
        <a href="{{ route('admin.monitoring.index') }}" class="{{ request()->routeIs('admin.monitoring.*') ? 'bg-blue-500/13' : '' }} py-2 dark:text-white dark:opacity-80 text-sm ease-nav-brand my-0 mx-2 flex items-center whitespace-nowrap rounded-lg px-3 font-semibold text-slate-700 transition-colors">
          <div class="mr-2 flex h-7 w-7 shrink-0 items-center justify-center rounded-lg bg-center stroke-0 text-center">
            <i class="text-sm leading-normal text-red-600 ni ni-world-2"></i>
          </div>
          <span class="duration-300 opacity-100 pointer-events-none ease truncate">{{ __('Monitoring') }}</span>
        </a>
      </li>

      <hr class="h-px my-1 mx-4 bg-transparent bg-gradient-to-r from-transparent via-black/40 to-transparent dark:via-white/30" />

      <li class="w-full">
        <a href="{{ route('admin.survey.index') }}" class="{{ request()->routeIs('admin.survey.*') ? 'bg-blue-500/13' : '' }} py-2 dark:text-white dark:opacity-80 text-sm ease-nav-brand my-0 mx-2 flex items-center whitespace-nowrap rounded-lg px-3 font-semibold text-slate-700 transition-colors">
          <div class="mr-2 flex h-7 w-7 shrink-0 items-center justify-center rounded-lg bg-center stroke-0 text-center">
            <i class="text-sm leading-normal text-orange-500 ni ni-calendar-grid-58"></i>
          </div>
          <span class="duration-300 opacity-100 pointer-events-none ease truncate">{{ __('Manajemen Survei') }}</span>
        </a>
      </li>

      <hr class="h-px my-1 mx-4 bg-transparent bg-gradient-to-r from-transparent via-black/40 to-transparent dark:via-white/30" />

      <li class="w-full">
        <a href="{{ route('admin.template_email.index') }}" class="{{ request()->routeIs('admin.template_email.*') ? 'bg-blue-500/13' : '' }} py-2 dark:text-white dark:opacity-80 text-sm ease-nav-brand my-0 mx-2 flex items-center whitespace-nowrap rounded-lg px-3 font-semibold text-slate-700 transition-colors">
          <div class="mr-2 flex h-7 w-7 shrink-0 items-center justify-center rounded-lg bg-center stroke-0 text-center">
            <i class="text-sm leading-normal text-blue-500 ni ni-email-83"></i>
          </div>
          <span class="duration-300 opacity-100 pointer-events-none ease truncate">{{ __('Template Email') }}</span>
        </a>
      </li>

      <hr class="h-px my-1 mx-4 bg-transparent bg-gradient-to-r from-transparent via-black/40 to-transparent dark:via-white/30" />

      <li class="w-full">
        <a href="{{ route('admin.lulusan.index') }}" class="{{ request()->routeIs('admin.lulusan.*') ? 'bg-blue-500/13' : '' }} py-2 dark:text-white dark:opacity-80 text-sm ease-nav-brand my-0 mx-2 flex items-center whitespace-nowrap rounded-lg px-3 font-semibold text-slate-700 transition-colors">
          <div class="mr-2 flex h-7 w-7 shrink-0 items-center justify-center rounded-lg bg-center fill-current stroke-0 text-center">
            <i class="text-sm leading-normal text-emerald-500 ni ni-credit-card"></i>
          </div>
          <span class="duration-300 opacity-100 pointer-events-none ease truncate">{{ __('Manajemen Lulusan') }}</span>
        </a>
      </li>

      <hr class="h-px my-1 mx-4 bg-transparent bg-gradient-to-r from-transparent via-black/40 to-transparent dark:via-white/30" />

      <li class="w-full">
        <a href="{{ route('admin.pengguna_lulusan.index') }}" class="{{ request()->routeIs('admin.pengguna_lulusan.*') ? 'bg-blue-500/13' : '' }} py-2 dark:text-white dark:opacity-80 text-sm ease-nav-brand my-0 mx-2 flex items-center whitespace-nowrap rounded-lg px-3 font-semibold text-slate-700 transition-colors">
          <div class="mr-2 flex h-7 w-7 shrink-0 items-center justify-center rounded-lg bg-center fill-current stroke-0 text-center">
            <i class="text-sm leading-normal text-emerald-500 ni ni-credit-card"></i>
          </div>
          <span class="duration-300 opacity-100 pointer-events-none ease truncate">{{ __('Manajemen Pengguna Lulusan') }}</span>
        </a>
      </li>

      <hr class="h-px my-1 mx-4 bg-transparent bg-gradient-to-r from-transparent via-black/40 to-transparent dark:via-white/30" />

      <li class="w-full">
        <a href="{{ route('admin.profile.edit') }}" class="{{ request()->routeIs('admin.profile.edit') ? 'bg-blue-500/13' : '' }} py-2 dark:text-white dark:opacity-80 text-sm ease-nav-brand my-0 mx-2 flex items-center whitespace-nowrap rounded-lg px-3 font-semibold text-slate-700 transition-colors">
          <div class="mr-2 flex h-7 w-7 shrink-0 items-center justify-center rounded-lg bg-center stroke-0 text-center">
            <i class="text-sm leading-normal text-purple-500 ni ni-single-02"></i>
          </div>
          <span class="duration-300 opacity-100 pointer-events-none ease truncate">{{ __('Manajemen Profil Admin') }}</span>
        </a>
      </li>

      <hr class="h-px my-1 mx-4 bg-transparent bg-gradient-to-r from-transparent via-black/40 to-transparent dark:via-white/30" />

    </ul>
  </div>
</aside>
<!-- end sidenav -->