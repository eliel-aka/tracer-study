@extends('admin.layouts.app')
@section('title', 'Edit Survey')

@push('styles')
<style>
    .section-block {
        border: 2px solid #e5e7eb;
        border-radius: 12px;
        background: #f8fafc;
        transition: all 0.3s ease;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .section-block:hover {
        border-color: #3b82f6;
        background-color: #f1f5f9;
        box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    }

    /* Options styling with navigation */
    .option-item {
        position: relative;
        margin-bottom: 12px;
    }

    .option-item .bg-blue-50 {
        border-left: 3px solid #3b82f6;
        transition: all 0.2s ease;
    }

    .option-item .bg-blue-50:hover {
        background-color: #eff6ff;
        border-left-color: #1d4ed8;
    }

    .option-navigation-select {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e");
        background-position: right 0.5rem center;
        background-repeat: no-repeat;
        background-size: 1.5em 1.5em;
        padding-right: 2.5rem;
    }

    /* Navigation toggle styling */
    .navigation-toggle-label {
        transition: all 0.2s ease;
    }

    .navigation-toggle-label:hover {
        color: #3b82f6;
    }

    /* Navigation block animation */
    .navigation-block {
        transition: all 0.3s ease;
    }

    .navigation-block.hidden {
        opacity: 0;
        max-height: 0;
        overflow: hidden;
        margin: 0;
        padding: 0;
    }

    .navigation-block.block {
        opacity: 1;
        max-height: 200px;
    }

    /* Enhanced Question Item Styling with Better Separation */
    .question-item {
        border: 2px solid #e5e7eb;
        border-radius: 12px;
        background: white;
        box-shadow: 0 2px 6px rgba(0,0,0,0.08);
        margin-bottom: 20px;
        padding: 20px;
        position: relative;
        transition: all 0.3s ease;
    }

    /* Section Block Spacing */
    .section-block {
        margin-bottom: 30px;
    }
    .question-item:hover {
        border-color: #3b82f6;
        box-shadow: 0 4px 12px rgba(0,0,0,0.12);
        transform: translateY(-2px);
    }

    /* Question Number Badge */
    .question-item::before {
        content: attr(data-question-number);
        position: absolute;
        top: -10px;
        left: 20px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: bold;
        box-shadow: 0 2px 4px rgba(0,0,0,0.2);
        z-index: 10;
    }

    /* Question Separator Line */
    .question-item:not(:last-child)::after {
        content: '';
        position: absolute;
        bottom: -10px;
        left: 50%;
        transform: translateX(-50%);
        width: 60%;
        height: 2px;
        background: linear-gradient(90deg, transparent, #e5e7eb, transparent);
    }

    /* Questions Container Spacing */
    .questions-container {
        padding: 20px 0;
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        border-radius: 12px;
        margin-top: 16px;
    }

    /* Add Block Button Styling */
    .add-block-section {
        background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
        border-radius: 12px;
        padding: 20px;
        margin-top: 16px;
        border: 1px dashed #64748b;
        transition: all 0.3s ease;
    }
    .add-block-section:hover {
        border-color: #10b981;
        background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
        transform: translateY(-1px);
    }

    /* Option Controls Styling */
    .option-controls {
        display: flex;
        flex-direction: column;
        gap: 2px;
        margin-left: 8px;
    }
    .option-btn {
        padding: 4px 6px;
        border-radius: 4px;
        border: 1px solid #e5e7eb;
        background: white;
        color: #6b7280;
        cursor: pointer;
        transition: all 0.2s ease;
        font-size: 10px;
        line-height: 1;
        min-width: 24px;
        height: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .option-btn:hover {
        background: #f3f4f6;
        color: #374151;
        border-color: #d1d5db;
        transform: translateY(-1px);
    }
    .option-btn:disabled {
        opacity: 0.3;
        cursor: not-allowed;
        transform: none;
    }
    .option-btn.up:hover:not(:disabled) {
        background: #dbeafe;
        color: #1d4ed8;
        border-color: #3b82f6;
    }
    .option-btn.down:hover:not(:disabled) {
        background: #fef3c7;
        color: #d97706;
        border-color: #f59e0b;
    }

    /* Option Item Styling */
    .option-item {
        background: #f9fafb;
        padding: 8px;
        border-radius: 8px;
        border: 1px solid #f3f4f6;
        transition: all 0.2s ease;
    }
    .option-item:hover {
        background: #f3f4f6;
        border-color: #e5e7eb;
    }

    .icon-container {
        display: flex;
        gap: 8px;
        align-items: center;
    }
    .icon-link {
        padding: 8px;
        border-radius: 6px;
        color: #6b7280;
        transition: all 0.2s;
        cursor: pointer;
        background: white;
        border: 1px solid #e5e7eb;
    }
    .icon-link:hover {
        background-color: #f3f4f6;
        color: #374151;
        border-color: #d1d5db;
        transform: translateY(-1px);
    }
    .block-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 12px 16px;
        border-radius: 8px 8px 0 0;
        margin: -24px -24px 16px -24px;
    }
    .question-header {
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        padding: 12px 16px;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
        margin-bottom: 16px;
        position: relative;
    }
    .form-section {
        background: white;
        border-radius: 0 0 8px 8px;
        padding: 16px;
    }
    .required-asterisk {
        color: #ef4444;
        font-weight: bold;
    }
    .error-field {
        border-color: #ef4444 !important;
        background-color: #fef2f2 !important;
    }
    .valid-field {
        border-color: #10b981 !important;
        background-color: #f0fdf4 !important;
    }

    /* Block color variations - Even/Odd only */
    .block-color-even .block-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
        color: white !important;
    }
    .block-color-odd .block-header {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%) !important;
        color: white !important;
    }

    .block-color-even {
        border-left: 4px solid #667eea !important;
    }
    .block-color-odd {
        border-left: 4px solid #4facfe !important;
    }
</style>
@endpush

@section('content')
<div class="flex flex-wrap -mx-3">
    <div class="flex-none w-full max-w-full px-3">
        <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
            <div class="p-6 pb-0 mb-0 border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                <div class="flex flex-wrap -mx-3">
                    <div class="flex items-center w-full max-w-full px-3 shrink-0 md:w-8/12 md:flex-0">
                        <h6 class="mb-0 dark:text-white">Edit Survey</h6>
                    </div>
                </div>
            </div>

            <div class="flex-auto p-6">
                <form id="editSurveyForm" action="{{ route('admin.survey.update', $survey->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Basic Survey Information -->
                    <div class="bg-gray-50 p-6 rounded-lg mb-6">
                        <h3 class="text-lg font-semibold mb-4">Informasi Survey</h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="nama" class="block text-sm font-medium text-gray-700 mb-2">Nama Survey <span class="text-red-500">*</span></label>
                                <input type="text" id="nama" name="nama" value="{{ $survey->nama }}" required
                                       class="focus:shadow-primary-outline text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none" />
                                @error('nama')
                                    <span class="text-red-500 text-xs">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label for="type_survei" class="block text-sm font-medium text-gray-700 mb-2">Tipe Survey <span class="text-red-500">*</span></label>
                                <select id="type_survei" name="type_survei" required
                                        class="focus:shadow-primary-outline text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none">
                                    <option value="">Pilih Tipe Survey</option>
                                    <option value="lulusan" {{ $survey->type_survei == 'lulusan' ? 'selected' : '' }}>Lulusan</option>
                                    <option value="penggunaLulusan" {{ in_array($survey->type_survei, ['penggunaLulusan', 'pengguna_lulusan'], true) ? 'selected' : '' }}>Pengguna Lulusan</option>
                                </select>
                                @error('type_survei')
                                    <span class="text-red-500 text-xs">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="tanggal_mulai" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Mulai <span class="text-red-500">*</span></label>
                                <input type="date" id="tanggal_mulai" name="tanggal_mulai" value="{{ $survey->tanggal_mulai }}" required
                                       class="focus:shadow-primary-outline text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none" />
                                @error('tanggal_mulai')
                                    <span class="text-red-500 text-xs">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label for="tanggal_selesai" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Selesai <span class="text-red-500">*</span></label>
                                <input type="date" id="tanggal_selesai" name="tanggal_selesai" value="{{ $survey->tanggal_selesai }}" required
                                       class="focus:shadow-primary-outline text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none" />
                                @error('tanggal_selesai')
                                    <span class="text-red-500 text-xs">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-2">Deskripsi Survey</label>
                            <textarea id="deskripsi" name="deskripsi" rows="3" placeholder="Deskripsi survey (opsional)"
                                      class="focus:shadow-primary-outline text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none">{{ $survey->deskripsi }}</textarea>
                            @error('deskripsi')
                                <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Form Builder Section -->
                    <div class="bg-white border rounded-lg p-6">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-lg font-semibold">Form Builder</h3>
                            <button type="button" id="addSectionBtn" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition-colors">
                                <i class="fas fa-plus mr-2"></i>Tambah Block
                            </button>
                        </div>

                        <div id="sectionsContainer">
                            <!-- Sections will be populated by JavaScript -->
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex justify-end items-center mt-6 space-x-4">
                        <a href="{{ route('admin.survey.index') }}" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                            Batal
                        </a>
                        <button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded-lg hover:bg-blue-600 transition-colors">
                            <i class="fas fa-save mr-2"></i>Update Survey
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
let sectionCounter = 0;
let questionCounter = 0;

// Load existing form builder data
const formBuilderData = @json($formBuilderData ?? []);

document.addEventListener('DOMContentLoaded', function() {
    // Load existing sections if available
    if (Object.keys(formBuilderData).length > 0) {
        loadExistingSections();
    } else {
        // Add first section by default if no existing data
        addSection();
    }

    // Add section button event
    document.getElementById('addSectionBtn').addEventListener('click', addSection);

    // Update navigation options initially
    setTimeout(() => updateNavigationOptions(), 100);

    // Add form validation before submit
    const form = document.querySelector('#editSurveyForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault(); // Always prevent default to handle submission manually

            // Normalize dynamic indexes to avoid timestamp-based keys (e.g. questions[1775...])
            // that can create confusing backend validation paths.
            normalizeFormIndexesBeforeSubmit();

            if (!validateForm()) {
                return false;
            }

            // Show loading state
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Mengupdate...';

            // Submit form using fetch API
            const formData = new FormData(form);

            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                return response.json().then(data => ({
                    status: response.status,
                    ok: response.ok,
                    data: data
                }));
            })
            .then(({status, ok, data}) => {
                if (ok && data.success !== false) {
                    // Success - show message and redirect
                    showSuccessMessage('Survey berhasil diupdate!');
                    setTimeout(() => {
                        window.location.href = "{{ route('admin.survey.index') }}";
                    }, 1500);
                } else {
                    // Error response with JSON data
                    if (status === 419 || (data && data.message && data.message.includes('CSRF token mismatch'))) {
                        throw new Error('Sesi Anda telah berakhir (CSRF token expired). Silakan refresh halaman (tekan F5) lalu coba simpan kembali.');
                    }
                    if (status === 422 && data.errors) {
                        throw new Error(buildFriendlyValidationMessage(data.errors));
                    }

                    throw new Error(data.message || 'Terjadi kesalahan saat mengupdate survey');
                }
            })
            .catch(error => {
                console.error('Error:', error);

                // Show specific error message
                let errorMessage = 'Terjadi kesalahan saat mengupdate survey.';
                if (error.message && error.message !== 'Terjadi kesalahan saat mengupdate survey') {
                    errorMessage = error.message;
                }

                showErrorMessage(errorMessage);

                // Reset button state
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            });
        });
    }
});

// Load existing sections from backend data
function loadExistingSections() {
    Object.keys(formBuilderData).forEach((sectionId, sectionIdx) => {
        const sectionData = formBuilderData[sectionId];
        sectionCounter++;
        const isIdentity = (sectionCounter === 1) || (sectionIdx === 0) || (sectionData.metadata && sectionData.metadata.is_identity_block);

        const colorClass = (sectionCounter % 2 === 0) ? 'block-color-even' : 'block-color-odd';

        const sectionHtml = `
            <div class="section-block p-6 ${colorClass} ${isIdentity ? 'border-2 border-blue-400' : ''}" data-section-id="${sectionCounter}" data-is-identity="${isIdentity ? 'true' : 'false'}">
                <div class="block-header" ${isIdentity ? 'style="background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%) !important;"' : ''}>
                    <div class="flex justify-between items-center">
                        <div class="flex items-center gap-2">
                            <h4 class="block-title font-semibold text-white">${isIdentity ? `Block ${sectionCounter}: Identitas Responden` : `Block ${sectionCounter}`}</h4>
                            ${isIdentity ? '<span class="bg-amber-400 text-amber-950 text-xs px-2.5 py-0.5 rounded-full font-bold shadow-sm"><i class="fas fa-lock mr-1"></i>Terkunci Otomatis</span>' : ''}
                        </div>
                        <div class="icon-container">
                            ${isIdentity ? `
                                <span class="text-xs text-blue-100 italic">
                                    <i class="fas fa-shield-alt mr-1"></i> Data profil terisi otomatis untuk responden
                                </span>
                            ` : `
                                <button type="button" onclick="cloneSection(${sectionCounter})" class="icon-link" title="Clone Block">
                                    <i class="fas fa-copy"></i>
                                </button>
                                <button type="button" onclick="deleteSection(${sectionCounter})" class="icon-link" title="Delete Block">
                                    <i class="fas fa-trash"></i>
                                </button>
                            `}
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <!-- Block Info -->
                    <input type="hidden" name="sections[${sectionCounter}][id]" value="${sectionData.id || ''}">
                    ${isIdentity ? `
                        <div class="bg-blue-50 border border-blue-200 p-4 rounded-lg mb-4">
                            <div class="flex items-start gap-3">
                                <i class="fas fa-user-check text-blue-600 text-xl mt-0.5"></i>
                                <div>
                                    <h5 class="text-sm font-bold text-blue-900 mb-1">Blok Identitas Responden</h5>
                                    <p class="text-xs text-blue-700">
                                        Sebagian besar atribut di bawah ini terisi otomatis dari profil responden dan <strong>tidak dapat diubah (read-only)</strong> saat responden mengisi survei (kecuali beberapa atribut seperti Alamat Satuan Kerja). Blok ini terpasang permanen pada survei.
                                    </p>
                                </div>
                            </div>
                        </div>
                    ` : ''}
                    <div class="bg-gray-50 p-4 rounded-lg mb-4">
                        <h5 class="text-sm font-medium text-gray-700 mb-3">Nama Block <span class="text-red-500">*</span></h5>
                        <input type="text" name="sections[${sectionCounter}][section_name]" value="${sectionData.section_name || ''}" placeholder="Tulis nama blok disini..." required
                               ${isIdentity ? 'readonly' : ''}
                               class="focus:shadow-primary-outline text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 ${isIdentity ? 'bg-gray-100 font-semibold cursor-not-allowed text-gray-700' : 'bg-white text-gray-700'} bg-clip-padding px-3 py-2 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none" />
                    </div>

                    <div class="bg-gray-50 p-4 rounded-lg mb-4">
                        <h5 class="text-sm font-medium text-gray-700 mb-3">Deskripsi Block</h5>
                        <textarea name="sections[${sectionCounter}][section_description]" rows="2" placeholder="Tulis deskripsi blok disini..."
                                  ${isIdentity ? 'readonly' : ''}
                                  class="focus:shadow-primary-outline text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 ${isIdentity ? 'bg-gray-100 cursor-not-allowed text-gray-700' : 'bg-white text-gray-700'} bg-clip-padding px-3 py-2 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none">${sectionData.section_description || ''}</textarea>
                    </div>

                    <div class="bg-gray-50 p-4 rounded-lg mb-4">
                        <h5 class="text-sm font-medium text-gray-700 mb-3">Navigasi Block</h5>
                        <select name="sections[${sectionCounter}][navigation_type]" data-original-value="${sectionData.navigation_type}" class="focus:shadow-primary-outline text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none">
                            <option value="next" ${sectionData.navigation_type === 'next' ? 'selected' : ''}>Lanjut ke block berikutnya</option>
                            <option value="end" ${sectionData.navigation_type === 'end' ? 'selected' : ''}>Akhiri survey</option>
                        </select>
                    </div>

                    <div class="bg-blue-50 p-4 rounded-lg mb-4 border border-blue-200" ${isIdentity ? 'style="display:none;"' : ''}>
                        <div class="flex items-center">
                            <input type="checkbox" id="kompetensi_${sectionCounter}" name="sections[${sectionCounter}][is_kompetensi]" value="1" class="w-4 h-4 text-blue-600 bg-white border-gray-300 rounded focus:ring-blue-500 mr-2" onchange="toggleKompetensiBlock(${sectionCounter}, this)" ${sectionData.metadata && sectionData.metadata.is_kompetensi ? 'checked' : ''}>
                            <label for="kompetensi_${sectionCounter}" class="text-sm font-semibold text-blue-800">Jadikan sebagai Blok Penilaian Kompetensi/Indikator (Satu Tabel Analitik)</label>
                        </div>
                        <div id="kompetensi_info_${sectionCounter}" class="${sectionData.metadata && sectionData.metadata.is_kompetensi ? '' : 'hidden'} mt-3 bg-white p-3 rounded border border-blue-100">
                              <div class="mb-3">
                                  <label class="block text-xs font-medium text-blue-800 mb-1">Pertanyaan Utama <span class="text-red-500">*</span></label>
                                  <input type="text" name="sections[${sectionCounter}][pertanyaan_utama]" value="${sectionData.pertanyaan_utama || ''}" placeholder="Contoh: Bagaimana tingkat kompetensi [indikator] Anda?" class="text-sm w-full border border-gray-300 rounded px-2 py-1 focus:border-blue-500 outline-none">
                                  <p class="text-[10px] text-blue-600 mt-1">Gunakan kata <strong>[indikator]</strong> di dalam kalimat agar posisi indikator diganti otomatis (opsional).</p>
                              </div>
                            <p class="text-xs text-blue-800 mb-2">
                                <i class="fas fa-info-circle mr-1"></i>
                                Saat penanda ini dipilih, seluruh pertanyaan dalam blok ini akan diolah menjadi satu kesatuan tabel analitik dan visualisasi terintegrasi.
                            </p>
                            <ul class="text-xs text-blue-700 list-disc list-inside space-y-1 ml-1">
                                <li>Seluruh pertanyaan dalam blok wajib menggunakan <strong>tipe pertanyaan yang sama</strong>.</li>
                                <li>Seluruh pilihan jawaban juga wajib <strong>sama/seragam</strong>.</li>
                                <li>Tipe yang diperbolehkan: <strong>Radio Button, Checkbox, Dropdown, Multiple Choice Grid</strong>.</li>
                                <li>Admin cukup menyiapkan <strong>pertanyaan utama</strong>, <strong>template jawaban</strong>, lalu menambahkan <strong>daftar indikator</strong>.</li>
                                <li>Pertanyaan pada blok ini <strong>wajib memiliki label</strong>.</li>
                            </ul>
                        </div>
                    </div>

                    <!-- Questions Container -->
                    <div class="questions-container" data-section-id="${sectionCounter}">
                        <div class="flex justify-between items-center mb-4">
                            <h5 class="text-sm font-bold text-gray-700">Pertanyaan ${isIdentity ? '(Terkunci dari Profil)' : ''}</h5>
                            ${isIdentity ? `
                                <span class="text-xs text-gray-500 italic bg-gray-100 px-2 py-1 rounded">
                                    <i class="fas fa-lock mr-1"></i> Pertanyaan identitas terkunci
                                </span>
                            ` : `
                                <button type="button" onclick="addQuestion(${sectionCounter})" class="bg-green-500 text-white px-3 py-1 rounded text-sm hover:bg-green-600 transition-colors">
                                    <i class="fas fa-plus mr-1"></i>Tambah Pertanyaan
                                </button>
                            `}
                        </div>
                        <div class="questions-list space-y-3" id="questions-${sectionCounter}">
                            <!-- Questions will be loaded here -->
                        </div>
                    </div>

                    <!-- Add Block Button -->
                    <div class="add-block-section mt-6 pt-4">
                        <div class="flex justify-center">
                            <button type="button" onclick="addSectionAfter(${sectionCounter})" class="inline-block px-6 py-3 font-bold leading-normal text-center text-white align-middle transition-all ease-in bg-gradient-to-r from-green-500 to-green-600 border-0 rounded-lg shadow-lg cursor-pointer text-sm tracking-tight-rem hover:shadow-xl hover:-translate-y-1 active:opacity-85 hover:from-green-600 hover:to-green-700">
                                <i class="fas fa-plus-circle mr-2"></i> Tambah Block Baru
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;

        document.getElementById('sectionsContainer').insertAdjacentHTML('beforeend', sectionHtml);

        // Load questions for this section
        if (sectionData.questions) {
            Object.keys(sectionData.questions).forEach(questionId => {
                const questionData = sectionData.questions[questionId];
                loadExistingQuestion(sectionCounter, questionData, isIdentity);
            });
        }
        
        if (!isIdentity && sectionData.metadata && sectionData.metadata.is_kompetensi) {
            const checkbox = document.getElementById(`kompetensi_${sectionCounter}`);
            if (checkbox) toggleKompetensiBlock(sectionCounter, checkbox, sectionData.pertanyaan_utama || '');
        }
    });

    updateNavigationOptions();
}

// Load existing question
function loadExistingQuestion(sectionId, questionData, isIdentity = false) {
    questionCounter++;

    const questionHtml = `
        <div class="question-item ${isIdentity ? 'bg-gray-50 border border-gray-200' : ''}" data-question-id="${questionCounter}" data-question-number="Q${questionCounter}" data-section-id="${sectionId}">
            <div class="flex justify-between items-center mb-3">
                <div class="flex items-center gap-2">
                    <h6 class="text-sm font-semibold">Pertanyaan ${questionCounter}</h6>
                    ${isIdentity ? '<span class="text-xs font-semibold text-gray-600">(' + (questionData.question || '') + ')</span>' : ''}
                </div>
                <div class="flex space-x-2 question-controls">
                    ${isIdentity ? `
                        <span class="text-[11px] bg-blue-100 text-blue-800 font-medium px-2 py-0.5 rounded">
                            <i class="fas fa-lock text-[10px] mr-1"></i> Profil Responden
                        </span>
                    ` : `
                        <button type="button" onclick="addQuestion(${sectionId}, ${questionCounter})" class="text-green-500 hover:text-green-700 add-question-after-btn" title="Tambah Pertanyaan di Bawah">
                            <i class="fas fa-plus"></i>
                        </button>
                        <button type="button" onclick="cloneQuestion(${sectionId}, ${questionCounter})" class="text-blue-500 hover:text-blue-700" title="Clone Question">
                            <i class="fas fa-copy"></i>
                        </button>
                        <button type="button" onclick="deleteQuestion(${sectionId}, ${questionCounter})" class="text-red-500 hover:text-red-700" title="Delete Question">
                            <i class="fas fa-trash"></i>
                        </button>
                    `}
                </div>
            </div>

            <div class="space-y-3">
                <input type="hidden" name="sections[${sectionId}][questions][${questionCounter}][id]" value="${questionData.id || ''}">
                <div class="question-textarea-container">
                    <label class="inline-block mb-1 text-xs font-semibold text-slate-600">Teks Pertanyaan <span class="text-red-500">*</span></label>
                    <textarea name="sections[${sectionId}][questions][${questionCounter}][question]" rows="2" placeholder="Tulis pertanyaan disini..." required
                              ${isIdentity ? 'readonly' : ''}
                              class="focus:shadow-primary-outline text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 ${isIdentity ? 'bg-gray-100 font-medium text-gray-800 cursor-not-allowed' : 'bg-white text-gray-700'} bg-clip-padding px-3 py-2 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none">${questionData.question || ''}</textarea>
                </div>
                <div>
                    <label class="inline-block mb-1 text-xs font-semibold text-slate-600">Deskripsi/Instruksi</label>
                    <textarea name="sections[${sectionId}][questions][${questionCounter}][description]" rows="1" placeholder="Tulis deskripsi disini..."
                              ${isIdentity ? 'readonly' : ''}
                              class="focus:shadow-primary-outline text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 ${isIdentity ? 'bg-gray-100 text-gray-500 cursor-not-allowed' : 'bg-white text-gray-700'} bg-clip-padding px-3 py-2 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none">${questionData.description || ''}</textarea>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                ${isIdentity ? `
                    <div>
                        <input type="hidden" name="sections[${sectionId}][questions][${questionCounter}][type]" value="${questionData.type === 'date' ? 'date' : 'text'}">
                        <input type="text" value="${questionData.type === 'date' ? 'Date' : 'Text Input'}" readonly
                               class="focus:shadow-primary-outline text-sm leading-5.6 ease block w-full rounded-lg border border-solid border-gray-300 bg-gray-100 px-3 py-2 font-mono text-gray-700 cursor-not-allowed">
                    </div>
                    <div>
                        <input type="hidden" name="sections[${sectionId}][questions][${questionCounter}][visualization]" value="">
                    </div>
                    <div class="flex items-center pt-2">
                        <input type="hidden" name="sections[${sectionId}][questions][${questionCounter}][required]" value="1">
                        <span class="text-xs text-amber-700 bg-amber-50 px-2 py-1 rounded border border-amber-200 font-medium">
                            <i class="fas fa-check-circle mr-1"></i> Wajib & Otomatis Terisi
                        </span>
                    </div>
                ` : `
                    <div>
                        <select name="sections[${sectionId}][questions][${questionCounter}][type]" onchange="handleQuestionTypeChange(${sectionId}, ${questionCounter}, this.value)" required
                                class="question-type-select focus:shadow-primary-outline text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none">
                            <option value="text" ${questionData.type === 'text' ? 'selected' : ''}>Text Input</option>
                            <option value="textarea" ${questionData.type === 'textarea' ? 'selected' : ''}>Text Area</option>
                            <option value="radio" ${questionData.type === 'radio' ? 'selected' : ''}>Radio Button</option>
                            <option value="checkbox" ${questionData.type === 'checkbox' ? 'selected' : ''}>Checkbox</option>
                            <option value="select" ${questionData.type === 'select' ? 'selected' : ''}>Select Dropdown</option>
                            <option value="multiple_choice_grid" ${questionData.type === 'multiple_choice_grid' ? 'selected' : ''}>Multiple Choice Grid</option>
                            <option value="file" ${questionData.type === 'file' ? 'selected' : ''}>File Upload</option>
                            <option value="date" ${questionData.type === 'date' ? 'selected' : ''}>Date</option>
                            <option value="gaji" ${questionData.type === 'gaji' ? 'selected' : ''}>Gaji</option>
                        </select>
                    </div>
                    <div>
                        <select name="sections[${sectionId}][questions][${questionCounter}][visualization]"
                                class="focus:shadow-primary-outline text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none">
                            <option value="" ${!questionData.visualization ? 'selected' : ''}>Tidak ada visualisasi</option>
                            <option value="bar" ${questionData.visualization === 'bar' ? 'selected' : ''}>Bar Chart</option>
                            <option value="pie" ${questionData.visualization === 'pie' ? 'selected' : ''}>Pie Chart</option>
                        </select>
                    </div>
                    <div class="flex flex-col gap-2 pt-2">
                        <label class="inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="sections[${sectionId}][questions][${questionCounter}][required]" value="1" ${questionData.required == '1' ? 'checked' : ''} class="sr-only peer">
                            <div class="relative w-9 h-5 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
                            <span class="ml-2 text-xs font-medium text-gray-700 dark:text-gray-300">Wajib diisi</span>
                        </label>
                        <label class="inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="sections[${sectionId}][questions][${questionCounter}][is_analytic_table]" value="1" ${questionData.is_analytic_table == '1' ? 'checked' : ''} class="sr-only peer">
                            <div class="relative w-9 h-5 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all dark:border-gray-600 peer-checked:bg-indigo-600"></div>
                            <span class="ml-2 text-xs font-medium text-gray-700 dark:text-gray-300">Tabel Analitik</span>
                        </label>
                    </div>
                `}
            </div>

            ${isIdentity ? '' : `
            <!-- Options Container -->
            <div class="options-container" id="optionsContainer-${sectionId}-${questionCounter}" style="display: ${['radio', 'checkbox', 'select', 'multiple_choice_grid'].includes(questionData.type) ? 'block' : 'none'};">
                <div class="flex justify-between items-center mb-2">
                    <label class="block text-xs font-medium text-gray-700">Pilihan Jawaban</label>
                    <button type="button" onclick="addOption(${sectionId}, ${questionCounter})" class="bg-blue-500 text-white px-2 py-1 rounded text-xs hover:bg-blue-600 transition-colors">
                        <i class="fas fa-plus mr-1"></i>Tambah Pilihan
                    </button>
                </div>
                <div class="options-list space-y-2" id="optionsList-${sectionId}-${questionCounter}">
                    <!-- Options will be loaded here -->
                </div>

                <div class="mt-4 pt-4 border-t border-gray-200" id="gridColumnsContainer-${sectionId}-${questionCounter}" style="display: ${questionData.type === 'multiple_choice_grid' ? 'block' : 'none'};">
                    <div class="flex justify-between items-center mb-2">
                        <label class="block text-xs font-medium text-gray-700">Kolom Grid</label>
                        <button type="button" onclick="addGridColumnOption(${sectionId}, ${questionCounter})" class="bg-blue-500 text-white px-2 py-1 rounded text-xs hover:bg-blue-600 transition-colors">
                            <i class="fas fa-plus mr-1"></i>Tambah Kolom
                        </button>
                    </div>
                    <div class="space-y-2" id="gridColumnsList-${sectionId}-${questionCounter}"></div>
                </div>
            </div>

            <!-- Min Gaji Container -->
            <div class="min-gaji-container mt-4 pt-4 border-t border-gray-200" id="minGajiContainer-${sectionId}-${questionCounter}" style="display: ${questionData.type === 'gaji' ? 'block' : 'none'};">
                <label class="block text-xs font-semibold text-gray-700 mb-1">Minimal Nominal Gaji (Boleh dikosongkan)</label>
                <input type="number" name="sections[${sectionId}][questions][${questionCounter}][min_gaji]" value="${questionData.min_gaji || ''}" class="w-full text-sm leading-normal bg-white outline-none border border-gray-300 rounded px-3 py-2 focus:border-blue-500" placeholder="Contoh: 1500000">
            </div>

            <!-- Kompetensi Indikator Container (Hidden by default) -->
            <div id="indikatorContainer-${sectionId}-${questionCounter}" class="kompetensi-indikator-area mt-4 border-t pt-3" style="display: none;">
                <label class="block text-xs font-medium text-purple-700 mb-2"><i class="fas fa-list-ol mr-1"></i> Daftar Indikator</label>
                <div class="indikator-list space-y-2" id="indikatorList-${sectionId}-${questionCounter}">
                    <!-- indicators will be added here -->
                </div>
                <button type="button" onclick="addIndikatorItem(${sectionId}, ${questionCounter})" class="mt-2 bg-purple-100 text-purple-700 px-2 py-1 rounded text-xs hover:bg-purple-200 transition-colors">
                    <i class="fas fa-plus mr-1"></i>Tambah Indikator
                </button>
            </div>
            `}
        </div>
    `;

    document.getElementById(`questions-${sectionId}`).insertAdjacentHTML('beforeend', questionHtml);

    if (!isIdentity) {
        // Load options for this question if it's a choice-based question
        if (['radio', 'checkbox', 'select', 'multiple_choice_grid'].includes(questionData.type) && questionData.options) {
            questionData.options.forEach((option, index) => {
                loadExistingOption(sectionId, questionCounter, option, questionData.option_navigation ? questionData.option_navigation[index] : 'next');
            });
        }

        if (questionData.type === 'multiple_choice_grid') {
            const savedColumns = Array.isArray(questionData.grid_columns) && questionData.grid_columns.length > 0
                ? questionData.grid_columns
                : ['Column 1', 'Column 2'];
            savedColumns.forEach((column) => addGridColumnOption(sectionId, questionCounter, column));
        }
    }
}

// Load existing option
function loadExistingOption(sectionId, questionId, optionText, navigationValue) {
    const optionsList = document.getElementById(`optionsList-${sectionId}-${questionId}`);
    const optionCount = optionsList.children.length + 1;

    // Check if this question type supports navigation
    const questionTypeSelect = document.querySelector(`select[name="sections[${sectionId}][questions][${questionId}][type]"]`);
    const questionType = questionTypeSelect ? questionTypeSelect.value : 'text';
    const showNavigationToggle = ['radio', 'select'].includes(questionType);

    // Determine if custom navigation is being used
    // Custom navigation is active if navigationValue is not null, undefined, empty, or 'next' (default)
    const isCustomNavigation = navigationValue && navigationValue !== '' && navigationValue !== 'next';
    
    // Get block-level navigation as default for select questions when no custom navigation is set
    const blockNavigation = getBlockLevelNavigation(sectionId);
    const defaultNavigationValue = isCustomNavigation ? 'next' : blockNavigation;

    const optionHtml = `
        <div class="option-item mb-3" data-option-index="${optionCount}">
            <div class="flex items-start gap-2">
                <div class="option-controls">
                    <button type="button" onclick="moveOptionUp(this)" class="option-btn up" title="Move Up">
                        <i class="fas fa-chevron-up"></i>
                    </button>
                    <button type="button" onclick="moveOptionDown(this)" class="option-btn down" title="Move Down">
                        <i class="fas fa-chevron-down"></i>
                    </button>
                </div>
                <div class="flex-1">
                    <input type="text" name="sections[${sectionId}][questions][${questionId}][options][]"
                           value="${optionText}" placeholder="Pilihan ${optionCount}" required
                           class="focus:shadow-primary-outline text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none" />
                    ${showNavigationToggle ? `
                    <div class="mt-2 flex items-center gap-2">
                        <label class="flex items-center cursor-pointer navigation-toggle-label">
                            <input type="checkbox" onchange="toggleOptionNavigation(this)" class="mr-2 text-blue-600 rounded focus:ring-blue-500" ${isCustomNavigation ? 'checked' : ''}>
                            <span class="text-xs text-gray-600">🔀 Custom navigation untuk pilihan ini</span>
                        </label>
                    </div>
                    <div class="navigation-block ${isCustomNavigation ? 'block' : 'hidden'} mt-2 ml-4 px-3 py-2 bg-blue-50 border border-blue-200 rounded-lg">
                        <label class="text-xs font-medium text-blue-700 mb-1 block">Jika pilihan ini dipilih, lanjut ke:</label>
                        <select name="sections[${sectionId}][questions][${questionId}][option_navigation][]" ${!isCustomNavigation ? 'disabled' : ''} class="text-xs w-full border border-blue-300 rounded px-2 py-1 bg-white option-navigation-select" data-original-value="${navigationValue || ''}">
                            <option value="">Gunakan navigasi default</option>
                            <option value="next">Block Berikutnya</option>
                            <option value="end">Selesai Survey</option>
                        </select>
                        <input type="hidden" name="sections[${sectionId}][questions][${questionId}][option_navigation][]" value="${defaultNavigationValue}" class="hidden-navigation-input" ${isCustomNavigation ? 'disabled' : ''}>
                    </div>
                    ` : ''}
                </div>
                <button type="button" onclick="removeOption(this)" class="text-red-500 hover:text-red-700 p-2">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </div>
    `;

    optionsList.insertAdjacentHTML('beforeend', optionHtml);
    updateOptionButtons(optionsList);

    // Update navigation options after adding the option to populate block options
    setTimeout(() => {
        updateNavigationOptions();
        // Set the correct navigation value after options are populated
        if (isCustomNavigation) {
            const addedOption = optionsList.lastElementChild;
            const select = addedOption.querySelector('.option-navigation-select');
            if (select && navigationValue) {
                // Try to find and select the correct option
                const option = select.querySelector(`option[value="${navigationValue}"]`);
                if (option) {
                    select.value = navigationValue;
                } else {
                    // If the exact option doesn't exist, set to 'end' as fallback
                    select.value = 'end';
                }
            }
        }
    }, 100);
}

// Rest of the JavaScript functions (same as create.blade.php)
function addSection() {
    sectionCounter++;
    const colorClass = (sectionCounter % 2 === 0) ? 'block-color-even' : 'block-color-odd';

    const sectionHtml = `
        <div class="section-block p-6 ${colorClass}" data-section-id="${sectionCounter}">
            <div class="block-header">
                <div class="flex justify-between items-center">
                    <h4 class="block-title">Block ${sectionCounter}</h4>
                    <div class="icon-container">
                        <button type="button" onclick="cloneSection(${sectionCounter})" class="icon-link" title="Clone Block">
                            <i class="fas fa-copy"></i>
                        </button>
                        <button type="button" onclick="deleteSection(${sectionCounter})" class="icon-link" title="Delete Block">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>

            <div class="form-section">
                <!-- Block Info -->
                <div class="bg-gray-50 p-4 rounded-lg mb-4">
                    <h5 class="text-sm font-medium text-gray-700 mb-3">Nama Block <span class="text-red-500">*</span></h5>
                    <input type="text" name="sections[${sectionCounter}][section_name]" placeholder="Tulis nama blok disini..." required
                           class="focus:shadow-primary-outline text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none" />
                </div>

                <div class="bg-gray-50 p-4 rounded-lg mb-4">
                    <h5 class="text-sm font-medium text-gray-700 mb-3">Deskripsi Block</h5>
                    <textarea name="sections[${sectionCounter}][section_description]" rows="2" placeholder="Tulis deskripsi blok disini..."
                              class="focus:shadow-primary-outline text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none"></textarea>
                </div>

                <div class="bg-gray-50 p-4 rounded-lg mb-4">
                    <h5 class="text-sm font-medium text-gray-700 mb-3">Navigasi Block</h5>
                    <select name="sections[${sectionCounter}][navigation_type]" data-original-value="next" class="focus:shadow-primary-outline text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none">
                        <option value="next">Lanjut ke block berikutnya</option>
                        <option value="end">Akhiri survey</option>
                    </select>
                </div>

                <div class="bg-blue-50 p-4 rounded-lg mb-4 border border-blue-200">
                    <div class="flex items-center">
                        <input type="checkbox" id="kompetensi_${sectionCounter}" name="sections[${sectionCounter}][is_kompetensi]" value="1" class="w-4 h-4 text-blue-600 bg-white border-gray-300 rounded focus:ring-blue-500 mr-2" onchange="toggleKompetensiBlock(${sectionCounter}, this)">
                        <label for="kompetensi_${sectionCounter}" class="text-sm font-semibold text-blue-800">Jadikan sebagai Blok Penilaian Kompetensi/Indikator (Satu Tabel Analitik)</label>
                    </div>
                    <div id="kompetensi_info_${sectionCounter}" class="hidden mt-3 bg-white p-3 rounded border border-blue-100">
                        <div class="mb-3">
                            <label class="block text-xs font-medium text-blue-800 mb-1">Pertanyaan Utama <span class="text-red-500">*</span></label>
                            <input type="text" name="sections[${sectionCounter}][pertanyaan_utama]" placeholder="Contoh: Bagaimana tingkat kompetensi [indikator] Anda?" class="text-sm w-full border border-gray-300 rounded px-2 py-1 focus:border-blue-500 outline-none">
                            <p class="text-[10px] text-blue-600 mt-1">Gunakan kata <strong>[indikator]</strong> di dalam kalimat agar posisi indikator diganti otomatis (opsional).</p>
                        </div>
                        <p class="text-xs text-blue-800 mb-2">
                            <i class="fas fa-info-circle mr-1"></i>
                            Saat penanda ini dipilih, seluruh pertanyaan dalam blok ini akan diolah menjadi satu kesatuan tabel analitik dan visualisasi terintegrasi.
                        </p>
                        <ul class="text-xs text-blue-700 list-disc list-inside space-y-1 ml-1">
                            <li>Seluruh pertanyaan dalam blok wajib menggunakan <strong>tipe pertanyaan yang sama</strong>.</li>
                            <li>Seluruh pilihan jawaban juga wajib <strong>sama/seragam</strong>.</li>
                            <li>Tipe yang diperbolehkan: <strong>Radio Button, Checkbox, Dropdown, Multiple Choice Grid</strong>.</li>
                            <li>Admin cukup menyiapkan <strong>pertanyaan utama</strong>, <strong>template jawaban</strong>, lalu menambahkan <strong>daftar indikator</strong>.</li>
                            <li>Pertanyaan pada blok ini <strong>wajib memiliki label</strong>.</li>
                        </ul>
                    </div>
                </div>

                <!-- Questions Container -->
                <div class="questions-container" data-section-id="${sectionCounter}">
                    <div class="flex justify-between items-center mb-4">
                        <h5 class="text-sm font-medium text-gray-700">Pertanyaan</h5>
                        <button type="button" onclick="addQuestion(${sectionCounter})" class="bg-green-500 text-white px-3 py-1 rounded text-sm hover:bg-green-600 transition-colors">
                            <i class="fas fa-plus mr-1"></i>Tambah Pertanyaan
                        </button>
                    </div>
                    <div class="questions-list space-y-3" id="questions-${sectionCounter}">
                        <!-- Questions will be added here -->
                    </div>
                </div>

                <!-- Add Block Button -->
                <div class="add-block-section mt-6 pt-4">
                    <div class="flex justify-center">
                        <button type="button" onclick="addSectionAfter(${sectionCounter})" class="inline-block px-6 py-3 font-bold leading-normal text-center text-white align-middle transition-all ease-in bg-gradient-to-r from-green-500 to-green-600 border-0 rounded-lg shadow-lg cursor-pointer text-sm tracking-tight-rem hover:shadow-xl hover:-translate-y-1 active:opacity-85 hover:from-green-600 hover:to-green-700">
                            <i class="fas fa-plus-circle mr-2"></i> Tambah Block Baru
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `;

    document.getElementById('sectionsContainer').insertAdjacentHTML('beforeend', sectionHtml);
    updateNavigationOptions();
}

function addQuestion(sectionId, insertAfterQuestionId = null) {
    // Use timestamp as temporary question ID to avoid conflicts
    const tempQuestionId = Date.now();

    const questionHtml = `
        <div class="question-item" data-question-id="${tempQuestionId}" data-question-number="Q${tempQuestionId}" data-section-id="${sectionId}">
            <div class="flex justify-between items-center mb-3">
                <h6 class="text-sm font-semibold">Pertanyaan ${tempQuestionId}</h6>
                <div class="flex space-x-2 question-controls">
                    <button type="button" onclick="addQuestion(${sectionId}, ${tempQuestionId})" class="text-green-500 hover:text-green-700 add-question-after-btn" title="Tambah Pertanyaan di Bawah">
                        <i class="fas fa-plus"></i>
                    </button>
                    <button type="button" onclick="cloneQuestion(${sectionId}, ${tempQuestionId})" class="text-blue-500 hover:text-blue-700" title="Clone Question">
                        <i class="fas fa-copy"></i>
                    </button>
                    <button type="button" onclick="deleteQuestion(${sectionId}, ${tempQuestionId})" class="text-red-500 hover:text-red-700" title="Delete Question">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>

            <div class="space-y-3">
                <div class="question-textarea-container">
                    <label class="inline-block mb-1 text-xs font-semibold text-slate-600">Teks Pertanyaan <span class="text-red-500">*</span></label>
                    <textarea name="sections[${sectionId}][questions][${tempQuestionId}][question]" rows="2" placeholder="Tulis pertanyaan disini..." required
                              class="focus:shadow-primary-outline text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none"></textarea>
                </div>
                <div>
                    <label class="inline-block mb-1 text-xs font-semibold text-slate-600">Deskripsi/Instruksi</label>
                    <textarea name="sections[${sectionId}][questions][${tempQuestionId}][description]" rows="1" placeholder="Tulis deskripsi disini..."
                              class="focus:shadow-primary-outline text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none"></textarea>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                    <div>
                        <select name="sections[${sectionId}][questions][${tempQuestionId}][type]" onchange="handleQuestionTypeChange(${sectionId}, ${tempQuestionId}, this.value)" required class="question-type-select focus:shadow-primary-outline text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none">
                            <option value="text">Text Input</option>
                            <option value="textarea">Text Area</option>
                            <option value="radio">Radio Button</option>
                            <option value="checkbox">Checkbox</option>
                            <option value="select">Select Dropdown</option>
                            <option value="multiple_choice_grid">Multiple Choice Grid</option>
                            <option value="file">File Upload</option>
                            <option value="date">Date</option>
                            <option value="gaji">Gaji</option>
                        </select>
                    </div>

                    <div>
                        <select name="sections[${sectionId}][questions][${tempQuestionId}][visualization]"
                                class="focus:shadow-primary-outline text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none">
                            <option value="">Tidak ada visualisasi</option>
                            <option value="bar">Bar Chart</option>
                            <option value="pie">Pie Chart</option>
                        </select>
                    </div>

                    <div class="flex flex-col gap-2 pt-2">
                        <label class="inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="sections[${sectionId}][questions][${tempQuestionId}][required]" value="1" class="sr-only peer">
                            <div class="relative w-9 h-5 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
                            <span class="ml-2 text-xs font-medium text-gray-700 dark:text-gray-300">Wajib diisi</span>
                        </label>
                        <label class="inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="sections[${sectionId}][questions][${tempQuestionId}][is_analytic_table]" value="1" class="sr-only peer">
                            <div class="relative w-9 h-5 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all dark:border-gray-600 peer-checked:bg-indigo-600"></div>
                            <span class="ml-2 text-xs font-medium text-gray-700 dark:text-gray-300">Tabel Analitik</span>
                        </label>
                    </div>
                </div>

            <!-- Options Container -->
            <div class="options-container" id="optionsContainer-${sectionId}-${tempQuestionId}" style="display: none;">
                <div class="flex justify-between items-center mb-2">
                    <label class="block text-xs font-medium text-gray-700">Pilihan Jawaban</label>
                    <button type="button" onclick="addOption(${sectionId}, ${tempQuestionId})" class="bg-blue-500 text-white px-2 py-1 rounded text-xs hover:bg-blue-600 transition-colors">
                        <i class="fas fa-plus mr-1"></i>Tambah Pilihan
                    </button>
                </div>
                <div class="options-list space-y-2" id="optionsList-${sectionId}-${tempQuestionId}">
                    <!-- Options will be added here -->
                </div>

                <div class="mt-4 pt-4 border-t border-gray-200" id="gridColumnsContainer-${sectionId}-${tempQuestionId}" style="display: none;">
                    <div class="flex justify-between items-center mb-2">
                        <label class="block text-xs font-medium text-gray-700">Kolom Grid</label>
                        <button type="button" onclick="addGridColumnOption(${sectionId}, ${tempQuestionId})" class="bg-blue-500 text-white px-2 py-1 rounded text-xs hover:bg-blue-600 transition-colors">
                            <i class="fas fa-plus mr-1"></i>Tambah Kolom
                        </button>
                    </div>
                    <div class="space-y-2" id="gridColumnsList-${sectionId}-${tempQuestionId}"></div>
                </div>
            </div>

            <!-- Min Gaji Container -->
            <div class="min-gaji-container mt-4 pt-4 border-t border-gray-200" id="minGajiContainer-${sectionId}-${tempQuestionId}" style="display: none;">
                <label class="block text-xs font-semibold text-gray-700 mb-1">Minimal Nominal Gaji (Boleh dikosongkan)</label>
                <input type="number" name="sections[${sectionId}][questions][${tempQuestionId}][min_gaji]" class="w-full text-sm leading-normal bg-white outline-none border border-gray-300 rounded px-3 py-2 focus:border-blue-500" placeholder="Contoh: 1500000">
            </div>

            <!-- Kompetensi Indikator Container (Hidden by default) -->
            <div id="indikatorContainer-${sectionId}-${tempQuestionId}" class="kompetensi-indikator-area mt-4 border-t pt-3" style="display: none;">
                <label class="block text-xs font-medium text-purple-700 mb-2"><i class="fas fa-list-ol mr-1"></i> Daftar Indikator</label>
                <div class="indikator-list space-y-2" id="indikatorList-${sectionId}-${tempQuestionId}">
                    <!-- indicators will be added here -->
                </div>
                <button type="button" onclick="addIndikatorItem(${sectionId}, ${tempQuestionId})" class="mt-2 bg-purple-100 text-purple-700 px-2 py-1 rounded text-xs hover:bg-purple-200 transition-colors">
                    <i class="fas fa-plus mr-1"></i>Tambah Indikator
                </button>
            </div>
        </div>
    `;

    const questionsContainer = document.getElementById(`questions-${sectionId}`);
    if (!questionsContainer) {
        return;
    }

    if (insertAfterQuestionId) {
        const referenceQuestion = questionsContainer.querySelector(`[data-question-id="${insertAfterQuestionId}"]`);
        if (referenceQuestion) {
            referenceQuestion.insertAdjacentHTML('afterend', questionHtml);
        } else {
            questionsContainer.insertAdjacentHTML('beforeend', questionHtml);
        }
    } else {
        questionsContainer.insertAdjacentHTML('beforeend', questionHtml);
    }
    
    // After adding the question, reindex all sections and questions to ensure proper order
    setTimeout(() => {
        updateSectionNumbers();
        updateNavigationOptions();
    }, 50);
}

function updateQuestionNumbers(sectionId) {
    const questionsContainer = document.getElementById(`questions-${sectionId}`);
    if (questionsContainer) {
        const questions = questionsContainer.querySelectorAll('.question-item');

        questions.forEach((question, index) => {
            question.setAttribute('data-question-number', `Q${index + 1}`);

            // Update header text
            const header = question.querySelector('h6');
            if (header) {
                header.textContent = `Pertanyaan ${index + 1}`;
            }
        });
    }
}

function handleQuestionTypeChange(sectionId, questionId, type) {
    const optionsContainer = document.getElementById(`optionsContainer-${sectionId}-${questionId}`);
    const gridColumnsContainer = document.getElementById(`gridColumnsContainer-${sectionId}-${questionId}`);
    const minGajiContainer = document.getElementById(`minGajiContainer-${sectionId}-${questionId}`);

    if (minGajiContainer) {
        minGajiContainer.style.display = type === 'gaji' ? 'block' : 'none';
    }

    if (!optionsContainer) {
        console.error(`Options container not found: optionsContainer-${sectionId}-${questionId}`);
        return;
    }

    if (['radio', 'checkbox', 'select', 'multiple_choice_grid'].includes(type)) {
        optionsContainer.style.display = 'block';

        if (gridColumnsContainer) {
            gridColumnsContainer.style.display = type === 'multiple_choice_grid' ? 'block' : 'none';
        }

        // Add default options if none exist
        const optionsList = document.getElementById(`optionsList-${sectionId}-${questionId}`);
        
        if (!optionsList) {
            console.error(`Options list not found for question ${questionId}`);
            return;
        }

        if (optionsList.children.length === 0) {
            if (type === 'multiple_choice_grid') {
                addDefaultMultipleChoiceGridOptions(sectionId, questionId);
                addDefaultGridColumns(sectionId, questionId);
            } else {
                addOption(sectionId, questionId);
                addOption(sectionId, questionId);
            }
        } else {
            // Update existing options to add/remove navigation based on type
            updateExistingOptionsNavigation(sectionId, questionId, type);
        }

        if (type === 'multiple_choice_grid') {
            const gridColumnsList = document.getElementById(`gridColumnsList-${sectionId}-${questionId}`);
            if (gridColumnsList && gridColumnsList.children.length === 0) {
                addDefaultGridColumns(sectionId, questionId);
            }
        }
    } else {
        optionsContainer.style.display = 'none';
        if (gridColumnsContainer) {
            gridColumnsContainer.style.display = 'none';
        }
    }
}

function addGridColumnOption(sectionId, questionId, value = '') {
    const list = document.getElementById(`gridColumnsList-${sectionId}-${questionId}`);
    if (!list) {
        return;
    }

    const index = list.children.length + 1;
    const columnRow = document.createElement('div');
    columnRow.className = 'flex items-center gap-2 grid-column-item';
    columnRow.innerHTML = `
        <i class="far fa-circle text-gray-400 text-xs"></i>
        <input type="text" name="sections[${sectionId}][questions][${questionId}][grid_columns][]"
               placeholder="Column ${index}"
               class="focus:shadow-primary-outline text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none" />
        <button type="button" onclick="this.closest('.grid-column-item').remove()" class="text-red-500 hover:text-red-700 p-2">
            <i class="fas fa-trash"></i>
        </button>
    `;

    columnRow.querySelector('input').value = value;
    list.appendChild(columnRow);
}

function addDefaultGridColumns(sectionId, questionId) {
    ['Column 1', 'Column 2'].forEach((label) => addGridColumnOption(sectionId, questionId, label));
}

function addOption(sectionId, questionId) {
    const optionsList = document.getElementById(`optionsList-${sectionId}-${questionId}`);
    
    if (!optionsList) {
        console.error(`Options list not found for adding option: ${sectionId}-${questionId}`);
        return;
    }
    
    const optionCount = optionsList.children.length + 1;

    // Check if this question type supports navigation
    const questionTypeSelect = document.querySelector(`select[name="sections[${sectionId}][questions][${questionId}][type]"]`);
    const questionType = questionTypeSelect ? questionTypeSelect.value : 'text';
    const showNavigationToggle = ['radio', 'select'].includes(questionType);
    
    // Get block-level navigation as default for select questions
    const blockNavigation = getBlockLevelNavigation(sectionId);

    const optionHtml = `
        <div class="option-item mb-3" data-option-index="${optionCount}">
            <div class="flex items-start gap-2">
                <div class="option-controls">
                    <button type="button" onclick="moveOptionUp(this)" class="option-btn up" title="Move Up">
                        <i class="fas fa-chevron-up"></i>
                    </button>
                    <button type="button" onclick="moveOptionDown(this)" class="option-btn down" title="Move Down">
                        <i class="fas fa-chevron-down"></i>
                    </button>
                </div>
                <div class="flex-1">
                    <input type="text" name="sections[${sectionId}][questions][${questionId}][options][]"
                           placeholder="Pilihan ${optionCount}" required
                           class="focus:shadow-primary-outline text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none" />
                    ${showNavigationToggle ? `
                    <div class="mt-2 flex items-center gap-2">
                        <label class="flex items-center cursor-pointer navigation-toggle-label">
                            <input type="checkbox" onchange="toggleOptionNavigation(this)" class="mr-2 text-blue-600 rounded focus:ring-blue-500">
                            <span class="text-xs text-gray-600">🔀 Custom navigation untuk pilihan ini</span>
                        </label>
                    </div>
                    <div class="navigation-block hidden mt-2 ml-4 px-3 py-2 bg-blue-50 border border-blue-200 rounded-lg">
                        <label class="text-xs font-medium text-blue-700 mb-1 block">Jika pilihan ini dipilih, lanjut ke:</label>
                        <select name="sections[${sectionId}][questions][${questionId}][option_navigation][]" disabled class="text-xs w-full border border-blue-300 rounded px-2 py-1 bg-white option-navigation-select" data-original-value="">
                            <option value="">Gunakan navigasi default</option>
                            <option value="next">Block Berikutnya</option>
                            <option value="end">Selesai Survey</option>
                        </select>
                        <input type="hidden" name="sections[${sectionId}][questions][${questionId}][option_navigation][]" value="${blockNavigation}" class="hidden-navigation-input">
                    </div>
                    ` : ''}
                </div>
                <button type="button" onclick="removeOption(this)" class="text-red-500 hover:text-red-700 p-2">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </div>
    `;

    optionsList.insertAdjacentHTML('beforeend', optionHtml);
    updateOptionButtons(optionsList);
    updateOptionPlaceholders(optionsList);

    // Update navigation options for the new option
    if (showNavigationToggle) {
        updateNavigationOptions();
    }
}

function addDefaultMultipleChoiceGridOptions(sectionId, questionId) {
    const multiple_choice_gridLabels = [
        'Pernyataan 1',
        'Pernyataan 2',
        'Pernyataan 3'
    ];

    multiple_choice_gridLabels.forEach((label, index) => {
        addOption(sectionId, questionId);
        const optionsList = document.getElementById(`optionsList-${sectionId}-${questionId}`);
        const optionInputs = optionsList ? optionsList.querySelectorAll('input[type="text"]') : [];

        if (optionInputs[index]) {
            optionInputs[index].value = label;
        }
    });
}

// Helper function to get block-level navigation for a section
function getBlockLevelNavigation(sectionId) {
    const section = document.querySelector(`[data-section-id="${sectionId}"]`);
    if (section) {
        const navSelect = section.querySelector('select[name*="[navigation_type]"]');
        if (navSelect) {
            return navSelect.value || 'next';
        }
    }
    return 'next';
}

// Toggle option navigation
function toggleOptionNavigation(checkbox) {
    const optionItem = checkbox.closest('.option-item');
    const navigationBlock = optionItem.querySelector('.navigation-block');
    const navigationSelect = navigationBlock.querySelector('select');
    const hiddenInput = navigationBlock.querySelector('.hidden-navigation-input');

    if (checkbox.checked) {
        navigationBlock.classList.remove('hidden');
        navigationBlock.classList.add('block');
        // Enable the select element and disable hidden input
        navigationSelect.disabled = false;
        if (hiddenInput) hiddenInput.disabled = true;
    } else {
        navigationBlock.classList.remove('block');
        navigationBlock.classList.add('hidden');
        // Reset and disable select, enable hidden input with block-level navigation as default
        navigationSelect.value = '';
        navigationSelect.disabled = true;
        navigationSelect.setAttribute('data-original-value', '');
        if (hiddenInput) {
            hiddenInput.disabled = false;
            // Get the section ID from the input name to determine block-level navigation
            const sectionMatch = hiddenInput.name.match(/sections\[(\d+)\]/);
            const sectionId = sectionMatch ? sectionMatch[1] : null;
            const blockNavigation = sectionId ? getBlockLevelNavigation(sectionId) : 'next';
            hiddenInput.value = blockNavigation;
        }
    }
}

// Add event listener for navigation select changes
document.addEventListener('change', function(e) {
    if (e.target.matches('.option-navigation-select')) {
        // Update data-original-value when user changes the selection
        e.target.setAttribute('data-original-value', e.target.value);
    }

    // Also handle block navigation selects
    if (e.target.matches('select[name*="[navigation_type]"]')) {
        // Update data-original-value when user changes the block navigation
        e.target.setAttribute('data-original-value', e.target.value);
        
        // When block-level navigation changes, update all hidden inputs in select-type questions within this block
        const section = e.target.closest('.section-block');
        const sectionId = section ? section.getAttribute('data-section-id') : null;
        
        if (sectionId) {
            const blockNavigation = e.target.value || 'next';
            
            // Update all hidden inputs in select/radio questions within this section that don't have custom navigation
            const hiddenInputs = section.querySelectorAll('.hidden-navigation-input');
            hiddenInputs.forEach(hiddenInput => {
                if (!hiddenInput.disabled) { // Only update if not using custom navigation
                    hiddenInput.value = blockNavigation;
                }
            });
        }
    }
});

// Update existing options when question type changes
function updateExistingOptionsNavigation(sectionId, questionId, questionType) {
    const optionsList = document.getElementById(`optionsList-${sectionId}-${questionId}`);
    
    if (!optionsList) {
        console.error(`Options list not found for updating navigation: ${sectionId}-${questionId}`);
        return;
    }
    
    const options = optionsList.querySelectorAll('.option-item');
    const showNavigationToggle = ['radio', 'select'].includes(questionType);

    options.forEach((option, index) => {
        // Remove existing navigation toggle and block if any
        const existingToggle = option.querySelector('input[type="checkbox"]');
        const existingNav = option.querySelector('.navigation-block');

        if (existingToggle && existingToggle.closest('.flex.items-center')) {
            existingToggle.closest('.flex.items-center').remove();
        }
        if (existingNav) {
            existingNav.remove();
        }

        // Add navigation toggle and block if needed
        if (showNavigationToggle) {
            const inputElement = option.querySelector('input[type="text"]');
            const blockNavigation = getBlockLevelNavigation(sectionId);
            const navigationHtml = `
                <div class="mt-2 flex items-center gap-2">
                    <label class="flex items-center cursor-pointer navigation-toggle-label">
                        <input type="checkbox" onchange="toggleOptionNavigation(this)" class="mr-2 text-blue-600 rounded focus:ring-blue-500">
                        <span class="text-xs text-gray-600">🔀 Custom navigation untuk pilihan ini</span>
                    </label>
                </div>
                <div class="navigation-block hidden mt-2 ml-4 px-3 py-2 bg-blue-50 border border-blue-200 rounded-lg">
                    <label class="text-xs font-medium text-blue-700 mb-1 block">Jika pilihan ini dipilih, lanjut ke:</label>
                    <select name="sections[${sectionId}][questions][${questionId}][option_navigation][]" disabled class="text-xs w-full border border-blue-300 rounded px-2 py-1 bg-white option-navigation-select">
                        <option value="">Gunakan navigasi default</option>
                        <option value="next">Block Berikutnya</option>
                        <option value="end">Selesai Survey</option>
                    </select>
                    <input type="hidden" name="sections[${sectionId}][questions][${questionId}][option_navigation][]" value="${blockNavigation}" class="hidden-navigation-input">
                </div>
            `;

            inputElement.insertAdjacentHTML('afterend', navigationHtml);
        }
    });

    // Update navigation options
    if (showNavigationToggle) {
        updateNavigationOptions();
    }
}

function deleteSection(sectionId) {
    const section = document.querySelector(`[data-section-id="${sectionId}"]`);
    if (section && (section.getAttribute('data-is-identity') === 'true' || sectionId === 1 || String(sectionId) === '1')) {
        showErrorMessage('Block 1 (Identitas Responden) wajib ada dan tidak dapat dihapus.');
        return;
    }

    if (confirm('Apakah Anda yakin ingin menghapus block ini?')) {
        if (section) {
            section.remove();
            
            // Update all section numbers and form names to be sequential
            updateSectionNumbers();
            updateNavigationOptions();
        }
    }
}

function deleteQuestion(sectionId, questionId) {
    const section = document.querySelector(`[data-section-id="${sectionId}"]`);
    if (section && (section.getAttribute('data-is-identity') === 'true' || sectionId === 1 || String(sectionId) === '1')) {
        showErrorMessage('Pertanyaan pada Blok Identitas Responden tidak dapat dihapus.');
        return;
    }

    const questionsContainer = document.getElementById(`questions-${sectionId}`);
    if (questionsContainer) {
        const questions = questionsContainer.querySelectorAll('.question-item');

        if (questions.length <= 1) {
            alert('Setiap block minimal harus memiliki 1 pertanyaan!');
            return;
        }

        if (confirm('Apakah Anda yakin ingin menghapus pertanyaan ini?')) {
            const question = questionsContainer.querySelector(`[data-question-id="${questionId}"]`);
            if (question) {
                question.remove();
                
                // After deleting, reindex all sections and questions to ensure proper order
                setTimeout(() => {
                    updateSectionNumbers();
                    updateNavigationOptions();
                }, 50);
            }
        }
    }
}

function cloneSection(sectionId) {
    const originalSection = document.querySelector(`[data-section-id="${sectionId}"]`);
    if (originalSection && (originalSection.getAttribute('data-is-identity') === 'true' || sectionId === 1 || String(sectionId) === '1')) {
        showErrorMessage('Block Identitas Responden tidak dapat diduplikasi.');
        return;
    }
    if (originalSection) {
        // Create temporary section ID for initial creation
        const tempSectionId = Date.now(); // Use timestamp as temp ID
        const colorClass = 'block-color-odd'; // Will be updated by updateSectionNumbers()

        // Get all form data from the original section
        const originalData = getFormDataFromSection(originalSection, sectionId);

        const clonedHtml = originalSection.outerHTML
            .replace(new RegExp(`sections\\[${sectionId}\\]`, 'g'), `sections[${tempSectionId}]`)
            .replace(new RegExp(`data-section-id="${sectionId}"`, 'g'), `data-section-id="${tempSectionId}"`)
            .replace(new RegExp(`Block ${sectionId}`, 'g'), `Block ${tempSectionId}`)
            .replace(new RegExp(`questions-${sectionId}`, 'g'), `questions-${tempSectionId}`)
            .replace(new RegExp(`deleteSection\\(${sectionId}\\)`, 'g'), `deleteSection(${tempSectionId})`)
            .replace(new RegExp(`cloneSection\\(${sectionId}\\)`, 'g'), `cloneSection(${tempSectionId})`)
            .replace(new RegExp(`addQuestion\\(${sectionId}\\)`, 'g'), `addQuestion(${tempSectionId})`)
            .replace(new RegExp(`addSectionAfter\\(${sectionId}\\)`, 'g'), `addSectionAfter(${tempSectionId})`)
            .replace(/block-color-\w+/, colorClass); // Replace color class

        originalSection.insertAdjacentHTML('afterend', clonedHtml);

        // Update all sections after cloning and restore form data
        setTimeout(() => {
            // Find the cloned section by its position
            const clonedSection = originalSection.nextElementSibling;
            if (clonedSection && clonedSection.classList.contains('section-block')) {
                const clonedSectionId = clonedSection.getAttribute('data-section-id');
                
                // Update all section numbers and form names to be sequential
                updateSectionNumbers();
                updateNavigationOptions();
                
                // Restore form data to cloned section using new sequential ID
                const newSectionId = clonedSection.getAttribute('data-section-id');
                restoreFormDataToSection(newSectionId, originalData);
            }
        }, 100);
    }
}

function cloneQuestion(sectionId, questionId) {
    const section = document.querySelector(`[data-section-id="${sectionId}"]`);
    if (section && (section.getAttribute('data-is-identity') === 'true' || sectionId === 1 || String(sectionId) === '1')) {
        showErrorMessage('Pertanyaan pada Blok Identitas Responden tidak dapat diduplikasi.');
        return;
    }

    const originalQuestion = document.querySelector(`#questions-${sectionId} [data-question-id="${questionId}"]`);
    if (originalQuestion) {
        console.log('Cloning question:', questionId);

        // Get original question data
        const originalData = {
            question: originalQuestion.querySelector(`textarea[name*="[question]"]`)?.value || '',
            description: originalQuestion.querySelector(`textarea[name*="[description]"]`)?.value || '',
            type: originalQuestion.querySelector(`select[name*="[type]"]`)?.value || 'text',
            required: originalQuestion.querySelector(`input[name*="[required]"]`)?.checked || false,
            visualization: originalQuestion.querySelector(`select[name*="[visualization]"]`)?.value || '',
            options: [],
            optionNavigation: [],
            grid_columns: []
        };

        // Get options and their navigation values
        const optionInputs = originalQuestion.querySelectorAll('input[name*="[options]"]');
        optionInputs.forEach((input, index) => {
            if (input.value.trim()) {
                originalData.options.push(input.value.trim());

                // Get navigation value for this option
                const optionItem = input.closest('.option-item');
                const navSelect = optionItem ? optionItem.querySelector('.option-navigation-select') : null;
                const hiddenNav = optionItem ? optionItem.querySelector('.hidden-navigation-input') : null;

                if (navSelect && navSelect.value) {
                    originalData.optionNavigation.push(navSelect.value);
                } else if (hiddenNav && hiddenNav.value) {
                    originalData.optionNavigation.push(hiddenNav.value);
                } else {
                    originalData.optionNavigation.push('next');
                }
            }
        });

        const gridColumnInputs = originalQuestion.querySelectorAll('input[name*="[grid_columns]"]');
        gridColumnInputs.forEach((input) => {
            if (input.value.trim()) {
                originalData.grid_columns.push(input.value.trim());
            }
        });

        console.log('Original question data:', originalData);

        // Create temporary question using timestamp
        const tempQuestionId = Date.now();
        
        // Clone the HTML and insert it after the original question
        const clonedHtml = originalQuestion.outerHTML
            .replace(new RegExp(`data-question-id="${questionId}"`, 'g'), `data-question-id="${tempQuestionId}"`)
            .replace(new RegExp(`data-question-number="Q\\d+"`, 'g'), `data-question-number="Q${tempQuestionId}"`)
            .replace(new RegExp(`Pertanyaan \\d+`, 'g'), `Pertanyaan ${tempQuestionId}`)
            .replace(new RegExp(`cloneQuestion\\(${sectionId}, ${questionId}\\)`, 'g'), `cloneQuestion(${sectionId}, ${tempQuestionId})`)
            .replace(new RegExp(`deleteQuestion\\(${sectionId}, ${questionId}\\)`, 'g'), `deleteQuestion(${sectionId}, ${tempQuestionId})`)
            .replace(new RegExp(`handleQuestionTypeChange\\(${sectionId}, ${questionId}, this\\.value\\)`, 'g'), `handleQuestionTypeChange(${sectionId}, ${tempQuestionId}, this.value)`)
            .replace(new RegExp(`addOption\\(${sectionId}, ${questionId}\\)`, 'g'), `addOption(${sectionId}, ${tempQuestionId})`)
            .replace(new RegExp(`addGridColumnOption\\(${sectionId}, ${questionId}\\)`, 'g'), `addGridColumnOption(${sectionId}, ${tempQuestionId})`)
            .replace(new RegExp(`options-${sectionId}-${questionId}`, 'g'), `options-${sectionId}-${tempQuestionId}`)
            .replace(new RegExp(`optionsList-${sectionId}-${questionId}`, 'g'), `optionsList-${sectionId}-${tempQuestionId}`)
            .replace(new RegExp(`gridColumnsContainer-${sectionId}-${questionId}`, 'g'), `gridColumnsContainer-${sectionId}-${tempQuestionId}`)
            .replace(new RegExp(`gridColumnsList-${sectionId}-${questionId}`, 'g'), `gridColumnsList-${sectionId}-${tempQuestionId}`);

        // Insert the cloned question after the original
        originalQuestion.insertAdjacentHTML('afterend', clonedHtml);

        // After cloning, reindex all sections and questions to ensure proper order
        setTimeout(() => {
            updateSectionNumbers();
            updateNavigationOptions();
            
            // Find the cloned question by its position (should be right after original)
            const clonedQuestion = originalQuestion.nextElementSibling;
            if (clonedQuestion && clonedQuestion.classList.contains('question-item')) {
                console.log('Restoring data to cloned question');
                
                // Restore the original form values to the cloned question
                const questionInput = clonedQuestion.querySelector(`textarea[name*="[question]"]`);
                if (questionInput) {
                    questionInput.value = originalData.question;
                }

                const descInput = clonedQuestion.querySelector(`textarea[name*="[description]"]`);
                if (descInput) {
                    descInput.value = originalData.description;
                }

                const typeSelect = clonedQuestion.querySelector(`select[name*="[type]"]`);
                if (typeSelect) {
                    typeSelect.value = originalData.type;
                    typeSelect.dispatchEvent(new Event('change'));
                }

                const requiredInput = clonedQuestion.querySelector(`input[name*="[required]"]`);
                if (requiredInput) {
                    requiredInput.checked = originalData.required;
                }

                const vizSelect = clonedQuestion.querySelector(`select[name*="[visualization]"]`);
                if (vizSelect) {
                    vizSelect.value = originalData.visualization;
                }

                if (originalData.type === 'multiple_choice_grid' && originalData.grid_columns.length > 0) {
                    const questionNameInput = clonedQuestion.querySelector('textarea[name*="[question]"]');
                    const nameMatch = questionNameInput?.name.match(/sections\[(\d+)\]\[questions\]\[(\d+)\]/);
                    const newSectionId = nameMatch ? nameMatch[1] : sectionId;
                    const newQuestionId = nameMatch ? nameMatch[2] : tempQuestionId;
                    const gridColumnsList = clonedQuestion.querySelector('div[id*="gridColumnsList"]');
                    if (gridColumnsList) {
                        gridColumnsList.innerHTML = '';
                        originalData.grid_columns.forEach((label) => addGridColumnOption(newSectionId, newQuestionId, label));
                    }
                }

                console.log('Question cloned and data restored successfully');
            }
        }, 100);
    }
}

function getFormDataFromSection(sectionElement, sectionId) {
    const data = {
        sectionName: sectionElement.querySelector(`input[name="sections[${sectionId}][section_name]"]`)?.value || '',
        sectionDescription: sectionElement.querySelector(`textarea[name="sections[${sectionId}][section_description]"]`)?.value || '',
        navigationType: sectionElement.querySelector(`select[name="sections[${sectionId}][navigation_type]"]`)?.value || 'next',
        questions: []
    };

    // Get questions data
    const questions = sectionElement.querySelectorAll('.question-item');
    questions.forEach((questionEl, index) => {
        const questionData = {
            question: questionEl.querySelector(`textarea[name*="[question]"]`)?.value || '',
            description: questionEl.querySelector(`textarea[name*="[description]"]`)?.value || '',
            type: questionEl.querySelector(`select[name*="[type]"]`)?.value || 'text',
            required: questionEl.querySelector(`input[name*="[required]"]`)?.checked || false,
            visualization: questionEl.querySelector(`select[name*="[visualization]"]`)?.value || '',
            options: [],
            grid_columns: []
        };

        // Get options
        const optionInputs = questionEl.querySelectorAll('input[name*="[options]"]');
        optionInputs.forEach(input => {
            if (input.value.trim()) {
                questionData.options.push(input.value.trim());
            }
        });

        const gridColumnInputs = questionEl.querySelectorAll('input[name*="[grid_columns]"]');
        gridColumnInputs.forEach(input => {
            if (input.value.trim()) {
                questionData.grid_columns.push(input.value.trim());
            }
        });

        data.questions.push(questionData);
    });

    return data;
}

function restoreFormDataToSection(sectionId, data) {
    const section = document.querySelector(`[data-section-id="${sectionId}"]`);
    if (!section) return;

    // Restore section data
    const nameInput = section.querySelector(`input[name="sections[${sectionId}][section_name]"]`);
    if (nameInput) nameInput.value = data.sectionName;

    const descInput = section.querySelector(`textarea[name="sections[${sectionId}][section_description]"]`);
    if (descInput) descInput.value = data.sectionDescription;

    const navSelect = section.querySelector(`select[name="sections[${sectionId}][navigation_type]"]`);
    if (navSelect) {
        navSelect.value = data.navigationType;
        // Trigger change event
        navSelect.dispatchEvent(new Event('change'));
    }    // Clear existing questions and add cloned questions
    const questionsContainer = section.querySelector(`#questions-${sectionId}`);
    if (questionsContainer) {
        questionsContainer.innerHTML = '';

        data.questions.forEach((questionData, index) => {
            // Add question first
            addQuestion(sectionId);

            // Restore question data with proper timing
            setTimeout(() => {
                const questionCount = index + 1;
                const questionElements = questionsContainer.querySelectorAll('.question-item');
                const questionEl = questionElements[index]; // Use index instead of data-question-id

                if (questionEl) {
                    console.log('Restoring question data:', questionData);

                    const questionInput = questionEl.querySelector(`textarea[name="sections[${sectionId}][questions][${questionCount}][question]"]`);
                    if (questionInput) {
                        questionInput.value = questionData.question;
                        console.log('Set question text:', questionData.question);
                    }

                    const descInput = questionEl.querySelector(`textarea[name="sections[${sectionId}][questions][${questionCount}][description]"]`);
                    if (descInput) {
                        descInput.value = questionData.description;
                        console.log('Set description:', questionData.description);
                    }

                    const typeSelect = questionEl.querySelector(`select[name="sections[${sectionId}][questions][${questionCount}][type]"]`);
                    if (typeSelect) {
                        typeSelect.value = questionData.type;
                        // Trigger change event for options container
                        typeSelect.dispatchEvent(new Event('change'));
                        console.log('Set type:', questionData.type);
                    }

                    const requiredInput = questionEl.querySelector(`input[name="sections[${sectionId}][questions][${questionCount}][required]"]`);
                    if (requiredInput) {
                        requiredInput.checked = questionData.required;
                        console.log('Set required:', questionData.required);
                    }

                    const vizSelect = questionEl.querySelector(`select[name="sections[${sectionId}][questions][${questionCount}][visualization]"]`);
                    if (vizSelect) {
                        vizSelect.value = questionData.visualization;
                        console.log('Set visualization:', questionData.visualization);
                    }

                    // Add options if needed
                    if (['radio', 'checkbox', 'select', 'multiple_choice_grid'].includes(questionData.type) && questionData.options.length > 0) {
                        setTimeout(() => {
                            const optionsList = questionEl.querySelector(`#optionsList-${sectionId}-${questionCount}`);
                            if (optionsList) {
                                optionsList.innerHTML = '';
                                questionData.options.forEach((optionText, optIndex) => {
                                    addOption(sectionId, questionCount);
                                    // Set option value after a short delay
                                    setTimeout(() => {
                                        const optionInputs = optionsList.querySelectorAll('input[type="text"]');
                                        if (optionInputs[optIndex]) {
                                            optionInputs[optIndex].value = optionText;
                                            console.log('Set option:', optionText);
                                        }
                                    }, 50);
                                });
                            }
                        }, 300);
                    }

                    if (questionData.type === 'multiple_choice_grid' && Array.isArray(questionData.grid_columns) && questionData.grid_columns.length > 0) {
                        setTimeout(() => {
                            const gridColumnsList = questionEl.querySelector(`#gridColumnsList-${sectionId}-${questionCount}`);
                            if (gridColumnsList) {
                                gridColumnsList.innerHTML = '';
                                questionData.grid_columns.forEach((columnLabel) => {
                                    addGridColumnOption(sectionId, questionCount, columnLabel);
                                });
                            }
                        }, 350);
                    }
                }
            }, 200 * (index + 1)); // Stagger the restoration
        });
    }
}
function updateSectionNumbers() {
    const sections = document.querySelectorAll('.section-block');
    
    sections.forEach((section, index) => {
        const newSectionId = index + 1;
        const oldSectionId = section.getAttribute('data-section-id');
        
        // Update data-section-id attribute
        section.setAttribute('data-section-id', newSectionId);
        
        // Update visual header
        const header = section.querySelector('.block-header h4');
        if (header) {
            if (section.getAttribute('data-is-identity') === 'true' || newSectionId === 1) {
                header.textContent = `Block ${newSectionId}: Identitas Responden`;
            } else {
                header.textContent = `Block ${newSectionId}`;
            }
        }

        // Update color class
        const colorClass = (newSectionId % 2 === 0) ? 'block-color-even' : 'block-color-odd';
        section.className = section.className.replace(/block-color-\w+/, colorClass);
        
        // Update all form field names in this section
        updateSectionFormNames(section, oldSectionId, newSectionId);
        
        // Update all onclick handlers for this section
        updateSectionOnclickHandlers(section, oldSectionId, newSectionId);
    });
}

function updateSectionFormNames(section, oldId, newId) {
    // Update section-level form names
    const sectionInputs = section.querySelectorAll(`[name*="sections[${oldId}]"]`);
    sectionInputs.forEach(input => {
        input.name = input.name.replace(`sections[${oldId}]`, `sections[${newId}]`);
    });
    
    // Update question container ID
    const questionsContainer = section.querySelector(`#questions-${oldId}`);
    if (questionsContainer) {
        questionsContainer.id = `questions-${newId}`;
    }
    
    // Update questions form names and reindex them sequentially
    const questions = section.querySelectorAll('.question-item');
    questions.forEach((question, questionIndex) => {
        const newQuestionId = questionIndex + 1;
        const oldQuestionId = question.getAttribute('data-question-id');
        
        // Update question data attribute
        question.setAttribute('data-question-id', newQuestionId);
        
        // Update question number display
        question.setAttribute('data-question-number', `Q${newQuestionId}`);
        
        // Update question header text
        const questionHeader = question.querySelector('h6');
        if (questionHeader) {
            questionHeader.textContent = `Pertanyaan ${newQuestionId}`;
        }
        
        // Update all question form field names
        const questionInputs = question.querySelectorAll(`[name*="sections[${newId}][questions]"]`);
        questionInputs.forEach(input => {
            // Replace the old question index with new sequential index
            input.name = input.name.replace(
                new RegExp(`sections\\[${newId}\\]\\[questions\\]\\[\\d+\\]`), 
                    `sections[${newId}][questions][${newQuestionId}]`
            );
        });
        
        // Update onclick handlers for questions
        updateQuestionOnclickHandlers(question, newId, oldQuestionId, newQuestionId);
    });
}

function updateSectionOnclickHandlers(section, oldId, newId) {
    // Update clone section button
    const cloneBtn = section.querySelector(`[onclick*="cloneSection(${oldId})"]`);
    if (cloneBtn) {
        cloneBtn.setAttribute('onclick', `cloneSection(${newId})`);
    }
    
    // Update delete section button
    const deleteBtn = section.querySelector(`[onclick*="deleteSection(${oldId})"]`);
    if (deleteBtn) {
        deleteBtn.setAttribute('onclick', `deleteSection(${newId})`);
    }
    
    // Update add question button
    const addQuestionBtn = section.querySelector(`[onclick*="addQuestion(${oldId})"]`);
    if (addQuestionBtn) {
        addQuestionBtn.setAttribute('onclick', `addQuestion(${newId})`);
    }
    
    // Update add section after button
    const addSectionBtn = section.querySelector(`[onclick*="addSectionAfter(${oldId})"]`);
    if (addSectionBtn) {
        addSectionBtn.setAttribute('onclick', `addSectionAfter(${newId})`);
    }
}

function updateQuestionOnclickHandlers(question, sectionId, oldQuestionId, newQuestionId) {
    // Update add question button
    const addQuestionBtn = question.querySelector('.add-question-after-btn');
    if (addQuestionBtn) {
        addQuestionBtn.setAttribute('onclick', `addQuestion(${sectionId}, ${newQuestionId})`);
    }

    // Update clone question button
    const cloneBtn = question.querySelector(`[onclick*="cloneQuestion("]`);
    if (cloneBtn) {
        cloneBtn.setAttribute('onclick', `cloneQuestion(${sectionId}, ${newQuestionId})`);
    }
    
    // Update delete question button
    const deleteBtn = question.querySelector(`[onclick*="deleteQuestion("]`);
    if (deleteBtn) {
        deleteBtn.setAttribute('onclick', `deleteQuestion(${sectionId}, ${newQuestionId})`);
    }

    // Update Options Container & List
    const optionsContainer = question.querySelector(`[id^="optionsContainer-"]`);
    if (optionsContainer) {
        optionsContainer.id = `optionsContainer-${sectionId}-${newQuestionId}`;
    }

    const optionsList = question.querySelector(`[id^="optionsList-"]`);
    if (optionsList) {
        optionsList.id = `optionsList-${sectionId}-${newQuestionId}`;
    }

    // Update Grid Columns Container & List
    const gridColumnsContainer = question.querySelector(`[id^="gridColumnsContainer-"]`);
    if (gridColumnsContainer) {
        gridColumnsContainer.id = `gridColumnsContainer-${sectionId}-${newQuestionId}`;
    }

    const gridColumnsList = question.querySelector(`[id^="gridColumnsList-"]`);
    if (gridColumnsList) {
        gridColumnsList.id = `gridColumnsList-${sectionId}-${newQuestionId}`;
    }

    // Update Min Gaji Container
    const minGajiContainer = question.querySelector(`[id^="minGajiContainer-"]`);
    if (minGajiContainer) {
        minGajiContainer.id = `minGajiContainer-${sectionId}-${newQuestionId}`;
    }

    // Update Kompetensi Indikator Container & List
    const indikatorContainer = question.querySelector(`[id^="indikatorContainer-"]`);
    if (indikatorContainer) {
        indikatorContainer.id = `indikatorContainer-${sectionId}-${newQuestionId}`;
    }

    const indikatorList = question.querySelector(`[id^="indikatorList-"]`);
    if (indikatorList) {
        indikatorList.id = `indikatorList-${sectionId}-${newQuestionId}`;
    }

    // Update Question Type Select handler
    const typeSelect = question.querySelector('.question-type-select');
    if (typeSelect) {
        typeSelect.setAttribute('onchange', `handleQuestionTypeChange(${sectionId}, ${newQuestionId}, this.value)`);
    }

    // Update Add Option button handler
    const addOptionButton = question.querySelector(`button[onclick^="addOption("]`);
    if (addOptionButton) {
        addOptionButton.setAttribute('onclick', `addOption(${sectionId}, ${newQuestionId})`);
    }

    // Update Add Grid Column button handler
    const addGridColumnButton = question.querySelector(`button[onclick^="addGridColumnOption("]`);
    if (addGridColumnButton) {
        addGridColumnButton.setAttribute('onclick', `addGridColumnOption(${sectionId}, ${newQuestionId})`);
    }

    // Update Add Indikator Item button handler
    const addIndikatorItemButton = question.querySelector(`button[onclick^="addIndikatorItem("]`);
    if (addIndikatorItemButton) {
        addIndikatorItemButton.setAttribute('onclick', `addIndikatorItem(${sectionId}, ${newQuestionId})`);
    }
}
function updateNavigationOptions() {
    const sections = document.querySelectorAll('.section-block');
    const sectionCount = sections.length;

    sections.forEach((section, index) => {
        // Update the main navigation select to show direct block options
        const navSelect = section.querySelector('select[name*="[navigation_type]"]');
        if (navSelect) {
            // Get original value from data attribute or current value
            const originalValue = navSelect.getAttribute('data-original-value');
            const currentValue = navSelect.value;
            const valueToRestore = originalValue || currentValue || 'next';

            let optionsHtml = '<option value="next">Block Berikutnya</option>';

            // Add specific block options
            for (let i = 1; i <= sectionCount; i++) {
                if (i !== index + 1) { // Don't allow navigating to self
                    optionsHtml += `<option value="block_${i}">Ke Block ${i}</option>`;
                }
            }

            // Add "Selesaikan Survey" option
            optionsHtml += `<option value="end">Selesaikan Survey</option>`;

            navSelect.innerHTML = optionsHtml;

            // Restore previous value if it still exists
            if (valueToRestore && navSelect.querySelector(`option[value="${valueToRestore}"]`)) {
                navSelect.value = valueToRestore;
                navSelect.setAttribute('data-original-value', valueToRestore);
            } else {
                navSelect.value = 'next';
                navSelect.setAttribute('data-original-value', 'next');
            }
        }

        // Update option navigation selects within this section
        const optionNavSelects = section.querySelectorAll('.option-navigation-select');
        optionNavSelects.forEach(optionNavSelect => {
            // Get the original value from data attribute, current value, or hidden input
            const originalValue = optionNavSelect.getAttribute('data-original-value');
            const currentValue = optionNavSelect.value;
            const optionItem = optionNavSelect.closest('.option-item');
            const hiddenInput = optionItem ? optionItem.querySelector('.hidden-navigation-input') : null;
            const hiddenValue = hiddenInput ? hiddenInput.value : '';

            // Prioritize: originalValue > currentValue > hiddenValue
            const valueToRestore = originalValue || currentValue || hiddenValue || '';

            // Build option navigation options
            let optionsHtml = '<option value="">Gunakan navigasi default</option>';
            optionsHtml += '<option value="next">Block Berikutnya</option>';

            // Add specific block options
            for (let i = 1; i <= sectionCount; i++) {
                optionsHtml += `<option value="block_${i}">Ke Block ${i}</option>`;
            }

            optionsHtml += '<option value="end">Selesai Survey</option>';

            optionNavSelect.innerHTML = optionsHtml;

            // Restore the value
            if (valueToRestore && optionNavSelect.querySelector(`option[value="${valueToRestore}"]`)) {
                optionNavSelect.value = valueToRestore;
                // Update the data attribute to keep track
                optionNavSelect.setAttribute('data-original-value', valueToRestore);
            } else {
                optionNavSelect.value = '';
                optionNavSelect.setAttribute('data-original-value', '');
            }

            // Update hidden input to match if it exists
            if (hiddenInput) {
                // Use block-level navigation as default instead of hardcoded 'next'
                const sectionElement = optionNavSelect.closest('.section-block');
                const sectionId = sectionElement ? sectionElement.getAttribute('data-section-id') : null;
                const blockNavigation = sectionId ? getBlockLevelNavigation(sectionId) : 'next';
                hiddenInput.value = optionNavSelect.value || blockNavigation;
            }
        });
    });
}

// Function to add a section after a specific section (for the "Tambah Block Baru" button)
function addSectionAfter(afterSectionId) {
    // Get reference to the section we're inserting after
    const currentSection = document.querySelector(`[data-section-id="${afterSectionId}"]`);
    if (!currentSection) {
        console.error('Could not find section to insert after:', afterSectionId);
        return;
    }
    
    // Create temporary section ID for initial creation
    const tempSectionId = Date.now(); // Use timestamp as temp ID
    const colorClass = 'block-color-odd'; // Will be updated by updateSectionNumbers()

    const sectionHtml = `
        <div class="section-block p-6 ${colorClass}" data-section-id="${tempSectionId}">
            <div class="block-header">
                <div class="flex justify-between items-center">
                    <h4 class="block-title">Block ${tempSectionId}</h4>
                    <div class="icon-container">
                        <button type="button" onclick="cloneSection(${tempSectionId})" class="icon-link" title="Clone Block">
                            <i class="fas fa-copy"></i>
                        </button>
                        <button type="button" onclick="deleteSection(${tempSectionId})" class="icon-link" title="Delete Block">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>

            <div class="form-section">
                <!-- Block Info -->
                <div class="bg-gray-50 p-4 rounded-lg mb-4">
                    <h5 class="text-sm font-medium text-gray-700 mb-3">Nama Block <span class="text-red-500">*</span></h5>
                    <input type="text" name="sections[${tempSectionId}][section_name]" placeholder="Tulis nama blok disini..." required
                           class="focus:shadow-primary-outline text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none" />
                </div>

                <div class="bg-gray-50 p-4 rounded-lg mb-4">
                    <h5 class="text-sm font-medium text-gray-700 mb-3">Deskripsi Block</h5>
                    <textarea name="sections[${tempSectionId}][section_description]" rows="2" placeholder="Tulis deskripsi blok disini..."
                              class="focus:shadow-primary-outline text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none"></textarea>
                </div>

                <div class="bg-gray-50 p-4 rounded-lg mb-4">
                    <h5 class="text-sm font-medium text-gray-700 mb-3">Navigasi Block</h5>
                    <select name="sections[${tempSectionId}][navigation_type]" data-original-value="next" class="focus:shadow-primary-outline text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none">
                        <option value="next">Lanjut ke block berikutnya</option>
                        <option value="end">Akhiri survey</option>
                    </select>
                </div>

                <div class="bg-blue-50 p-4 rounded-lg mb-4 border border-blue-200">
                    <div class="flex items-center">
                        <input type="checkbox" id="kompetensi_${tempSectionId}" name="sections[${tempSectionId}][is_kompetensi]" value="1" class="w-4 h-4 text-blue-600 bg-white border-gray-300 rounded focus:ring-blue-500 mr-2" onchange="toggleKompetensiBlock(${tempSectionId}, this)">
                        <label for="kompetensi_${tempSectionId}" class="text-sm font-semibold text-blue-800">Jadikan sebagai Blok Penilaian Kompetensi/Indikator (Satu Tabel Analitik)</label>
                    </div>
                    <div id="kompetensi_info_${tempSectionId}" class="hidden mt-3 bg-white p-3 rounded border border-blue-100">
                        <div class="mb-3">
                            <label class="block text-xs font-medium text-blue-800 mb-1">Pertanyaan Utama <span class="text-red-500">*</span></label>
                            <input type="text" name="sections[${tempSectionId}][pertanyaan_utama]" placeholder="Contoh: Bagaimana tingkat kompetensi [indikator] Anda?" class="text-sm w-full border border-gray-300 rounded px-2 py-1 focus:border-blue-500 outline-none">
                            <p class="text-[10px] text-blue-600 mt-1">Gunakan kata <strong>[indikator]</strong> di dalam kalimat agar posisi indikator diganti otomatis (opsional).</p>
                        </div>
                        <p class="text-xs text-blue-800 mb-2">
                            <i class="fas fa-info-circle mr-1"></i>
                            Saat penanda ini dipilih, seluruh pertanyaan dalam blok ini akan diolah menjadi satu kesatuan tabel analitik dan visualisasi terintegrasi.
                        </p>
                        <ul class="text-xs text-blue-700 list-disc list-inside space-y-1 ml-1">
                            <li>Seluruh pertanyaan dalam blok wajib menggunakan <strong>tipe pertanyaan yang sama</strong>.</li>
                            <li>Seluruh pilihan jawaban juga wajib <strong>sama/seragam</strong>.</li>
                            <li>Tipe yang diperbolehkan: <strong>Radio Button, Checkbox, Dropdown, Multiple Choice Grid</strong>.</li>
                            <li>Admin cukup menyiapkan <strong>pertanyaan utama</strong>, <strong>template jawaban</strong>, lalu menambahkan <strong>daftar indikator</strong>.</li>
                            <li>Pertanyaan pada blok ini <strong>wajib memiliki label</strong>.</li>
                        </ul>
                    </div>
                </div>

                <!-- Questions Container -->
                <div class="questions-container" data-section-id="${tempSectionId}">
                    <div class="flex justify-between items-center mb-4">
                        <h5 class="text-sm font-medium text-gray-700">Pertanyaan</h5>
                        <button type="button" onclick="addQuestion(${tempSectionId})" class="bg-green-500 text-white px-3 py-1 rounded text-sm hover:bg-green-600 transition-colors">
                            <i class="fas fa-plus mr-1"></i>Tambah Pertanyaan
                        </button>
                    </div>
                    <div class="questions-list space-y-3" id="questions-${tempSectionId}">
                        <!-- Questions will be added here -->
                    </div>
                </div>

                <!-- Add Block Button -->
                <div class="add-block-section mt-6 pt-4">
                    <div class="flex justify-center">
                        <button type="button" onclick="addSectionAfter(${tempSectionId})" class="inline-block px-6 py-3 font-bold leading-normal text-center text-white align-middle transition-all ease-in bg-gradient-to-r from-green-500 to-green-600 border-0 rounded-lg shadow-lg cursor-pointer text-sm tracking-tight-rem hover:shadow-xl hover:-translate-y-1 active:opacity-85 hover:from-green-600 hover:to-green-700">
                            <i class="fas fa-plus-circle mr-2"></i> Tambah Block Baru
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `;

    // Insert the new section after the current section
    currentSection.insertAdjacentHTML('afterend', sectionHtml);

    // Now update all section numbers and form names to be sequential
    setTimeout(() => {
        updateSectionNumbers();
        updateNavigationOptions();
        
        // Add first question to the newly created section
        // Find the new section by its position (it should be right after currentSection)
        const newSection = currentSection.nextElementSibling;
        if (newSection && newSection.classList.contains('section-block')) {
            const newSectionId = newSection.getAttribute('data-section-id');
            addQuestion(newSectionId);
        }
    }, 100);
}

// Function to move option up
function moveOptionUp(button) {
    const optionItem = button.closest('.option-item');
    const previousOption = optionItem.previousElementSibling;

    if (previousOption) {
        optionItem.parentNode.insertBefore(optionItem, previousOption);
        const optionsList = optionItem.closest('[id*="optionsList"]') || optionItem.parentNode;
        updateOptionButtons(optionsList);
        updateOptionPlaceholders(optionsList);
    }
}

// Function to move option down
function moveOptionDown(button) {
    const optionItem = button.closest('.option-item');
    const nextOption = optionItem.nextElementSibling;

    if (nextOption) {
        optionItem.parentNode.insertBefore(nextOption, optionItem);
        const optionsList = optionItem.closest('[id*="optionsList"]') || optionItem.parentNode;
        updateOptionButtons(optionsList);
        updateOptionPlaceholders(optionsList);
    }
}

// Function to remove option
function removeOption(button) {
    const optionItem = button.closest('.option-item');
    const optionsList = optionItem.closest('[id*="optionsList"]') || optionItem.parentNode;

    optionItem.remove();
    updateOptionButtons(optionsList);
    updateOptionPlaceholders(optionsList);
}

// Function to update option buttons (enable/disable based on position)
function updateOptionButtons(optionsList) {
    const options = optionsList.querySelectorAll('.option-item');

    options.forEach((option, index) => {
        const upButton = option.querySelector('.option-btn.up');
        const downButton = option.querySelector('.option-btn.down');

        if (upButton) {
            upButton.disabled = index === 0;
        }
        if (downButton) {
            downButton.disabled = index === options.length - 1;
        }

        // Update data-option-index
        option.setAttribute('data-option-index', index + 1);
    });
}

function updateOptionPlaceholders(optionsList) {
    const options = optionsList.querySelectorAll('.option-item');

    options.forEach((option, index) => {
        const input = option.querySelector('input[type="text"]');
        if (input) {
            input.placeholder = `Pilihan ${index + 1}`;
        }
    });
}

function validateForm() {
    const errors = [];

    // Validate basic survey info
    const surveyName = document.querySelector('input[name="nama"]');
    if (!surveyName || !surveyName.value.trim()) {
        errors.push('Nama Survey wajib diisi');
        markFieldError(surveyName);
    } else {
        markFieldValid(surveyName);
    }

    const startDate = document.querySelector('input[name="tanggal_mulai"]');
    if (!startDate || !startDate.value) {
        errors.push('Tanggal Mulai wajib diisi');
        markFieldError(startDate);
    } else {
        markFieldValid(startDate);
    }

    const endDate = document.querySelector('input[name="tanggal_selesai"]');
    if (!endDate || !endDate.value) {
        errors.push('Tanggal Selesai wajib diisi');
        markFieldError(endDate);
    } else {
        markFieldValid(endDate);
    }

    const surveyType = document.querySelector('select[name="type_survei"]');
    if (!surveyType || !surveyType.value) {
        errors.push('Tipe Survei wajib dipilih');
        markFieldError(surveyType);
    } else {
        markFieldValid(surveyType);
    }

    // Validate blocks and questions
    const sections = document.querySelectorAll('.section-block');
    if (sections.length === 0) {
        errors.push('Minimal harus ada 1 block');
    }

    sections.forEach((section, sectionIndex) => {
        const sectionName = section.querySelector('input[name*="[section_name]"]');
        if (!sectionName || !sectionName.value.trim()) {
            errors.push(`Nama Block ${sectionIndex + 1} wajib diisi`);
            markFieldError(sectionName);
        } else {
            markFieldValid(sectionName);
        }

        const questions = section.querySelectorAll('.question-item');
        if (questions.length === 0) {
            errors.push(`Block ${sectionIndex + 1} harus memiliki minimal 1 pertanyaan`);
        }

        questions.forEach((question, questionIndex) => {
            const questionText = question.querySelector('textarea[name*="[question]"]');
            if (!questionText || !questionText.value.trim()) {
                errors.push(`Pertanyaan ${questionIndex + 1} di Block ${sectionIndex + 1} wajib diisi`);
                markFieldError(questionText);
            } else {
                markFieldValid(questionText);
            }

            // Validate options for select types
            const questionType = question.querySelector('select[name*="[type]"]');
            if (questionType && ['radio', 'checkbox', 'select', 'multiple_choice_grid'].includes(questionType.value)) {
                const options = question.querySelectorAll('input[name*="[options]"]');
                const filledOptions = Array.from(options).filter(opt => opt.value.trim());
                const isGrid = questionType.value === 'multiple_choice_grid';
                const minRows = isGrid ? 1 : 2;

                if (filledOptions.length < minRows) {
                    errors.push(`Pertanyaan ${questionIndex + 1} di Block ${sectionIndex + 1} dengan tipe ${questionType.value} harus memiliki minimal ${minRows} pilihan`);
                    options.forEach(opt => markFieldError(opt));
                } else {
                    options.forEach(opt => markFieldValid(opt));
                }

                if (isGrid) {
                    const columns = question.querySelectorAll('input[name*="[grid_columns]"]');
                    const filledColumns = Array.from(columns).filter(col => col.value.trim());
                    if (filledColumns.length < 1) {
                        errors.push(`Pertanyaan ${questionIndex + 1} di Block ${sectionIndex + 1} dengan tipe multiple_choice_grid harus memiliki minimal 1 kolom`);
                        columns.forEach(col => markFieldError(col));
                    } else {
                        columns.forEach(col => markFieldValid(col));
                    }
                }
            }
        });
    });

    // Show errors if any
    if (errors.length > 0) {
        showErrorMessage('Terdapat kesalahan pada form:\n\n' + errors.join('\n'));
        return false;
    }

    return true;
}

function markFieldError(field) {
    if (field) {
        field.classList.add('border-red-500', 'bg-red-50');
        field.classList.remove('border-green-500', 'bg-green-50');
    }
}

function markFieldValid(field) {
    if (field) {
        field.classList.remove('border-red-500', 'bg-red-50');
        field.classList.add('border-green-500', 'bg-green-50');
    }
}

// Clear validation styling on input
document.addEventListener('input', function(e) {
    if (e.target.matches('input, textarea, select')) {
        e.target.classList.remove('border-red-500', 'bg-red-50', 'border-green-500', 'bg-green-50');
    }
});

// Function to show error message
function showErrorMessage(message) {
    // Remove any existing messages
    removeMessages();

    const messageDiv = document.createElement('div');
    messageDiv.className = 'fixed top-4 right-4 bg-red-500 text-white px-6 py-4 rounded-lg shadow-lg z-50 max-w-md';
    const renderedMessage = String(message).replace(/\n/g, '<br>');
    messageDiv.innerHTML = `
        <div class="flex items-center">
            <i class="fas fa-exclamation-circle mr-3"></i>
            <div>
                <h4 class="font-semibold">Error!</h4>
                <p class="text-sm mt-1">${renderedMessage}</p>
            </div>
            <button onclick="this.parentElement.parentElement.remove()" class="ml-4 text-white hover:text-gray-200">
                <i class="fas fa-times"></i>
            </button>
        </div>
    `;

    document.body.appendChild(messageDiv);

    // Auto remove after 10 seconds
    setTimeout(() => {
        if (messageDiv.parentElement) {
            messageDiv.remove();
        }
    }, 10000);
}

// Function to show success message
function showSuccessMessage(message) {
    // Remove any existing messages
    removeMessages();

    const messageDiv = document.createElement('div');
    messageDiv.className = 'fixed top-4 right-4 bg-green-500 text-white px-6 py-4 rounded-lg shadow-lg z-50 max-w-md';
    messageDiv.innerHTML = `
        <div class="flex items-center">
            <i class="fas fa-check-circle mr-3"></i>
            <div>
                <h4 class="font-semibold">Berhasil!</h4>
                <p class="text-sm mt-1">${message}</p>
            </div>
        </div>
    `;

    document.body.appendChild(messageDiv);

    // Auto remove after 5 seconds
    setTimeout(() => {
        if (messageDiv.parentElement) {
            messageDiv.remove();
        }
    }, 5000);
}

// Function to remove existing messages
function removeMessages() {
    const existingMessages = document.querySelectorAll('.fixed.top-4.right-4');
    existingMessages.forEach(msg => msg.remove());
}

function buildFriendlyValidationMessage(errorsObj) {
    const fallback = 'Ada data yang belum valid. Periksa kembali isian formulir.';

    if (!errorsObj || typeof errorsObj !== 'object') {
        return fallback;
    }

    const messages = [];
    Object.entries(errorsObj).forEach(([field, fieldMessages]) => {
        if (!Array.isArray(fieldMessages) || fieldMessages.length === 0) {
            return;
        }

        const rawMessage = fieldMessages[0];
        messages.push(humanizeValidationField(field, rawMessage));
    });

    if (messages.length === 0) {
        return fallback;
    }

    const uniqueMessages = [...new Set(messages)];
    return uniqueMessages.map((message, index) => `${index + 1}. ${message}`).join('\n');
}

function humanizeValidationField(field, rawMessage) {
    const labelByField = {
        nama: 'Nama Survei',
        tanggal_mulai: 'Tanggal Mulai',
        tanggal_selesai: 'Tanggal Selesai',
        type_survei: 'Tipe Survei',
        deskripsi: 'Deskripsi Survei',
    };

    if (labelByField[field]) {
        return `${labelByField[field]}: ${normalizeValidationMessage(rawMessage)}`;
    }

    const sectionMatch = field.match(/^sections\.(\d+)\.(section_name|section_description|navigation_type)$/);
    if (sectionMatch) {
        const sectionId = sectionMatch[1];
        const fieldName = sectionMatch[2];
        const sectionNumber = getSectionNumberById(sectionId);
        const sectionLabel = `Block ${sectionNumber}`;

        if (fieldName === 'section_name') {
            return `Nama ${sectionLabel} wajib diisi.`;
        }

        if (fieldName === 'section_description') {
            return `Deskripsi ${sectionLabel} tidak valid.`;
        }

        return `Pengaturan navigasi pada ${sectionLabel} tidak valid.`;
    }

    const questionMatch = field.match(/^sections\.(\d+)\.questions\.(\d+)\.(.+)$/);
    if (questionMatch) {
        const sectionId = questionMatch[1];
        const questionId = questionMatch[2];
        const questionField = questionMatch[3];

        const sectionNumber = getSectionNumberById(sectionId);
        const questionNumber = getQuestionNumberById(sectionId, questionId);
        const contextLabel = `Block ${sectionNumber}, Pertanyaan ${questionNumber}`;

        if (questionField === 'question') {
            return `Teks pertanyaan wajib diisi (${contextLabel}).`;
        }

        if (questionField === 'type') {
            return `Tipe pertanyaan wajib dipilih (${contextLabel}).`;
        }

        if (questionField.startsWith('options')) {
            return `Pilihan jawaban belum lengkap (${contextLabel}). Minimal isi 2 pilihan.`;
        }

        if (questionField === 'visualization') {
            return `Pilihan visualisasi tidak valid (${contextLabel}).`;
        }

        return `${normalizeValidationMessage(rawMessage)} (${contextLabel}).`;
    }

    return normalizeValidationMessage(rawMessage);
}

function normalizeValidationMessage(message) {
    if (!message) {
        return 'Isian tidak valid.';
    }

    const lower = String(message).toLowerCase();

    if (lower.includes('required')) {
        return 'Wajib diisi.';
    }

    if (lower.includes('must be an array') || lower.includes('must be a string') || lower.includes('must be')) {
        return 'Format isian tidak sesuai.';
    }

    return String(message);
}

function getSectionNumberById(sectionId) {
    const sections = Array.from(document.querySelectorAll('.section-block'));
    const index = sections.findIndex(section => String(section.getAttribute('data-section-id')) === String(sectionId));
    return index >= 0 ? index + 1 : sectionId;
}

function getQuestionNumberById(sectionId, questionId) {
    const section = document.querySelector(`.section-block[data-section-id="${sectionId}"]`);
    if (!section) {
        return questionId;
    }

    const questions = Array.from(section.querySelectorAll('.question-item'));
    const index = questions.findIndex(question => String(question.getAttribute('data-question-id')) === String(questionId));
    if (index >= 0) {
        return index + 1;
    }

    const expectedPrefix = `sections[${sectionId}][questions][${questionId}]`;
    const matchingField = Array.from(section.querySelectorAll('[name]')).find((field) => {
        return typeof field.name === 'string' && field.name.includes(expectedPrefix);
    });

    if (!matchingField) {
        return questionId;
    }

    const questionItem = matchingField.closest('.question-item');
    if (!questionItem) {
        return questionId;
    }

    const derivedIndex = questions.indexOf(questionItem);
    return derivedIndex >= 0 ? derivedIndex + 1 : questionId;
}

function normalizeFormIndexesBeforeSubmit() {
    const sections = Array.from(document.querySelectorAll('.section-block'));

    sections.forEach((section, sectionIndex) => {
        const newSectionId = String(sectionIndex + 1);
        section.setAttribute('data-section-id', newSectionId);

        const sectionFields = section.querySelectorAll('[name*="sections["]');
        sectionFields.forEach((field) => {
            if (!field.name) {
                return;
            }

            field.name = field.name.replace(/sections\[[^\]]+\]/, `sections[${newSectionId}]`);
        });

        const questionsContainer = section.querySelector('[id^="questions-"]');
        if (questionsContainer) {
            questionsContainer.id = `questions-${newSectionId}`;
        }

        const questions = Array.from(section.querySelectorAll('.question-item'));
        questions.forEach((question, questionIndex) => {
            const newQuestionId = String(questionIndex + 1);
            question.setAttribute('data-question-id', newQuestionId);
            question.setAttribute('data-question-number', `Q${newQuestionId}`);

            const header = question.querySelector('.question-header h6');
            if (header) {
                header.textContent = `Pertanyaan ${newQuestionId}`;
            }

            const questionFields = question.querySelectorAll('[name]');
            questionFields.forEach((field) => {
                if (!field.name) {
                    return;
                }

                field.name = field.name.replace(
                    /sections\[[^\]]+\]\[questions\]\[[^\]]+\]/,
                    `sections[${newSectionId}][questions][${newQuestionId}]`
                );
            });

            const optionsContainer = question.querySelector('[id^="optionsContainer-"]');
            if (optionsContainer) {
                optionsContainer.id = `optionsContainer-${newSectionId}-${newQuestionId}`;
            }

            const optionsList = question.querySelector('[id^="optionsList-"]');
            if (optionsList) {
                optionsList.id = `optionsList-${newSectionId}-${newQuestionId}`;
            }

            const gridColumnsContainer = question.querySelector('[id^="gridColumnsContainer-"]');
            if (gridColumnsContainer) {
                gridColumnsContainer.id = `gridColumnsContainer-${newSectionId}-${newQuestionId}`;
            }

            const gridColumnsList = question.querySelector('[id^="gridColumnsList-"]');
            if (gridColumnsList) {
                gridColumnsList.id = `gridColumnsList-${newSectionId}-${newQuestionId}`;
            }

            const minGajiContainer = question.querySelector('[id^="minGajiContainer-"]');
            if (minGajiContainer) {
                minGajiContainer.id = `minGajiContainer-${newSectionId}-${newQuestionId}`;
            }

            const typeSelect = question.querySelector('select[name*="[type]"]');
            if (typeSelect) {
                typeSelect.setAttribute('onchange', `handleQuestionTypeChange(${newSectionId}, ${newQuestionId}, this.value)`);
            }

            const addAfterButton = question.querySelector('.add-question-after-btn');
            if (addAfterButton) {
                addAfterButton.setAttribute('onclick', `addQuestion(${newSectionId}, ${newQuestionId})`);
            }

            const cloneButton = question.querySelector('button[onclick*="cloneQuestion("]');
            if (cloneButton) {
                cloneButton.setAttribute('onclick', `cloneQuestion(${newSectionId}, ${newQuestionId})`);
            }

            const deleteButton = question.querySelector('button[onclick*="deleteQuestion("]');
            if (deleteButton) {
                deleteButton.setAttribute('onclick', `deleteQuestion(${newSectionId}, ${newQuestionId})`);
            }

            const addOptionButton = question.querySelector('button[onclick*="addOption("]');
            if (addOptionButton) {
                addOptionButton.setAttribute('onclick', `addOption(${newSectionId}, ${newQuestionId})`);
            }

            const addGridColumnButton = question.querySelector('button[onclick*="addGridColumnOption("]');
            if (addGridColumnButton) {
                addGridColumnButton.setAttribute('onclick', `addGridColumnOption(${newSectionId}, ${newQuestionId})`);
            }
        });
    });
}

window.toggleKompetensiBlock = function(sectionId, checkbox) {
    const infoDiv = document.getElementById(`kompetensi_info_${sectionId}`);
    const section = document.querySelector(`[data-section-id="${sectionId}"]`);
    const questionsContainer = document.getElementById(`questions-${sectionId}`);
    
    if (checkbox.checked) {
        // KOMPETENSI MODE ON
        infoDiv.classList.remove('hidden');
        
        // Sembunyikan tombol "Tambah Pertanyaan"
        if (section) {
            const addBtns = section.querySelectorAll('button[onclick*="addQuestion"]');
            addBtns.forEach(btn => {
                if (btn.textContent.includes('Tambah Pertanyaan')) {
                    btn.style.display = 'none';
                }
            });
        }
        
        // Pastikan hanya ada 1 pertanyaan
        const questions = questionsContainer.querySelectorAll('.question-item');
        if (questions.length === 0) {
            addQuestion(sectionId);
        } else if (questions.length > 1) {
            for (let i = 1; i < questions.length; i++) {
                questions[i].remove();
            }
        }
        
        setTimeout(() => {
            const question = questionsContainer.querySelector('.question-item');
            if (question) {
                // Sembunyikan tombol kontrol pertanyaan (add, copy, delete)
                const controls = question.querySelector('.question-controls');
                if (controls) controls.style.display = 'none';
                
                // Batasi tipe pertanyaan ke 4 tipe yang diizinkan
                const typeSelect = question.querySelector('.question-type-select');
                if (typeSelect) {
                    if (!typeSelect.hasAttribute('data-original-html')) {
                        typeSelect.setAttribute('data-original-html', typeSelect.innerHTML);
                    }
                    const allowedTypes = ['radio', 'multiple_choice_grid'];
                    let newHtml = '';
                    const tempDiv = document.createElement('div');
                    tempDiv.innerHTML = typeSelect.getAttribute('data-original-html');
                    Array.from(tempDiv.querySelectorAll('option')).forEach(opt => {
                        if (allowedTypes.includes(opt.value)) {
                            newHtml += opt.outerHTML;
                        }
                    });
                    typeSelect.innerHTML = newHtml;
                    // Jika tipe saat ini tidak diizinkan, set ke radio
                    if (!allowedTypes.includes(typeSelect.value)) {
                        typeSelect.value = 'radio';
                        const qId = question.getAttribute('data-question-id');
                        handleQuestionTypeChange(sectionId, qId, 'radio');
                    }
                }
                
                // Tampilkan field indikator
                const qId = question.getAttribute('data-question-id');
                const indikatorContainer = document.getElementById(`indikatorContainer-${sectionId}-${qId}`);
                if (indikatorContainer) {
                    indikatorContainer.style.display = 'block';
                    // Sembunyikan textarea pertanyaan asli
                    const questionTextarea = document.querySelector(`textarea[name="sections[${sectionId}][questions][${qId}][question]"]`);
                    if (questionTextarea) {
                        const container = questionTextarea.closest('.question-textarea-container');
                        if (container) container.style.display = 'none';
                        // Initialize first indicator if empty
                        const list = document.getElementById(`indikatorList-${sectionId}-${qId}`);
                        if (list && list.children.length === 0) {
                            const existingLines = questionTextarea.value.split(/\r?\n|\\n/).filter(l => l.trim());
                            if (existingLines.length > 0) {
                                existingLines.forEach(line => addIndikatorItem(sectionId, qId, line));
                            } else {
                                addIndikatorItem(sectionId, qId);
                            }
                        }
                    }
                }
            }
        }, 100);
        
    } else {
        // KOMPETENSI MODE OFF
        infoDiv.classList.add('hidden');
        
        // Tampilkan kembali tombol "Tambah Pertanyaan"
        if (section) {
            const addBtns = section.querySelectorAll('button[onclick*="addQuestion"]');
            addBtns.forEach(btn => {
                if (btn.textContent.includes('Tambah Pertanyaan')) {
                    btn.style.display = 'inline-block';
                }
            });
        }
        
        setTimeout(() => {
            const questions = questionsContainer.querySelectorAll('.question-item');
            questions.forEach((question, index) => {
                // Tampilkan kembali tombol kontrol pertanyaan
                const controls = question.querySelector('.question-controls');
                if (controls) controls.style.display = 'flex';
                
                // Kembalikan semua tipe pertanyaan menjadi tersedia
                const typeSelect = question.querySelector('.question-type-select');
                if (typeSelect && typeSelect.hasAttribute('data-original-html')) {
                    const currentVal = typeSelect.value;
                    typeSelect.innerHTML = typeSelect.getAttribute('data-original-html');
                    typeSelect.value = currentVal;
                }
                
                // Sembunyikan field indikator
                const qId = question.getAttribute('data-question-id');
                const indikatorContainer = document.getElementById(`indikatorContainer-${sectionId}-${qId}`);
                if (indikatorContainer) {
                    indikatorContainer.style.display = 'none';
                    // Tampilkan kembali textarea pertanyaan asli
                    const questionTextarea = document.querySelector(`textarea[name="sections[${sectionId}][questions][${qId}][question]"]`);
                    if (questionTextarea) {
                        const container = questionTextarea.closest('.question-textarea-container');
                        if (container) container.style.display = 'block';
                    }
                }
            });
        }, 100);
    }
};

window.addIndikatorItem = function(sectionId, questionId, value = '') {
    const list = document.getElementById(`indikatorList-${sectionId}-${questionId}`);
    if (!list) return;
    const index = list.children.length + 1;
    const html = `
        <div class="flex items-center gap-2 indikator-item mb-2">
            <span class="text-xs text-gray-500 w-5">${index}.</span>
            <input type="text" class="flex-1 text-xs border border-gray-300 rounded px-2 py-1 indikator-input" placeholder="Indikator ${index}" value="${value.replace(/"/g, '&quot;')}" oninput="syncIndikators(${sectionId}, ${questionId})">
            <button type="button" onclick="this.parentElement.remove(); syncIndikators(${sectionId}, ${questionId});" class="text-red-400 hover:text-red-600 text-xs p-1"><i class="fas fa-times"></i></button>
        </div>
    `;
    list.insertAdjacentHTML('beforeend', html);
    syncIndikators(sectionId, questionId);
};

window.syncIndikators = function(sectionId, questionId) {
    const list = document.getElementById(`indikatorList-${sectionId}-${questionId}`);
    if (!list) return;
    const inputs = list.querySelectorAll('.indikator-input');
    const values = Array.from(inputs).map(input => input.value.trim()).filter(v => v !== '');
    
    // Update the hidden textarea
    const textarea = document.querySelector(`textarea[name="sections[${sectionId}][questions][${questionId}][question]"]`);
    if (textarea) {
        textarea.value = values.join('\n');
    }
    
    // Re-number the indicators
    Array.from(list.children).forEach((child, idx) => {
        const numSpan = child.querySelector('span');
        if (numSpan) numSpan.textContent = (idx + 1) + '.';
        const input = child.querySelector('input');
        if (input && !input.value) input.placeholder = 'Indikator ' + (idx + 1);
    });
};

window.addIndikator = function(sectionId, questionId) {
    const indikatorList = document.getElementById(`indikatorList-${sectionId}-${questionId}`);
    if (!indikatorList) return;
    
    const index = indikatorList.children.length + 1;
    const row = document.createElement('div');
    row.className = 'flex items-center gap-2 indikator-item';
    row.innerHTML = `
        <i class="far fa-circle text-green-400 text-xs"></i>
        <input type="text" name="sections[${sectionId}][questions][${questionId}][indikators][]"
               placeholder="Masukkan indikator ${index}..."
               class="focus:shadow-primary-outline text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none" />
        <button type="button" onclick="this.closest('.indikator-item').remove()" class="text-red-500 hover:text-red-700 p-2" title="Hapus indikator">
            <i class="fas fa-trash"></i>
        </button>
    `;
    indikatorList.appendChild(row);
};
</script>
@endpush

@endsection



