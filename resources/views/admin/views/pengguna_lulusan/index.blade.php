@extends('admin.layouts.app')
@section('title', 'Manajemen Pengguna Lulusan')

@section('content')

    <!-- table 1 -->
    <div class="flex flex-wrap -mx-3">
        <div class="flex-none w-full max-w-full px-3 min-w-0">
            <div class="font-bold">
                @if(session('success'))
                <div class="bg-green-100 border-t-4 border-green-500 rounded-b text-green-900 px-4 py-3 shadow-md alert alert-success mb-6" role="alert">
                    <div class="flex">
                        <div class="py-1">
                            <svg class="fill-current h-6 w-6 text-green-500 mr-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path d="M10 0C4.48 0 0 4.48 0 10s4.48 10 10 10 10-4.48 10-10S15.52 0 10 0zm5 7.5l-6.25 6.25-3.75-3.75 1.41-1.41 2.34 2.34 4.84-4.84L15 7.5z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="font-bold text-sm">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>

                @elseif(session('error'))
                    <div class="bg-red-100 border-t-4 border-red-500 rounded-b text-red-900 px-4 py-3 shadow-md alert alert-danger mb-6" role="alert">
                        <div class="flex">
                            <div class="py-1">
                                <svg class="fill-current h-6 w-6 text-red-500 mr-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path d="M2.93 17.07A10 10 0 1 1 17.07 2.93 10 10 0 0 1 2.93 17.07zm12.73-1.41A8 8 0 1 0 4.34 4.34a8 8 0 0 0 11.32 11.32zM9 11V9h2v6H9v-4zm0-6h2v2H9V5z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="font-bold text-sm">{{ session('error') }}</p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <div class="relative flex flex-col min-w-0 w-full max-w-full mb-6 break-words bg-white border-0 border-transparent border-solid shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border overflow-hidden">

                <!-- HEADER: Stack di mobile, Row di desktop -->
                <div class="p-4 pb-0 mb-0 border-b-0 border-b-solid rounded-t-2xl border-b-transparent flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <h6 class="dark:text-white text-sm font-bold">Daftar User Pengguna Lulusan</h6>
                    
                    <!-- Search Bar + Filter -->
                    <div class="flex items-center gap-2 w-full md:w-auto">
                        <form action="{{ route('admin.penggunaLulusan.index') }}" method="GET" class="flex items-center gap-2 w-full md:w-auto">
                            <div class="relative flex items-stretch flex-1 md:flex-none">
                                <span class="text-sm ease leading-5.6 absolute z-50 flex h-full items-center whitespace-nowrap rounded-lg rounded-tr-none rounded-br-none border border-r-0 border-transparent bg-transparent py-2 px-2.5 text-center font-normal text-slate-500 transition-all">
                                    <i class="fas fa-search"></i>
                                </span>
                                <input type="text" name="search" value="{{ request('search') }}"
                                    class="pl-9 w-full text-xs focus:shadow-primary-outline ease leading-5.6 relative block min-w-0 flex-auto rounded-lg border border-solid border-gray-300 dark:bg-slate-850 dark:text-white bg-white bg-clip-padding py-1.5 pr-3 text-gray-700 transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none focus:transition-shadow"
                                    placeholder="Search nama atau NIP..." />
                            </div>
                            <select name="status_data" onchange="this.form.submit()"
                                class="text-xs rounded-lg border border-solid border-gray-300 dark:bg-slate-850 dark:text-white bg-white py-1.5 px-2 text-gray-700 focus:border-blue-500 focus:outline-none">
                                <option value="">Semua Status</option>
                                <option value="lengkap" {{ request('status_data') == 'lengkap' ? 'selected' : '' }}>Data Lengkap</option>
                                <option value="tidak_lengkap" {{ request('status_data') == 'tidak_lengkap' ? 'selected' : '' }}>Data Tidak Lengkap</option>
                            </select>
                            @if(request('search') || request('status_data'))
                                <a href="{{ route('admin.penggunaLulusan.index') }}" class="text-gray-500 hover:text-gray-700">
                                    <i class="fas fa-times"></i>
                                </a>
                            @endif
                        </form>
                    </div>
                </div>

                <!-- BUTTONS -->
                @if(!auth()->user()->hasRole('supervisor'))
                <div class="px-4 py-2 mb-0 border-b-0 border-b-solid rounded-t-2xl border-b-transparent flex flex-wrap items-center gap-2">

                    <a href="{{ route('admin.penggunaLulusan.create') }}" class="w-full sm:w-auto">
                        <button type="button" class="inline-flex justify-center items-center w-full px-4 py-2 font-bold text-white transition-all ease-in bg-blue-500 border-0 rounded-lg shadow-md cursor-pointer text-xs tracking-tight-rem hover:shadow-xs hover:-translate-y-px active:opacity-85">
                            <i class="fas fa-plus mr-2"></i> Tambah Manual
                        </button>
                    </a>

                    <button type="button" id="openModal" class="inline-flex justify-center items-center w-full sm:w-auto px-4 py-2 font-bold text-white transition-all ease-in bg-blue-500 border-0 rounded-lg shadow-md cursor-pointer text-xs tracking-tight-rem hover:shadow-xs hover:-translate-y-px active:opacity-85">
                        <i class="fas fa-file-upload mr-2"></i> Import Data
                    </button>

                    <!-- pop up modal import  -->
                    <form action="{{ route('admin.penggunaLulusan.import') }}" method="POST" enctype="multipart/form-data" id="importForm" class="contents">
                        @csrf
                        <div id="uploadModal" class="fixed inset-0 z-50 flex items-center justify-center hidden bg-gray-800 bg-opacity-50 p-4">
                            <div class="bg-white rounded-lg shadow-lg w-full max-w-sm">
                                <div class="flex items-center justify-between p-4 border-b">
                                    <h3 class="text-lg font-bold">Upload File</h3>
                                    <button type="button" id="closeModal" class="text-gray-500 hover:text-gray-700 text-2xl">&times;</button>
                                </div>
                                <div class="p-4">
                                    <label for="fileInput" class="block text-sm font-medium text-gray-700 mb-2">Choose Excel File</label>
                                    <input type="file" id="fileInput" name="file" accept=".xls,.xlsx" class="block w-full text-sm text-gray-700 border rounded-lg cursor-pointer focus:ring-blue-500 focus:border-blue-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                    <p class="mt-2 text-xs text-gray-500">Only .xls and .xlsx files.</p>
                                    <div class="mt-4 flex justify-end gap-2">
                                        <button type="button" id="cancelUpload" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300">Cancel</button>
                                        <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-500 rounded-lg hover:bg-blue-600">Upload</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>

                    <a href="{{ route('admin.penggunaLulusan.export') }}" class="w-full sm:w-auto">
                        <button type="button" class="inline-flex justify-center items-center w-full px-4 py-2 font-bold text-white transition-all ease-in bg-green-500 border-0 rounded-lg shadow-md cursor-pointer text-xs tracking-tight-rem hover:shadow-xs hover:-translate-y-px active:opacity-85">
                            <i class="fas fa-file-excel mr-2"></i> Export Data
                        </button>
                    </a>

                    <a href="{{ route('admin.penggunaLulusan.template') }}" class="w-full sm:w-auto">
                        <button type="button" class="inline-flex justify-center items-center w-full px-4 py-2 font-bold text-white transition-all ease-in bg-green-500 border-0 rounded-lg shadow-md cursor-pointer text-xs tracking-tight-rem hover:shadow-xs hover:-translate-y-px active:opacity-85">
                            <i class="fas fa-download mr-2"></i> Template Excel
                        </button>
                    </a>

                </div>
                @endif

                <div class="flex-auto px-0 pt-0 pb-2 min-w-0 w-full max-w-full">
                    <div class="p-0 min-w-0 w-full max-w-full">
                        <!-- TABEL DENGAN SCROLL HORIZONTAL -->
                        <div class="overflow-x-auto w-full max-w-full min-w-0 block">
                            <table class="items-center w-full mb-0 align-top border-collapse dark:border-white/40 text-slate-500 min-w-[900px]"> <!-- min-w-[900px] agar tabel bisa di-scroll -->
                                <thead class="align-bottom">
                                    <tr>
                                        <!-- Kolom 1: Sticky Kiri -->
                                        <th class="sticky left-0 z-10 bg-white dark:bg-slate-850 px-3 py-2 font-bold text-left uppercase align-middle border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                            Nama</th>
                                        
                                        <th class="px-3 py-2 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                            NIP</th>
                                        <th class="px-3 py-2 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                            Email</th>
                                        <th class="px-3 py-2 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                            Jabatan</th>
                                        <th class="px-3 py-2 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                            Satuan Kerja</th>
                                        <th class="px-3 py-2 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                            Unit Kerja</th>
                                        <th class="px-3 py-2 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                            No HP</th>
                                        <th class="px-3 py-2 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                            Status Data</th>
                                        <th class="px-3 py-2 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                            Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($dataPenggunaLulusan as $penggunaLulusan)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-slate-700">
                                            <!-- Kolom 1: Sticky Kiri -->
                                            <td class="sticky left-0 z-10 bg-white dark:bg-slate-850 px-3 py-1.5 align-middle border-b dark:border-white/40 shadow-transparent">
                                                <h6 class="mb-0 text-xs leading-normal dark:text-white font-semibold whitespace-nowrap">{{ $penggunaLulusan->nama }}</h6>
                                            </td>
                                            <td class="px-3 py-1.5 text-center align-middle bg-transparent border-b dark:border-white/40 shadow-transparent">
                                                <div class="flex flex-col text-xs leading-tight text-slate-400 dark:text-white dark:opacity-80 whitespace-nowrap">
                                                    <span>Baru: {{ $penggunaLulusan->nip_baru ?? '-' }}</span>
                                                    <span>Lama: {{ $penggunaLulusan->nip_lama ?? '-' }}</span>
                                                </div>
                                            </td>
                                            <td class="px-3 py-1.5 text-center align-middle bg-transparent border-b dark:border-white/40 shadow-transparent">
                                                <span class="text-xs leading-tight dark:text-white dark:opacity-80 text-slate-400 whitespace-nowrap">{{ $penggunaLulusan->email }}</span>
                                            </td>
                                            <td class="px-3 py-1.5 text-center align-middle bg-transparent border-b dark:border-white/40 shadow-transparent">
                                                <span class="text-xs leading-tight dark:text-white dark:opacity-80 text-slate-400 whitespace-nowrap">{{ $penggunaLulusan->jabatan }}</span>
                                            </td>
                                            <td class="px-3 py-1.5 text-center align-middle bg-transparent border-b dark:border-white/40 shadow-transparent">
                                                <span class="text-xs leading-tight dark:text-white dark:opacity-80 text-slate-400 whitespace-nowrap">{{ $penggunaLulusan->satuan_kerja }}</span>
                                            </td>
                                            <td class="px-3 py-1.5 text-center align-middle bg-transparent border-b dark:border-white/40 shadow-transparent">
                                                <span class="text-xs leading-tight dark:text-white dark:opacity-80 text-slate-400 whitespace-nowrap">{{ $penggunaLulusan->unit_kerja }}</span>
                                            </td>
                                            <td class="px-3 py-1.5 text-center align-middle bg-transparent border-b dark:border-white/40 shadow-transparent">
                                                <span class="text-xs leading-tight dark:text-white dark:opacity-80 text-slate-400 whitespace-nowrap">{{ $penggunaLulusan->no_hp }}</span>
                                            </td>
                                            <td class="px-3 py-1.5 text-center align-middle bg-transparent border-b dark:border-white/40 shadow-transparent">
                                                @if($penggunaLulusan->status_data === 'Data Lengkap')
                                                    <span class="bg-gradient-to-tl from-emerald-500 to-teal-400 px-2.5 text-xs rounded-1.8 py-1.4 inline-block whitespace-nowrap text-center align-baseline font-bold uppercase leading-none text-white">Lengkap</span>
                                                @else
                                                    <span class="bg-gradient-to-tl from-red-600 to-red-400 px-2.5 text-xs rounded-1.8 py-1.4 inline-block whitespace-nowrap text-center align-baseline font-bold uppercase leading-none text-white">Tidak Lengkap</span>
                                                @endif
                                            </td>
                                            <td class="px-3 py-1.5 text-center align-middle bg-transparent border-b dark:border-white/40 shadow-transparent">
                                                <div class="flex items-center justify-center gap-2">
                                                    @if(!auth()->user()->hasRole('supervisor'))
                                                    <a href="{{ route('admin.penggunaLulusan.edit', $penggunaLulusan) }}" class="icon-link" data-tooltip="Edit">
                                                        <i class="fas fa-edit text-xs"></i>
                                                    </a>
                                                    <a href="javascript:;" class="icon-link" data-tooltip="Delete" onclick="event.preventDefault(); document.getElementById('delete-form-{{ $penggunaLulusan->id }}').submit();">
                                                        <i class="fas fa-trash text-xs"></i>
                                                    </a>
                                                    <form id="delete-form-{{ $penggunaLulusan->id }}" action="{{ route('admin.penggunaLulusan.destroy', $penggunaLulusan) }}" method="POST" style="display: none;">
                                                        @csrf
                                                        @method('DELETE')
                                                    </form>
                                                    @endif
                                                    <a href="{{ route('admin.penggunaLulusan.details', $penggunaLulusan) }}" class="icon-link" data-tooltip="Details">
                                                        <i class="fas fa-info-circle text-xs"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="p-3 border-t dark:border-white/10">
                            {{ $dataPenggunaLulusan->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- js pop up modal import -->
    <script>
        const openModal = document.getElementById('openModal');
        const closeModal = document.getElementById('closeModal');
        const cancelUpload = document.getElementById('cancelUpload');
        const uploadModal = document.getElementById('uploadModal');

        openModal.addEventListener('click', () => {
            uploadModal.classList.remove('hidden');
        });

        closeModal.addEventListener('click', () => {
            uploadModal.classList.add('hidden');
        });

        cancelUpload.addEventListener('click', () => {
            uploadModal.classList.add('hidden');
        });

        // Close on outside click
        uploadModal.addEventListener('click', (e) => {
            if(e.target === uploadModal) {
                uploadModal.classList.add('hidden');
            }
        });

        // Submit form logic
        document.getElementById('importForm').addEventListener('submit', (e) => {
            // Biarkan form submit secara normal atau tambahkan logic AJAX di sini
            // e.preventDefault(); 
            // alert('File uploaded successfully!');
            // uploadModal.classList.add('hidden');
        });
    </script>

    <script>
    setTimeout(() => {
        document.querySelectorAll('.alert').forEach(el => {
            el.classList.add('opacity-0', 'transition-opacity', 'duration-500');
            setTimeout(() => el.remove(), 500);
        });
    }, 3000);
    </script>

@endsection