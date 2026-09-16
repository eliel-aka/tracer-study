<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('assets/img/logo.png') }}" />
    <link rel="icon" type="image/png" href="{{ asset('assets/img/logo.png') }}" />
    <title>@yield('title', 'Tracer Study')</title>

    <script>
        (function() {
            try {
                var key = 'admin-theme-config';
                var config = JSON.parse(localStorage.getItem(key) || '{}');
                if (config.darkMode === true) {
                    document.documentElement.classList.add('dark');
                }
            } catch (e) {
                // Ignore malformed values and continue with defaults.
            }
        })();
    </script>
    
    <!-- Fonts and icons -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
    <!-- Font Awesome Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
    
    <!-- Nucleo Icons -->
    <link href="{{ asset('assets/css/nucleo-icons.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/css/nucleo-svg.css') }}" rel="stylesheet" />
    
    <!-- Tailwind CSS -->
    <!-- <script src="https://cdn.tailwindcss.com"></script> -->
    
    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Main Styling -->
    <link href="{{ asset('assets/css/argon-dashboard-tailwind.css?v=1.0.1') }}" rel="stylesheet" />

    @include('admin.layouts.theme')
    
    <!-- Select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
    
    @stack('styles')
</head>

<body class="admin-theme m-0 font-sans text-base antialiased font-normal dark:bg-slate-900 leading-default bg-gray-50 text-slate-500">
    <div class="absolute top-0 right-0 h-96 pointer-events-none admin-shell-gradient dark:hidden w-full lg:w-[calc(100%-16rem)]"></div>
    
    <!-- Include Sidebar -->
    @include('admin.layouts.sidebar')

    <!-- Main Content -->
    <main class="admin-main-content relative h-full max-h-screen transition-all duration-200 ease-in-out overflow-x-hidden max-w-full">
        <!-- Include Navigation -->
        @include('admin.layouts.navigation')
        
        <!-- Page Content -->
        <div class="admin-layout-space w-full mx-auto overflow-x-hidden max-w-full">
            @yield('content')
            
            <!-- Footer -->
            <footer class="pt-4">
                <div class="w-full px-6 mx-auto">
                    <div class="flex flex-wrap items-center -mx-3 lg:justify-between">
                        <div class="w-full max-w-full px-3 mt-0 mb-6 shrink-0 lg:mb-0 lg:w-1/2 lg:flex-none">
                            <div class="text-sm leading-normal text-center admin-text-secondary lg:text-left">
                                ©
                                <script>
                                    document.write(new Date().getFullYear() + ",");
                                </script>
                                made with <i class="fa fa-heart"></i> by
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

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<!-- Plugin for charts -->
<script src="{{ asset('assets/js/plugins/chartjs.min.js') }}" async></script>
<!-- Plugin for scrollbar -->
<script src="{{ asset('assets/js/plugins/perfect-scrollbar.min.js') }}" async></script>
<!-- Main script file -->
<script src="{{ asset('assets/js/argon-dashboard-tailwind.js?v=1.0.1') }}" async></script>

<!-- Custom Scripts Stack -->
@stack('scripts')

<script>
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Debug: Main layout loaded');
        
        // Setup CSRF token for AJAX requests
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        
        // Error handler
        window.onerror = function(msg, url, lineNo, columnNo, error) {
            console.error('Error: ' + msg + '\nURL: ' + url + '\nLine: ' + lineNo);
            return false;
        };
    });
</script>

</html>
