@extends('admin.layouts.app')
@section('title', 'Manajemen Satker')

@push('styles')
<style>
    .tab-btn {
        transition: all 0.3s ease;
    }
    .tab-btn.active {
        background: linear-gradient(135deg, #3b82f6, #2563eb);
        color: white;
        box-shadow: 0 4px 15px rgba(59, 130, 246, 0.35);
    }
    .tab-btn:not(.active):hover {
        background: #f1f5f9;
    }
    .tab-content {
        display: none;
        animation: fadeIn 0.3s ease-in-out;
    }
    .tab-content.active {
        display: block;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(8px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .modal-backdrop {
        background: rgba(15, 23, 42, 0.6);
        backdrop-filter: blur(4px);
    }
    .modal-content {
        animation: modalIn 0.25s ease-out;
    }
    @keyframes modalIn {
        from { opacity: 0; transform: scale(0.95) translateY(10px); }
        to { opacity: 1; transform: scale(1) translateY(0); }
    }
</style>
@endpush

@section('content')

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

                <!-- HEADER -->
                <div class="p-4 pb-0 mb-0 border-b-0 border-b-solid rounded-t-2xl border-b-transparent flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <h6 class="dark:text-white text-sm font-bold">Master Data Satker</h6>
                        <p class="text-xs text-slate-400 mt-1">Kelola master data Jabatan, Satuan Kerja, dan Unit Kerja</p>
                    </div>
                    <div class="w-full md:w-64">
                        <form action="{{ route('admin.manajemenSatker.index') }}" method="GET" class="relative flex w-full flex-wrap items-stretch">
                            <input type="hidden" name="tab" value="{{ $activeTab }}">
                            <span class="z-30 flex items-center justify-center w-8 text-center bg-transparent border-0 text-slate-400 absolute h-full px-2">
                                <i class="fas fa-search text-xs"></i>
                            </span>
                            <input type="text" name="search" value="{{ request('search') }}"
                                class="pl-9 w-full text-xs focus:shadow-primary-outline ease leading-5.6 relative block min-w-0 flex-auto rounded-lg border border-solid border-gray-300 dark:bg-slate-850 dark:text-white bg-white bg-clip-padding py-2 pr-3 text-gray-700 transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none focus:transition-shadow"
                                placeholder="Cari data..." />
                            @if(request('search'))
                                <a href="{{ route('admin.manajemenSatker.index', ['tab' => $activeTab]) }}" class="absolute right-0 z-30 flex items-center justify-center w-8 h-full px-2 text-slate-400 hover:text-slate-600">
                                    <i class="fas fa-times text-xs"></i>
                                </a>
                            @endif
                        </form>
                    </div>
                </div>

                <!-- TABS -->
                <div class="px-4 pt-4">
                    <div class="flex flex-wrap gap-2">
                        <button type="button" class="tab-btn px-5 py-2 rounded-lg text-xs font-bold transition-all {{ $activeTab == 'jabatan' ? 'active' : 'text-slate-600 dark:text-white' }}" data-tab="jabatan">
                            <i class="fas fa-id-badge mr-1.5"></i> Jabatan
                        </button>
                        <button type="button" class="tab-btn px-5 py-2 rounded-lg text-xs font-bold transition-all {{ $activeTab == 'satuan_kerja' ? 'active' : 'text-slate-600 dark:text-white' }}" data-tab="satuan_kerja">
                            <i class="fas fa-building mr-1.5"></i> Satuan Kerja
                        </button>
                        <button type="button" class="tab-btn px-5 py-2 rounded-lg text-xs font-bold transition-all {{ $activeTab == 'unit_kerja' ? 'active' : 'text-slate-600 dark:text-white' }}" data-tab="unit_kerja">
                            <i class="fas fa-sitemap mr-1.5"></i> Unit Kerja
                        </button>
                    </div>
                </div>

                <!-- TAB CONTENT: JABATAN -->
                <div class="tab-content {{ $activeTab == 'jabatan' ? 'active' : '' }}" id="tab-jabatan">
                    @include('admin.views.manajemen_satker._tab_content', [
                        'type' => 'jabatan',
                        'label' => 'Jabatan',
                        'items' => $jabatan,
                    ])
                </div>

                <!-- TAB CONTENT: SATUAN KERJA -->
                <div class="tab-content {{ $activeTab == 'satuan_kerja' ? 'active' : '' }}" id="tab-satuan_kerja">
                    @include('admin.views.manajemen_satker._tab_content', [
                        'type' => 'satuan_kerja',
                        'label' => 'Satuan Kerja',
                        'items' => $satuanKerja,
                    ])
                </div>

                <!-- TAB CONTENT: UNIT KERJA -->
                <div class="tab-content {{ $activeTab == 'unit_kerja' ? 'active' : '' }}" id="tab-unit_kerja">
                    @include('admin.views.manajemen_satker._tab_content', [
                        'type' => 'unit_kerja',
                        'label' => 'Unit Kerja',
                        'items' => $unitKerja,
                    ])
                </div>

            </div>
        </div>
    </div>

    <!-- MODAL: CREATE -->
    <div id="createModal" class="fixed inset-0 z-50 hidden flex items-center justify-center modal-backdrop p-4">
        <div class="modal-content bg-white dark:bg-slate-800 rounded-2xl shadow-2xl w-full max-w-md">
            <form id="createForm" method="POST" action="{{ route('admin.manajemenSatker.store') }}">
                @csrf
                <input type="hidden" name="type" id="createType" value="jabatan">
                <div class="flex items-center justify-between p-5 border-b dark:border-slate-700">
                    <h3 class="text-sm font-bold dark:text-white">Tambah <span id="createLabel">Jabatan</span></h3>
                    <button type="button" onclick="closeCreateModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-white text-xl">&times;</button>
                </div>
                <div class="p-5">
                    <label class="block mb-2 text-xs font-bold text-slate-700 dark:text-white/80">Nama <span class="text-red-500">*</span></label>
                    <input type="text" name="nama" required placeholder="Masukkan nama..."
                        class="focus:shadow-primary-outline dark:bg-slate-850 dark:text-white text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none" />
                </div>
                <div class="flex justify-end gap-2 p-5 pt-0">
                    <button type="button" onclick="closeCreateModal()" class="px-4 py-2 text-xs font-bold text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300">Batal</button>
                    <button type="submit" class="px-4 py-2 text-xs font-bold text-white bg-blue-500 rounded-lg shadow-md hover:shadow-lg hover:-translate-y-px active:opacity-85 transition-all">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL: EDIT -->
    <div id="editModal" class="fixed inset-0 z-50 hidden flex items-center justify-center modal-backdrop p-4">
        <div class="modal-content bg-white dark:bg-slate-800 rounded-2xl shadow-2xl w-full max-w-md">
            <form id="editForm" method="POST" action="">
                @csrf
                @method('PUT')
                <input type="hidden" name="type" id="editType" value="">
                <div class="flex items-center justify-between p-5 border-b dark:border-slate-700">
                    <h3 class="text-sm font-bold dark:text-white">Edit <span id="editLabel"></span></h3>
                    <button type="button" onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-white text-xl">&times;</button>
                </div>
                <div class="p-5">
                    <label class="block mb-2 text-xs font-bold text-slate-700 dark:text-white/80">Nama <span class="text-red-500">*</span></label>
                    <input type="text" name="nama" id="editNama" required
                        class="focus:shadow-primary-outline dark:bg-slate-850 dark:text-white text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none" />
                </div>
                <div class="flex justify-end gap-2 p-5 pt-0">
                    <button type="button" onclick="closeEditModal()" class="px-4 py-2 text-xs font-bold text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300">Batal</button>
                    <button type="submit" class="px-4 py-2 text-xs font-bold text-white bg-blue-500 rounded-lg shadow-md hover:shadow-lg hover:-translate-y-px active:opacity-85 transition-all">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL: IMPORT -->
    <div id="importModal" class="fixed inset-0 z-50 hidden flex items-center justify-center modal-backdrop p-4">
        <div class="modal-content bg-white dark:bg-slate-800 rounded-2xl shadow-2xl w-full max-w-md">
            <form id="importForm" method="POST" action="{{ route('admin.manajemenSatker.import') }}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="type" id="importType" value="jabatan">
                <div class="flex items-center justify-between p-5 border-b dark:border-slate-700">
                    <h3 class="text-sm font-bold dark:text-white">Import <span id="importLabel">Jabatan</span></h3>
                    <button type="button" onclick="closeImportModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-white text-xl">&times;</button>
                </div>
                <div class="p-5">
                    <label class="block mb-2 text-xs font-bold text-slate-700 dark:text-white/80">Pilih File Excel</label>
                    <input type="file" name="file" accept=".xls,.xlsx,.csv" required
                        class="block w-full text-sm text-gray-700 border rounded-lg cursor-pointer focus:ring-blue-500 focus:border-blue-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    <p class="mt-2 text-xs text-gray-500">Format: .xls, .xlsx, .csv — Kolom: <strong>nama</strong></p>
                </div>
                <div class="flex justify-end gap-2 p-5 pt-0">
                    <button type="button" onclick="closeImportModal()" class="px-4 py-2 text-xs font-bold text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300">Batal</button>
                    <button type="submit" class="px-4 py-2 text-xs font-bold text-white bg-blue-500 rounded-lg shadow-md hover:shadow-lg hover:-translate-y-px active:opacity-85 transition-all">Upload</button>
                </div>
            </form>
        </div>
    </div>

@endsection

@push('scripts')
<script>
    // Tab switching
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const tab = this.dataset.tab;
            
            document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            
            document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
            document.getElementById('tab-' + tab).classList.add('active');

            // Update URL without reload
            const url = new URL(window.location);
            url.searchParams.set('tab', tab);
            window.history.replaceState({}, '', url);
        });
    });

    // Create Modal
    function openCreateModal(type, label) {
        document.getElementById('createType').value = type;
        document.getElementById('createLabel').textContent = label;
        document.getElementById('createModal').classList.remove('hidden');
    }
    function closeCreateModal() {
        document.getElementById('createModal').classList.add('hidden');
    }

    // Edit Modal
    function openEditModal(id, type, label, nama) {
        document.getElementById('editType').value = type;
        document.getElementById('editLabel').textContent = label;
        document.getElementById('editNama').value = nama;
        document.getElementById('editForm').action = '{{ route("admin.manajemenSatker.update", ":id") }}'.replace(':id', id);
        document.getElementById('editModal').classList.remove('hidden');
    }
    function closeEditModal() {
        document.getElementById('editModal').classList.add('hidden');
    }

    // Import Modal
    function openImportModal(type, label) {
        document.getElementById('importType').value = type;
        document.getElementById('importLabel').textContent = label;
        document.getElementById('importModal').classList.remove('hidden');
    }
    function closeImportModal() {
        document.getElementById('importModal').classList.add('hidden');
    }

    // Close modals on backdrop click
    ['createModal', 'editModal', 'importModal'].forEach(id => {
        document.getElementById(id).addEventListener('click', function(e) {
            if (e.target === this) {
                this.classList.add('hidden');
            }
        });
    });

    // Auto-dismiss alerts
    setTimeout(() => {
        document.querySelectorAll('.alert').forEach(el => {
            el.classList.add('opacity-0', 'transition-opacity', 'duration-500');
            setTimeout(() => el.remove(), 500);
        });
    }, 3000);
</script>
@endpush
