<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('assets/img/logo.png') }}" />
    <link rel="icon" type="image/png" href="{{ asset('assets/img/logo.png') }}" />
    <title>Tracer Study</title>

    <script>
        (function() {
            try {
                var key = 'admin-theme-config';
                var config = JSON.parse(localStorage.getItem(key) || '{}');
                if (config.darkMode === true || (!('darkMode' in config) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                    document.documentElement.classList.add('dark');
                } else {
                    document.documentElement.classList.remove('dark');
                }
            } catch (e) {
                // Ignore malformed values and continue with defaults.
            }
        })();
    </script>
    
    <!-- Fonts and icons FIRST -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
    
    <!-- Nucleo Icons -->
    <link href="{{ asset('assets/css/nucleo-icons.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/css/nucleo-svg.css') }}" rel="stylesheet" />
    
    <!-- Main Argon Styling BEFORE Vite -->
    <link href="{{ asset('assets/css/argon-dashboard-tailwind.css?v=1.0.1') }}" rel="stylesheet" />
    
    <!-- Vite Assets (Tailwind) AFTER main styling -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Other plugins -->
    <script src="https://unpkg.com/@popperjs/core@2"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
    
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @include('admin.layouts.theme')
    
    <!-- Custom Styles -->
    <style>
        @media (min-width: 1024px) {
            .admin-main-content {
                margin-left: 16rem;
                width: calc(100% - 16rem);
                max-width: calc(100% - 16rem);
            }
        }
    </style>
    @stack('styles')
</head>

<body
    class="admin-theme m-0 font-sans text-base antialiased font-normal dark:bg-slate-900 leading-default bg-gray-50 text-slate-500 overflow-x-hidden max-w-full w-full">
    <div class="absolute top-0 right-0 h-96 pointer-events-none admin-shell-gradient w-full lg:w-[calc(100%-16rem)]"></div>
    @include ('admin.layouts.sidebar')

    <main class="admin-main-content relative h-full max-h-screen transition-all duration-200 ease-in-out overflow-x-hidden max-w-full">
        @include('admin.layouts.navigation')
        <div class="admin-layout-space w-full mx-auto overflow-x-hidden max-w-full">
            @include('admin.components.alert')
            @yield('content')

            <footer class="pt-4">
                <div class="w-full px-6 mx-auto">
                    <div class="flex flex-wrap items-center -mx-3 lg:justify-center">
                        <div class="w-full max-w-full px-3 mt-0 mb-6 shrink-0 lg:mb-0 lg:w-1/2 lg:flex-none">
                            <div class="text-sm leading-normal text-center admin-text-secondary lg:text-left">
                                ©
                                <script>
                                    document.write(new Date().getFullYear() + ",");
                                </script>
                                made with <i class="fa fa-heart text-red-500"></i> by
                                <a href="https://www.creative-tim.com"
                                    class="font-semibold admin-text-primary" target="_blank">unit SPM</a>
                                , Politeknik Statistika STIS.
                            </div>
                        </div>

                    </div>
                </div>
            </footer>
        </div>
    </main>


</body>
<!-- plugin for charts  -->
<script src="{{ asset('assets/js/plugins/chartjs.min.js') }}" async></script>
<!-- plugin for scrollbar  -->
<script src="{{ asset('assets/js/plugins/perfect-scrollbar.min.js') }}" async></script>
<!-- main script file  -->
<script src="{{ asset('assets/js/argon-dashboard-tailwind.js?v=1.0.1') }}" async></script>

@stack('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Debug: Layout loaded');
        window.onerror = function(msg, url, lineNo, columnNo, error) {
            console.error('Error: ' + msg + '\nURL: ' + url + '\nLine: ' + lineNo);
            return false;
        };

        const themeToggleDarkIcon = document.getElementById('theme-toggle-dark-icon');
        const themeToggleLightIcon = document.getElementById('theme-toggle-light-icon');
        const themeToggleBtn = document.getElementById('theme-toggle');
        
        const key = 'admin-theme-config';

        function updateIcons() {
            if (!themeToggleLightIcon || !themeToggleDarkIcon) return;
            if (document.documentElement.classList.contains('dark')) {
                themeToggleLightIcon.classList.remove('hidden');
                themeToggleDarkIcon.classList.add('hidden');
            } else {
                themeToggleDarkIcon.classList.remove('hidden');
                themeToggleLightIcon.classList.add('hidden');
            }
        }

        updateIcons();

        if (themeToggleBtn) {
            themeToggleBtn.addEventListener('click', function() {
                document.documentElement.classList.toggle('dark');
                
                let config = {};
                try {
                    config = JSON.parse(localStorage.getItem(key) || '{}');
                } catch (e) {}
                
                config.darkMode = document.documentElement.classList.contains('dark');
                localStorage.setItem(key, JSON.stringify(config));
                
                updateIcons();
            });
        }
    });
</script>

</html>
