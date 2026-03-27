    @extends('admin.layouts.app')
    @section('title', 'Manajemen Survei')

    @section('content')
        <!-- table 1 -->
        <div class="flex flex-wrap -mx-3">
            <div class="flex-none w-full max-w-full px-3">
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

                <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">

                    <!-- HEADER: Stack di mobile, Row di desktop -->
                    <div class="p-4 pb-0 mb-0 border-b-0 border-b-solid rounded-t-2xl border-b-transparent flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <h6 class="dark:text-white text-sm font-bold">Daftar Survei</h6>
                    
                    <div class="flex flex-row items-center gap-2 w-full md:w-auto md:ml-auto">
                        
                        <div class="relative flex items-center flex-1 md:w-64">
                            <div class="relative flex items-stretch w-full">
                                <form action="{{ route('admin.survey.index') }}" method="GET" class="flex items-center w-full">
                                    <span class="text-sm ease leading-5.6 absolute z-50 flex h-full items-center whitespace-nowrap rounded-lg rounded-tr-none rounded-br-none border border-r-0 border-transparent bg-transparent py-2 px-2.5 text-center font-normal text-slate-500 transition-all">
                                        <i class="fas fa-search"></i>
                                    </span>
                                    <input type="text" name="search" value="{{ request('search') }}"
                                        class="pl-9 text-xs focus:shadow-primary-outline ease w-full leading-5.6 relative block min-w-0 flex-auto rounded-lg border border-solid border-gray-300 dark:bg-slate-850 dark:text-white bg-white bg-clip-padding py-1.5 pr-3 text-gray-700 transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none focus:transition-shadow"
                                        placeholder="Cari..." />
                                    @if(request('search'))
                                        <a href="{{ route('admin.survey.index') }}" class="absolute right-2 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700">
                                            <i class="fas fa-times"></i>
                                        </a>
                                    @endif
                                </form>
                            </div>
                        </div>

                        @if(!auth()->user()->hasRole('supervisor'))
                        <a href="{{ route('admin.survey.create') }}" class="block flex-shrink-0">
                            <button type="button" class="inline-block px-3 py-2 sm:px-6 font-bold leading-normal text-center text-white align-middle transition-all ease-in bg-blue-500 border-0 rounded-lg shadow-md cursor-pointer text-xs tracking-tight-rem hover:shadow-xs hover:-translate-y-px active:opacity-85 whitespace-nowrap">
                                <i class="fas fa-plus sm:mr-2"></i> <span class="hidden sm:inline">Tambah Survei</span><span class="sm:hidden">Tambah</span>
                            </button>
                        </a>
                        @endif
                        
                    </div>
                </div>

                    <div class="flex-auto px-0 pt-0 pb-2">
                        <div class="p-0">
                            <!-- TABEL DENGAN SCROLL HORIZONTAL -->
                            <div class="overflow-x-auto">
                                <table class="items-center w-full mb-0 align-top border-collapse dark:border-white/40 text-slate-500 min-w-[800px]"> <!-- min-w-[800px] memaksa scroll -->
                                    <thead class="align-bottom">
                                        <tr>
                                            <!-- Kolom 1: Sticky Kiri -->
                                            <th class="sticky left-0 z-10 bg-white dark:bg-slate-850 px-3 py-2 font-bold text-left uppercase align-middle border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                                Nama Survei</th>
                                            
                                            <th class="px-3 py-2 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                                Status</th>
                                            <th class="px-3 py-2 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                                Tanggal Aktif</th>
                                            <th class="px-3 py-2 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                                Tipe Survei</th>
                                            <th class="px-3 py-2 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                                Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($survey as $srvy)
                                            <tr class="hover:bg-gray-50 dark:hover:bg-slate-700">
                                                <!-- Kolom 1: Sticky Kiri -->
                                                <td class="sticky left-0 z-10 bg-white dark:bg-slate-850 p-1.5 align-middle border-b dark:border-white/40 shadow-transparent">
                                                    <div class="flex flex-col px-2 py-1">
                                                        <h6 class="mb-0 text-xs leading-normal dark:text-white font-semibold whitespace-nowrap">{{ $srvy->nama }}</h6>
                                                    </div>
                                                </td>

                                                <td class="p-1.5 text-sm leading-normal text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                                    <span class="bg-gradient-to-tl {{ $srvy->status == 'Aktif' ? 'from-emerald-500 to-teal-400' : 'from-slate-600 to-slate-300' }} px-2.5 text-xs rounded-1.8 py-1.4 inline-block whitespace-nowrap text-center align-baseline font-bold uppercase leading-none text-white">
                                                        {{ $srvy->status }}
                                                    </span>
                                                </td>
                                                <td class="p-1.5 text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                                    <span class="text-xs font-semibold leading-tight dark:text-white dark:opacity-80 text-slate-400">
                                                        {{ $srvy->tanggal_mulai . ' - ' . $srvy->tanggal_selesai }}
                                                    </span>
                                                </td>
                                                <td class="p-1.5 text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                                    <span class="text-xs font-semibold leading-tight dark:text-white dark:opacity-80 text-slate-400 capitalize">
                                                        {{ $srvy->type_survei == 'lulusan' ? 'Lulusan' : 'Pengguna Lulusan' }}
                                                    </span>
                                                </td>
                                                <td class="p-1.5 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                                    <div class="flex items-center justify-center gap-2">
                                                        @if(!auth()->user()->hasRole('supervisor'))
                                                            <!-- Edit -->
                                                            <a href="{{ route('admin.survey.edit', $srvy) }}" class="icon-link p-1 hover:bg-gray-100 dark:hover:bg-slate-600 rounded" data-tooltip="Edit">
                                                                <i class="fas fa-edit text-xs text-blue-500"></i>
                                                            </a>

                                                            <!-- Duplicate -->
                                                            <a href="javascript:;" class="icon-link p-1 hover:bg-gray-100 dark:hover:bg-slate-600 rounded" data-tooltip="Duplicate Survei" onclick="confirmDuplicate({{ $srvy->id }})">
                                                                <i class="fas fa-copy text-xs text-gray-500"></i>
                                                            </a>
                                                            <form id="duplicate-form-{{ $srvy->id }}" action="{{ route('admin.survey.duplicate', $srvy->id) }}" method="POST" style="display: none;">
                                                                @csrf
                                                                @method('POST')
                                                            </form>

                                                            <!-- Delete -->
                                                            <a href="javascript:;" class="icon-link p-1 hover:bg-gray-100 dark:hover:bg-slate-600 rounded" data-tooltip="Delete" onclick="confirmDelete({{ $srvy->id }})">
                                                                <i class="fas fa-trash text-xs text-red-500"></i>
                                                            </a>
                                                            <form id="delete-form-{{ $srvy->id }}" action="{{ route('admin.survey.destroy', $srvy) }}" method="POST" style="display: none;">
                                                                @csrf
                                                                @method('DELETE')
                                                            </form>
                                                        @endif

                                                        <!-- Details -->
                                                        <a href="{{ route('admin.survey.details', $srvy) }}" class="icon-link p-1 hover:bg-gray-100 dark:hover:bg-slate-600 rounded" data-tooltip="Details">
                                                            <i class="fas fa-info-circle text-xs text-slate-400"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            
                            <div class="p-4 border-t dark:border-white/10">
                                {{ $survey->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- JS Scripts -->
        <script>
            // Fungsi auto resize jika dibutuhkan
            document.addEventListener('input', function (e) {
                if (e.target.classList.contains('auto-resize')) {
                    e.target.style.height = 'auto';
                    e.target.style.height = (e.target.scrollHeight) + 'px';
                }
            });

            // Fungsi alert fade out
            setTimeout(() => {
                document.querySelectorAll('.alert').forEach(el => {
                    el.classList.add('opacity-0', 'transition-opacity', 'duration-500');
                    setTimeout(() => el.remove(), 500);
                });
            }, 3000);

            // Konfirmasi Hapus
            function confirmDelete(surveyId) {
                if (confirm('Apakah Anda yakin ingin menghapus survey ini? Semua data terkait termasuk pertanyaan, jawaban, dan responden akan ikut terhapus secara permanen.')) {
                    document.getElementById('delete-form-' + surveyId).submit();
                }
            }

            // Konfirmasi Duplikasi
            function confirmDuplicate(surveyId) {
                if (confirm('Apakah Anda yakin ingin menduplikasi survey ini? Survey baru akan dibuat dengan semua pertanyaan dan pengaturan yang sama.')) {
                    document.getElementById('duplicate-form-' + surveyId).submit();
                }
            }
        </script>
    @endsection