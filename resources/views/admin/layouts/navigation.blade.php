<!-- Navbar -->
<nav class="admin-navbar flex flex-wrap items-center justify-between transition-all ease-in shadow-none duration-200 lg:flex-nowrap lg:justify-start" navbar-main navbar-scroll="false">
        <div class="flex items-center justify-between w-full mx-auto flex-wrap-inherit">
          <nav>
            <!-- breadcrumb -->
            <ol class="flex flex-wrap pt-1 mr-8 bg-transparent rounded-lg sm:mr-16">
              <li class="text-sm leading-normal">
              <a class="admin-text-secondary opacity-80" href="javascript:;">Home</a>
              </li>
              <li class="text-sm pl-2 capitalize leading-normal admin-text-secondary before:float-left before:pr-2 before:content-['/']" aria-current="page">@yield('title')</li>
            </ol>
            <h6 class="mb-0 font-bold capitalize admin-text-primary">@yield('title')</h6>
          </nav>

          <div class="flex items-center mt-2 grow sm:mt-0 sm:mr-0 lg:flex lg:basis-auto">
            <div class="flex items-center md:ml-auto md:pr-4">
              <div class="relative flex flex-wrap items-stretch w-full transition-all rounded-lg ease">
                <span class="text-sm ease leading-5.6 absolute z-50 -ml-px flex h-full items-center whitespace-nowrap rounded-lg rounded-tr-none rounded-br-none border border-r-0 border-transparent bg-transparent py-2 px-2.5 text-center font-normal admin-text-secondary transition-all">

                </span>
              </div>
            </div>
            <ul class="flex flex-row items-center justify-end gap-4 pl-0 mb-0 list-none md-max:w-full">

              <form method="POST" action="{{ route('logout') }}">
                  @csrf
                  <li class="flex items-center">
                    <a :href="route('logout')" onclick="event.preventDefault();this.closest('form').submit();"class="block px-3 py-2 text-sm font-semibold transition-all ease-nav-brand cursor-pointer rounded-lg admin-text-primary admin-hover-surface-soft whitespace-nowrap">
                      <i class="fa fa-user sm:mr-1"></i>
                      <span class="hidden sm:inline"> {{ __('Log Out') }}</span>
                    </a>
                  </li>
              </form>
              <li class="flex items-center lg:hidden">
                <a href="javascript:;" class="block p-0 text-sm transition-all ease-nav-brand admin-text-primary" sidenav-trigger>
                  <div class="w-4.5 overflow-hidden">
                    <i class="ease mb-0.75 relative block h-0.5 rounded-sm bg-slate-500 dark:bg-white transition-all"></i>
                    <i class="ease mb-0.75 relative block h-0.5 rounded-sm bg-slate-500 dark:bg-white transition-all"></i>
                    <i class="ease relative block h-0.5 rounded-sm bg-slate-500 dark:bg-white transition-all"></i>
                  </div>
                </a>
              </li>
              <li class="flex items-center">
                <button id="theme-toggle" type="button" class="p-2 text-sm transition-all ease-nav-brand admin-text-primary rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 focus:outline-none flex items-center justify-center">
                  <i id="theme-toggle-dark-icon" class="hidden fa-solid fa-moon text-lg"></i>
                  <i id="theme-toggle-light-icon" class="hidden fa-solid fa-sun text-lg"></i>
                </button>
              </li>
            </ul>
          </div>
        </div>
      </nav>

      <!-- end Navbar -->

