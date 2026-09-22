@if ($errors->any())
    <div class="mb-6 alert alert-danger alert-with-icon" data-notify="container">
        <span data-notify="icon" class="fas fa-times-circle"></span>
        <div data-notify="message" class="text-sm font-semibold">
            <div><strong>Perhatian!</strong> Terjadi kesalahan saat memproses data:</div>
            <ul class="mt-1.5 ml-4 list-disc list-outside text-xs font-normal space-y-0.5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endif

@if (session('success'))
    <div class="mb-6 alert alert-success alert-with-icon" data-notify="container">
        <span data-notify="icon" class="fas fa-check-circle"></span>
        <span data-notify="message" class="text-sm font-semibold">{{ session('success') }}</span>
    </div>
@endif

@if (session('error'))
    <div class="mb-6 alert alert-danger alert-with-icon" data-notify="container">
        <span data-notify="icon" class="fas fa-times-circle"></span>
        <span data-notify="message" class="text-sm font-semibold">{{ session('error') }}</span>
    </div>
@endif

@if (session('warning'))
    <div class="mb-6 alert alert-warning alert-with-icon" data-notify="container">
        <span data-notify="icon" class="fas fa-exclamation-circle"></span>
        <span data-notify="message" class="text-sm font-semibold">{{ session('warning') }}</span>
    </div>
@endif

<style>
    .alert {
        padding: 0.75rem 1.5rem;
        margin-bottom: 1rem;
        border: 1px solid transparent;
        border-radius: 0.375rem;
        display: flex;
        align-items: flex-start;
        gap: 1rem;
    }

    .alert-success {
        background-color: #d4edda;
        border-color: #c3e6cb;
        color: #155724;
    }

    .alert-danger {
        background-color: #f8d7da;
        border-color: #f5c6cb;
        color: #721c24;
    }

    .alert-warning {
        background-color: #fff3cd;
        border-color: #ffeeba;
        color: #856404;
    }

    .alert-success .fas { color: #28a745; }
    .alert-danger .fas { color: #dc3545; }
    .alert-warning .fas { color: #ffc107; }

    .dark-mode .alert-success {
        background-color: #1e4620;
        border-color: #2d5a2d;
        color: #a1d5a1;
    }

    .dark-mode .alert-danger {
        background-color: #5a2c2c;
        border-color: #7d3f3f;
        color: #f8a9a9;
    }

    .dark-mode .alert-warning {
        background-color: #5a4a00;
        border-color: #7d6600;
        color: #ffd700;
    }
</style>
