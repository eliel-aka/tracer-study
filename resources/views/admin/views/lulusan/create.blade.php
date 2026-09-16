@extends('admin.layouts.app')
@section('title', 'Create Lulusan')

@section('content')

<!-- table 1 -->
<form action="{{ route('admin.lulusan.store') }}" method="POST">
    @csrf

    <div class="flex flex-wrap -mx-3">
        <div class="flex-none w-full max-w-full px-3">
            <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
                
                <div class="flex flex-wrap -mx-3">

                    <!-- form start -->
                    <div class="flex-auto p-6">
                        <p class="leading-normal uppercase dark:text-white dark:opacity-60 text-sm text-center">
                            Informasi Lulusan
                        </p>

                        <hr class="h-px mx-0 my-4 bg-transparent border-0 opacity-25 
                            bg-gradient-to-r from-transparent via-black/40 to-transparent 
                            dark:bg-gradient-to-r dark:from-transparent dark:via-white dark:to-transparent" />

                        <div class="flex flex-wrap -mx-3">

                            <!-- Nama -->
                            <div class="w-full px-3 mb-4">
                                <label for="nama" class="inline-block mb-2 ml-1 font-bold text-xs text-slate-700 dark:text-white/80">
                                    Nama <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="nama" value="{{ old('nama') }}"
                                    class="focus:shadow-primary-outline dark:bg-slate-850 dark:text-white text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none" />
                                @error('nama')
                                    <span class="text-red-500 text-xs">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- NIP Baru -->
                            <div class="w-full px-3 mb-4">
                                <label for="nip_baru" class="inline-block mb-2 ml-1 font-bold text-xs text-slate-700 dark:text-white/80">
                                    NIP Baru (18 Digit) <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="nip_baru" value="{{ old('nip_baru') }}" placeholder="Contoh: 199801012022011001" maxlength="18"
                                    class="focus:shadow-primary-outline dark:bg-slate-850 dark:text-white text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none" />
                                @error('nip_baru')
                                    <span class="text-red-500 text-xs">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- NIP Lama -->
                            <div class="w-full px-3 mb-4">
                                <label for="nip_lama" class="inline-block mb-2 ml-1 font-bold text-xs text-slate-700 dark:text-white/80">
                                    NIP Lama (9 Digit) <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="nip_lama" value="{{ old('nip_lama') }}" placeholder="Contoh: 123456789" maxlength="9"
                                    class="focus:shadow-primary-outline dark:bg-slate-850 dark:text-white text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none" />
                                @error('nip_lama')
                                    <span class="text-red-500 text-xs">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div class="w-full px-3 mb-4">
                                <label for="email" class="inline-block mb-2 ml-1 font-bold text-xs text-slate-700 dark:text-white/80">
                                    Email <span class="text-red-500">*</span>
                                </label>
                                <input type="email" name="email" value="{{ old('email') }}"
                                    class="focus:shadow-primary-outline dark:bg-slate-850 dark:text-white text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none" />
                                @error('email')
                                    <span class="text-red-500 text-xs">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Prodi -->
                            <div class="w-full px-3 mb-4">
                                <label for="prodi" class="inline-block mb-2 ml-1 font-bold text-xs text-slate-700 dark:text-white/80">
                                    Program Studi
                                </label>
                                <select name="prodi"
                                    class="focus:shadow-primary-outline dark:bg-slate-850 dark:text-white text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all focus:border-blue-500 focus:outline-none">
                                    <option value="">-- Pilih Program Studi --</option>
                                    <option value="D-IV Komputasi Statistik" {{ old('prodi') == 'D-IV Komputasi Statistik' ? 'selected' : '' }}>
                                        D-IV Komputasi Statistik
                                    </option>
                                    <option value="D-IV Statistika" {{ old('prodi') == 'D-IV Statistika' ? 'selected' : '' }}>
                                        D-IV Statistika
                                    </option>
                                    <option value="D-III Statistika" {{ old('prodi') == 'D-III Statistika' ? 'selected' : '' }}>
                                        D-III Statistika
                                    </option>
                                </select>
                                @error('prodi')
                                    <span class="text-red-500 text-xs">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Jabatan -->
                            <div class="w-full px-3 mb-4">
                                <label for="jabatan" class="inline-block mb-2 ml-1 font-bold text-xs text-slate-700 dark:text-white/80">
                                    Jabatan
                                </label>
                                <select name="jabatan" id="jabatan" class="searchable-select w-full">
                                    <option value="">-- Pilih Jabatan --</option>
                                    @foreach($masterJabatan as $j)
                                        <option value="{{ $j->nama }}" {{ old('jabatan') == $j->nama ? 'selected' : '' }}>{{ $j->nama }}</option>
                                    @endforeach
                                </select>
                                @error('jabatan')
                                    <span class="text-red-500 text-xs">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Satuan Kerja -->
                            <div class="w-full px-3 mb-4">
                                <label for="satuan_kerja" class="inline-block mb-2 ml-1 font-bold text-xs text-slate-700 dark:text-white/80">
                                    Satuan Kerja
                                </label>
                                <select name="satuan_kerja" id="satuan_kerja" class="searchable-select w-full">
                                    <option value="">-- Pilih Satuan Kerja --</option>
                                    @foreach($masterSatuanKerja as $sk)
                                        <option value="{{ $sk->nama }}" {{ old('satuan_kerja') == $sk->nama ? 'selected' : '' }}>{{ $sk->nama }}</option>
                                    @endforeach
                                </select>
                                @error('satuan_kerja')
                                    <span class="text-red-500 text-xs">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Unit Kerja -->
                            <div class="w-full px-3 mb-4">
                                <label for="unit_kerja" class="inline-block mb-2 ml-1 font-bold text-xs text-slate-700 dark:text-white/80">
                                    Unit Kerja
                                </label>
                                <select name="unit_kerja" id="unit_kerja" class="searchable-select w-full">
                                    <option value="">-- Pilih Unit Kerja --</option>
                                    @foreach($masterUnitKerja as $uk)
                                        <option value="{{ $uk->nama }}" {{ old('unit_kerja') == $uk->nama ? 'selected' : '' }}>{{ $uk->nama }}</option>
                                    @endforeach
                                </select>
                                @error('unit_kerja')
                                    <span class="text-red-500 text-xs">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- No HP -->
                            <div class="w-full px-3 mb-4">
                                <label for="no_hp" class="inline-block mb-2 ml-1 font-bold text-xs text-slate-700 dark:text-white/80">
                                    No HP
                                </label>
                                <input type="text" name="no_hp" value="{{ old('no_hp') }}"
                                    class="focus:shadow-primary-outline dark:bg-slate-850 dark:text-white text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none" />
                                @error('no_hp')
                                    <span class="text-red-500 text-xs">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Tanggal Lahir -->
                            <div class="w-full px-3 mb-4">
                                <label for="tanggal_lahir" class="inline-block mb-2 ml-1 font-bold text-xs text-slate-700 dark:text-white/80">
                                    Tanggal Lahir <span class="text-red-500">*</span>
                                </label>
                                <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}"
                                    class="focus:shadow-primary-outline dark:bg-slate-850 dark:text-white text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none" />
                                @error('tanggal_lahir')
                                    <span class="text-red-500 text-xs">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Tahun Lulus -->
                            <div class="w-full px-3 mb-4">
                                <label for="tahun_lulus" class="inline-block mb-2 ml-1 font-bold text-xs text-slate-700 dark:text-white/80">
                                    Tahun Lulus <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="tahun_lulus" value="{{ old('tahun_lulus') }}"
                                    class="focus:shadow-primary-outline dark:bg-slate-850 dark:text-white text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none" />
                                @error('tahun_lulus')
                                    <span class="text-red-500 text-xs">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- NIP Baru Pengguna -->
                            <div class="w-full px-3 mb-4">
                                <label for="nip_baru_pengguna_lulusan" class="inline-block mb-2 ml-1 font-bold text-xs text-slate-700 dark:text-white/80">
                                    NIP Baru Pengguna Lulusan (18 Digit) <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="nip_baru_pengguna_lulusan" value="{{ old('nip_baru_pengguna_lulusan') }}" placeholder="Contoh: 198001012005011001" maxlength="18"
                                    class="focus:shadow-primary-outline dark:bg-slate-850 dark:text-white text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none" />
                                @error('nip_baru_pengguna_lulusan')
                                    <span class="text-red-500 text-xs">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- NIP Lama Pengguna -->
                            <div class="w-full px-3 mb-4">
                                <label for="nip_lama_pengguna_lulusan" class="inline-block mb-2 ml-1 font-bold text-xs text-slate-700 dark:text-white/80">
                                    NIP Lama Pengguna Lulusan (9 Digit) <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="nip_lama_pengguna_lulusan" value="{{ old('nip_lama_pengguna_lulusan') }}" placeholder="Contoh: 123456789" maxlength="9"
                                    class="focus:shadow-primary-outline dark:bg-slate-850 dark:text-white text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none" />
                                @error('nip_lama_pengguna_lulusan')
                                    <span class="text-red-500 text-xs">{{ $message }}</span>
                                @enderror
                            </div>

                        </div>

                        <!-- Buttons -->
                        <div class="flex justify-end items-center mt-4">
                            <a href="{{ route('admin.lulusan.index') }}"
                                class="inline-flex items-center justify-center px-8 py-2 w-36 h-10 font-bold text-center align-middle transition-all ease-in border border-gray-300 rounded-lg text-gray-700 bg-transparent hover:bg-gray-100 text-xs tracking-tight-rem cursor-pointer mr-4">
                                Batal
                            </a>

                            <button type="submit"
                                class="inline-flex items-center justify-center px-8 py-2 w-36 h-10 font-bold text-center text-white align-middle transition-all ease-in bg-blue-500 border-0 rounded-lg shadow-md cursor-pointer text-xs tracking-tight-rem hover:shadow-xs hover:-translate-y-px active:opacity-85">
                                Simpan
                            </button>
                        </div>

                    </div>
                    <!-- form end -->

                </div>
            </div>
        </div>
    </div>

</form>

@push('styles')
<style>
    .select2-container--default .select2-selection--single {
        border: 1px solid #d1d5db;
        border-radius: 0.5rem;
        height: 38px;
        padding: 4px 8px;
        font-size: 0.875rem;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 36px;
    }
    .select2-container--default .select2-results__option--highlighted[aria-selected] {
        background-color: #3b82f6;
    }
    .select2-dropdown {
        border-radius: 0.5rem;
        border: 1px solid #d1d5db;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
    }
    .select2-search--dropdown .select2-search__field {
        border-radius: 0.375rem;
        border: 1px solid #d1d5db;
        padding: 6px 8px;
    }
</style>
@endpush

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        function limitSelect2Matcher(params, data) {
            // Abaikan opsi kosong / placeholder di daftar hasil
            if (!data || !data.id || data.id === '') {
                return null;
            }

            // Inisialisasi counter per pencarian/query
            if (params._matchCount === undefined) {
                params._matchCount = 0;
            }

            const term = (params.term || '').trim().toLowerCase();

            // Kondisi 1: Saat dropdown baru dibuka tanpa kata kunci pencarian
            if (term === '') {
                const $select = data.element ? $(data.element).closest('select') : null;
                const selectedValue = $select ? $select.val() : null;

                // Jika ada opsi yang sedang terpilih dan belum tercakup, simpan slot ke-5 untuknya
                if (selectedValue && String(data.id) !== String(selectedValue) && !params._selectedMatched) {
                    if (params._hasSelectedInOptions === undefined) {
                        params._hasSelectedInOptions = $select.find('option').filter(function() {
                            return this.value && String(this.value) === String(selectedValue);
                        }).length > 0;
                    }

                    if (params._hasSelectedInOptions && params._matchCount >= 4) {
                        return null;
                    }
                }

                if (params._matchCount < 5) {
                    params._matchCount++;
                    if (selectedValue && String(data.id) === String(selectedValue)) {
                        params._selectedMatched = true;
                    }
                    return data;
                }

                return null;
            }

            // Kondisi 2: Saat pengguna mengetik di kolom pencarian
            if (data.text.toLowerCase().indexOf(term) > -1) {
                if (params._matchCount < 5) {
                    params._matchCount++;
                    return data;
                }
            }

            return null;
        }

        $('.searchable-select').select2({
            placeholder: 'Ketik untuk mencari...',
            allowClear: true,
            width: '100%',
            matcher: limitSelect2Matcher
        });
    });
</script>
@endpush

@endsection