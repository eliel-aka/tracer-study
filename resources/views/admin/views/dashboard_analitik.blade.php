@extends('admin.layouts.app')
@section('title', 'Tabel Analitik Survei')

@section('content')
    <div class="w-full px-6 py-6 mx-auto min-w-0">
        <div class="flex flex-wrap -mx-3">
            <div class="w-full px-3 min-w-0">
                <div class="relative flex flex-col min-w-0 w-full max-w-full break-words bg-white border-0 border-transparent border-solid shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border overflow-hidden">
                    <div class="p-6 pb-0 mb-0 border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                        <div class="flex justify-between items-start flex-wrap gap-4">
                            <div>
                                <h6 class="dark:text-white mb-1 text-sm font-bold">Tabel Analitik Hasil Survei</h6>
                                <p class="text-xs text-slate-500 dark:text-slate-400">Distribusi jawaban dalam bentuk jumlah dan persentase</p>
                            </div>
                            <div class="flex gap-2">
                                <a href="{{ route('admin.dashboard.grafik', $survey->id) }}" class="inline-flex items-center px-3 py-2 text-xs font-bold rounded-lg border border-blue-600 text-blue-600 hover:bg-blue-50 dark:text-blue-400 dark:border-blue-400">
                                    <i class="fas fa-chart-bar mr-2"></i> Lihat Grafik
                                </a>
                                <a href="{{ route('admin.dashboard.analitik.export', ['surveyId' => $survey->id, 'breakdown_by' => $selectedSegmentDimension]) }}" class="inline-flex items-center px-3 py-2 text-xs font-bold rounded-lg bg-green-600 text-white hover:bg-green-700 shadow-md">
                                    <i class="fas fa-file-excel mr-2"></i> Export Excel
                                </a>
                            </div>
                        </div>

                        <form id="analitikFilterForm" class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-3 items-end w-full mt-6" action="{{ route('admin.dashboard.analitik', $survey->id) }}" method="GET">
                            <div>
                                <label class="block text-xs mb-1 text-slate-500 dark:text-slate-300">Survei</label>
                                <select id="surveySelect" class="text-[11px] sm:text-xs w-full rounded-lg border border-solid border-gray-300 dark:bg-slate-850 dark:text-white bg-white py-1.5 sm:py-2 px-3 text-gray-700 focus:border-blue-500 focus:outline-none">
                                    @foreach ($allSurveys as $option)
                                        <option value="{{ $option->id }}" {{ (string) $survey->id === (string) $option->id ? 'selected' : '' }}>{{ $option->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs mb-1 text-slate-500 dark:text-slate-300">Breakdown Tabel</label>
                                <select id="segmentDimensionSelect" name="breakdown_by" class="text-[11px] sm:text-xs w-full rounded-lg border border-solid border-gray-300 dark:bg-slate-850 dark:text-white bg-white py-1.5 sm:py-2 px-3 text-gray-700 focus:border-blue-500 focus:outline-none">
                                    <option value="prodi" {{ $selectedSegmentDimension === 'prodi' ? 'selected' : '' }}>Program Studi</option>
                                    <option value="tahun_lulus" {{ $selectedSegmentDimension === 'tahun_lulus' ? 'selected' : '' }}>Tahun Lulus</option>
                                </select>
                            </div>
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.dashboard.analitik', $survey->id) }}" class="text-center px-3 sm:px-4 py-2 text-[11px] sm:text-xs font-bold rounded-lg border border-gray-300 text-slate-600 hover:bg-slate-100 dark:text-white dark:border-slate-600 dark:hover:bg-slate-700 whitespace-nowrap">Reset</a>
                                <button type="submit" class="px-3 sm:px-4 py-2 text-[11px] sm:text-xs font-bold rounded-lg bg-blue-600 text-white hover:bg-blue-700 whitespace-nowrap">Terapkan Filter</button>
                            </div>
                        </form>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 my-6">
                            <div class="bg-blue-50 dark:bg-blue-900/30 rounded-xl p-4 border border-blue-100 dark:border-blue-800">
                                <h4 class="text-xs font-semibold text-blue-600 dark:text-blue-300 uppercase tracking-wider">Total Responden</h4>
                                <p id="summaryTotalResponses" class="text-2xl font-bold text-blue-800 dark:text-blue-100 mt-1">{{ $initialAnalitikPayload['summary']['totalResponses'] ?? 0 }}</p>
                            </div>
                            <div class="bg-green-50 dark:bg-green-900/30 rounded-xl p-4 border border-green-100 dark:border-green-800">
                                <h4 class="text-xs font-semibold text-green-600 dark:text-green-300 uppercase tracking-wider">Responden Selesai</h4>
                                <p id="summaryCompletedResponses" class="text-2xl font-bold text-green-800 dark:text-green-100 mt-1">{{ $initialAnalitikPayload['summary']['completedResponses'] ?? 0 }}</p>
                            </div>
                            <div class="bg-purple-50 dark:bg-purple-900/30 rounded-xl p-4 border border-purple-100 dark:border-purple-800">
                                <h4 class="text-xs font-semibold text-purple-600 dark:text-purple-300 uppercase tracking-wider">Tingkat Penyelesaian</h4>
                                <p id="summaryCompletionRate" class="text-2xl font-bold text-purple-800 dark:text-purple-100 mt-1">{{ $initialAnalitikPayload['summary']['completionRate'] ?? 0 }}%</p>
                            </div>
                        </div>
                    </div>

                    <div class="flex-auto p-6 pt-0 min-w-0 w-full max-w-full">
                        @if (empty($initialAnalitikPayload['charts']))
                            <div class="text-center py-12 bg-gray-50 dark:bg-slate-800/50 rounded-2xl border-2 border-dashed border-gray-200 dark:border-gray-700">
                                <i class="fas fa-table text-4xl text-gray-300 dark:text-gray-600 mb-3"></i>
                                <p class="text-sm text-slate-500 dark:text-slate-400">
                                    Pertanyaan dengan opsi <b>"Tabel Analitik"</b> belum tersedia untuk survei ini.<br>
                                    Silakan centang opsi "Tabel Analitik" pada Builder Survei.
                                </p>
                            </div>
                        @endif

                        <div id="analitikContainer" class="space-y-12">
                            @foreach ($initialAnalitikPayload['charts'] ?? [] as $qId => $chart)
                                <div class="analitik-question-section" data-question-id="{{ $qId }}">
                                    <div class="mb-6 border-l-4 border-indigo-500 pl-4">
                                        <h3 class="text-lg font-bold dark:text-white leading-tight">{{ $chart['questionText'] ?? 'Pertanyaan' }}</h3>
                                        <div class="flex items-center gap-2 mt-1">
                                            @if(isset($chart['blok']))
                                            <span class="text-xs font-medium px-2 py-0.5 bg-gray-100 dark:bg-slate-700 text-gray-600 dark:text-gray-300 rounded">Blok {{ $chart['blok'] }}</span>
                                            @endif
                                            <span class="text-xs text-gray-400">ID: Q-{{ $qId }}</span>
                                        </div>
                                    </div>

                                    <div id="loading-{{ $qId }}" class="text-center py-10">
                                        <div class="animate-spin inline-block w-8 h-8 border-[3px] border-current border-t-transparent text-indigo-600 rounded-full" role="status"></div>
                                        <p class="mt-2 text-sm text-gray-500">Memproses data tabel...</p>
                                    </div>

                                    <div id="error-{{ $qId }}" class="hidden p-4 bg-red-50 text-red-600 rounded-lg text-center">
                                        <i class="fas fa-exclamation-triangle mr-2"></i> Gagal memuat data untuk pertanyaan ini.
                                    </div>

                                    <div id="content-{{ $qId }}" class="hidden space-y-8">
                                        <!-- Tables will be injected here -->
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @php
        $analitikPayload = [
            'selectedSurveyId' => (string) $survey->id,
            'selectedSegmentDimension' => $selectedSegmentDimension,
            'analitikDataUrlBase' => url('admin/dashboard/analitik-data'),
            'initialPayload' => $initialAnalitikPayload,
            'segmentLabel' => $selectedSegmentDimension === 'tahun_lulus' ? 'Tahun Lulus' : 'Program Studi'
        ];
    @endphp

    <script id="analitikPayload" type="application/json">{!! json_encode($analitikPayload) !!}</script>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const payloadData = JSON.parse(document.getElementById('analitikPayload').textContent);
            const { initialPayload, analitikDataUrlBase, selectedSurveyId, selectedSegmentDimension, segmentLabel } = payloadData;

            // Handle survey change
            document.getElementById('surveySelect').addEventListener('change', function() {
                const surveyId = this.value;
                const url = new URL(window.location.href);
                url.pathname = url.pathname.replace(/\/\d+$/, '/' + surveyId);
                window.location.href = url.toString();
            });

            function renderAnalitikTables(payload) {
                const charts = payload.charts || {};
                
                // Update summary
                if (payload.summary) {
                    document.getElementById('summaryTotalResponses').textContent = payload.summary.totalResponses;
                    document.getElementById('summaryCompletedResponses').textContent = payload.summary.completedResponses;
                    document.getElementById('summaryCompletionRate').textContent = payload.summary.completionRate + '%';
                }

                Object.keys(charts).forEach(qId => {
                    const chartData = charts[qId];
                    const container = document.getElementById(`content-${qId}`);
                    const loading = document.getElementById(`loading-${qId}`);
                    
                    if (!container) return;

                    loading.classList.add('hidden');
                    container.classList.remove('hidden');
                    container.innerHTML = ''; // Clear previous content

                    if (chartData.type === 'kompetensi_block') {
                        // 1. Aggregate Table
                        const aggTableHtml = createKompetensiTableHtml(chartData.aggregate, chartData.columns, null, chartData.maxSkala);
                        container.insertAdjacentHTML('beforeend', aggTableHtml);

                        // 2. Segmented Tables
                        if (chartData.segments && chartData.segments.length > 0) {
                            const segmentsHeader = `<h4 class="text-sm font-bold text-gray-700 dark:text-gray-300 mt-4 mb-4 border-t pt-6 uppercase tracking-wider">Breakdown Per ${segmentLabel}</h4>`;
                            container.insertAdjacentHTML('beforeend', segmentsHeader);
                            
                            const segmentsGrid = document.createElement('div');
                            segmentsGrid.className = 'grid grid-cols-1 gap-8';
                            
                            chartData.segments.forEach(seg => {
                                const segTableHtml = createKompetensiTableHtml(seg.rows, chartData.columns, seg.segmentLabel, chartData.maxSkala);
                                segmentsGrid.insertAdjacentHTML('beforeend', segTableHtml);
                            });
                            
                            container.appendChild(segmentsGrid);
                        }
                    } else if (chartData.type === 'multiple_choice_grid') {
                        // 1. Aggregate Table (STIS)
                        const aggTableHtml = createGridTableHtml('Politeknik Statistika STIS', chartData.columns, chartData.aggregate);
                        container.insertAdjacentHTML('beforeend', aggTableHtml);

                        // 2. Segmented Tables
                        if (chartData.segments && chartData.segments.length > 0) {
                            const segmentsHeader = `<h4 class="text-sm font-bold text-gray-700 dark:text-gray-300 mt-4 mb-4 border-t pt-6 uppercase tracking-wider">Breakdown Per ${segmentLabel}</h4>`;
                            container.insertAdjacentHTML('beforeend', segmentsHeader);
                            
                            const segmentsGrid = document.createElement('div');
                            segmentsGrid.className = 'grid grid-cols-1 gap-8';
                            
                            chartData.segments.forEach(seg => {
                                const segTableHtml = createGridTableHtml(seg.segmentLabel, chartData.columns, seg.rows, 'indigo');
                                segmentsGrid.insertAdjacentHTML('beforeend', segTableHtml);
                            });
                            
                            container.appendChild(segmentsGrid);
                        }
                    } else if (chartData.type === 'static_crosstab') {
                        // Static Crosstab Table
                        const tableHtml = createStaticCrosstabHtml(chartData.aggregate.title, chartData.aggregate.columns, chartData.aggregate.rows, chartData.aggregate.footer);
                        container.insertAdjacentHTML('beforeend', tableHtml);
                    } else {
                        // Regular Tables (radio, checkbox, etc.)
                        // 1. Aggregate Table (STIS)
                        const aggTableHtml = createTableHtml('Politeknik Statistika STIS', chartData.aggregate);
                        container.insertAdjacentHTML('beforeend', aggTableHtml);

                        // 2. Segmented Tables
                        if (chartData.segments && chartData.segments.length > 0) {
                            const segmentsHeader = `<h4 class="text-sm font-bold text-gray-700 dark:text-gray-300 mt-4 mb-4 border-t pt-6 uppercase tracking-wider">Breakdown Per ${segmentLabel}</h4>`;
                            container.insertAdjacentHTML('beforeend', segmentsHeader);
                            
                            const segmentsGrid = document.createElement('div');
                            segmentsGrid.className = 'grid grid-cols-1 lg:grid-cols-2 gap-6';
                            
                            chartData.segments.forEach(seg => {
                                const segTableHtml = createTableHtml(seg.segmentLabel, seg, 'indigo');
                                segmentsGrid.insertAdjacentHTML('beforeend', segTableHtml);
                            });
                            
                            container.appendChild(segmentsGrid);
                        }
                    }
                });
            }

            function createStaticCrosstabHtml(title, columns, rows, footer) {
                let colHeaders = '';
                columns.forEach(col => {
                    colHeaders += `<th class="px-4 py-2 border border-black text-center font-normal">${col}</th>`;
                });

                let bodyHtml = '';
                rows.forEach((row) => {
                    bodyHtml += `<tr>`;
                    bodyHtml += `<td class="px-4 py-2 border border-black">${row.label}</td>`;
                    row.data.forEach(val => {
                        bodyHtml += `<td class="px-4 py-2 border border-black text-center">${val}</td>`;
                    });
                    bodyHtml += `</tr>`;
                });

                let footerHtml = '';
                if (footer) {
                    footerHtml += `<tr>`;
                    footerHtml += `<td class="px-4 py-2 border border-black text-center">${footer.label}</td>`;
                    footer.data.forEach(val => {
                        footerHtml += `<td class="px-4 py-2 border border-black text-center">${val}</td>`;
                    });
                    footerHtml += `</tr>`;
                }

                return `
                    <div class="mb-6">
                        <div class="text-center mb-2">
                            <span class="text-sm text-black" style="font-family: 'Times New Roman', Times, serif;">${title}</span>
                        </div>
                        <div class="overflow-x-auto w-full max-w-full min-w-0 block">
                            <table class="mx-auto w-full max-w-4xl text-sm text-black border-collapse border border-black" style="font-family: 'Times New Roman', Times, serif;">
                                <thead class="bg-gray-100/50">
                                    <tr>
                                        ${colHeaders}
                                    </tr>
                                </thead>
                                <tbody>
                                    ${bodyHtml}
                                </tbody>
                                <tfoot>
                                    ${footerHtml}
                                </tfoot>
                            </table>
                        </div>
                    </div>
                `;
            }

            function createKompetensiTableHtml(rows, columns, title = null, maxSkala = null) {
                const headerTitle = title ? `<div class="px-4 py-3 bg-blue-50 dark:bg-blue-900/20 border-b border-blue-100 dark:border-blue-800"><h4 class="text-sm font-bold text-blue-700 dark:text-blue-300">${title}</h4></div>` : '';
                
                let colHeaders = '';
                columns.forEach(col => {
                    colHeaders += `<th class="px-4 py-2 border border-black text-center font-normal">${col}</th>`;
                });

                let bodyHtml = '';
                rows.forEach((row, index) => {
                    if (row.is_total) {
                        bodyHtml += `<tr>`;
                        bodyHtml += `<td colspan="2" class="px-4 py-2 border border-black text-center">Total</td>`;
                        row.percentages.forEach(p => {
                            bodyHtml += `<td class="px-4 py-2 border border-black text-center">${p.toFixed(2).replace('.', ',')}</td>`;
                        });
                        bodyHtml += `<td class="px-4 py-2 border border-black text-center">${row.skalaN.toFixed(2).replace('.', ',')}</td>`;
                        bodyHtml += `<td class="px-4 py-2 border border-black text-center">${row.skala100.toFixed(2).replace('.', ',')}</td>`;
                        bodyHtml += `</tr>`;
                    } else {
                        bodyHtml += `<tr>`;
                        bodyHtml += `<td class="px-4 py-2 border border-black text-center">${index + 1}</td>`;
                        bodyHtml += `<td class="px-4 py-2 border border-black">${row.label}</td>`;
                        row.percentages.forEach(p => {
                            bodyHtml += `<td class="px-4 py-2 border border-black text-center">${p.toFixed(2).replace('.', ',')}</td>`;
                        });
                        bodyHtml += `<td class="px-4 py-2 border border-black text-center">${row.skalaN.toFixed(2).replace('.', ',')}</td>`;
                        bodyHtml += `<td class="px-4 py-2 border border-black text-center">${row.skala100.toFixed(2).replace('.', ',')}</td>`;
                        bodyHtml += `</tr>`;
                    }
                });

                const maxSkalaValue = maxSkala || columns.length;

                return `
                    <div class="bg-white rounded-lg shadow-sm overflow-hidden mb-6">
                        ${headerTitle}
                        <div class="overflow-x-auto p-4 w-full max-w-full min-w-0 block">
                            <table class="w-full text-sm text-black border-collapse border border-black" style="font-family: 'Times New Roman', Times, serif;">
                                <thead>
                                    <tr>
                                        <th rowspan="2" class="px-4 py-2 border border-black text-center w-12 font-normal">No</th>
                                        <th rowspan="2" class="px-4 py-2 border border-black text-center font-normal">Jenis Kompetensi</th>
                                        <th colspan="${columns.length}" class="px-4 py-2 border border-black text-center font-normal">Tingkat Kompetensi (%)</th>
                                        <th colspan="2" class="px-4 py-2 border border-black text-center font-normal">Skor</th>
                                    </tr>
                                    <tr>
                                        ${colHeaders}
                                        <th class="px-4 py-2 border border-black text-center font-normal">Skala ${maxSkalaValue}</th>
                                        <th class="px-4 py-2 border border-black text-center font-normal">Skala 100</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    ${bodyHtml}
                                </tbody>
                            </table>
                        </div>
                    </div>
                `;
            }

            function createGridTableHtml(title, columns, rows, color = 'blue') {
                const colorClasses = {
                    blue: { bg: 'bg-blue-50 dark:bg-blue-900/20', text: 'text-blue-700 dark:text-blue-300', border: 'border-blue-100 dark:border-blue-800' },
                    indigo: { bg: 'bg-indigo-50 dark:bg-indigo-900/20', text: 'text-indigo-700 dark:text-indigo-300', border: 'border-indigo-100 dark:border-indigo-800' }
                }[color];

                let headerHtml = `<th class="px-4 py-3 text-[11px] font-bold uppercase tracking-wider text-gray-700 dark:text-gray-200 border-b bg-gray-100 dark:bg-slate-700">Aspek</th>`;
                columns.forEach(col => {
                    headerHtml += `<th class="px-4 py-3 text-[11px] font-bold uppercase tracking-wider text-gray-700 dark:text-gray-200 text-center border-b bg-gray-100 dark:bg-slate-700">${col}</th>`;
                });

                let bodyHtml = '';
                rows.forEach(row => {
                    const isTotal = row.is_total;
                    const rowClass = isTotal ? 'bg-gray-50 dark:bg-slate-700/50 font-bold border-t-2 border-gray-200' : 'border-b border-gray-100 dark:border-slate-700 hover:bg-gray-50 dark:hover:bg-slate-800/50 transition-colors';
                    
                    bodyHtml += `<tr class="${rowClass}">`;
                    bodyHtml += `<td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300 ${isTotal ? 'font-bold' : 'font-medium'}">${row.label}</td>`;
                    
                    row.percentages.forEach(p => {
                        bodyHtml += `<td class="px-4 py-3 text-sm text-center text-gray-800 dark:text-gray-200">${p.toFixed(2)}</td>`;
                    });
                    
                    bodyHtml += `</tr>`;
                });

                return `
                    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border ${colorClasses.border} overflow-hidden">
                        <div class="px-4 py-3 ${colorClasses.bg} border-b ${colorClasses.border} flex justify-between items-center">
                            <h4 class="text-sm font-bold ${colorClasses.text}">${title}</h4>
                        </div>
                        <div class="overflow-x-auto w-full max-w-full min-w-0 block">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr>${headerHtml}</tr>
                                </thead>
                                <tbody>
                                    ${bodyHtml}
                                </tbody>
                            </table>
                        </div>
                    </div>
                `;
            }

            function createTableHtml(title, data, color = 'blue') {
                const colorClasses = {
                    blue: {
                        bg: 'bg-blue-50 dark:bg-blue-900/20',
                        text: 'text-blue-700 dark:text-blue-300',
                        border: 'border-blue-100 dark:border-blue-800',
                        header: 'bg-blue-600 text-white'
                    },
                    indigo: {
                        bg: 'bg-indigo-50 dark:bg-indigo-900/20',
                        text: 'text-indigo-700 dark:text-indigo-300',
                        border: 'border-indigo-100 dark:border-indigo-800',
                        header: 'bg-indigo-600 text-white'
                    }
                }[color];

                let rowsHtml = '';
                data.labels.forEach((label, idx) => {
                    const count = data.counts[idx];
                    const percentage = data.percentages[idx];
                    rowsHtml += `
                        <tr class="border-b border-gray-100 dark:border-slate-700 hover:bg-gray-50 dark:hover:bg-slate-800/50 transition-colors">
                            <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300 font-medium">${label}</td>
                            <td class="px-4 py-3 text-sm text-center text-gray-600 dark:text-gray-400">${count}</td>
                            <td class="px-4 py-3 text-sm text-right">
                                <div class="flex items-center justify-end gap-3">
                                    <div class="flex-grow max-w-[100px] h-1.5 bg-gray-100 dark:bg-gray-700 rounded-full overflow-hidden hidden sm:block">
                                        <div class="h-full bg-indigo-500 rounded-full" style="width: ${percentage}%"></div>
                                    </div>
                                    <span class="font-bold text-gray-800 dark:text-gray-200">${percentage.toFixed(1)}%</span>
                                </div>
                            </td>
                        </tr>
                    `;
                });

                return `
                    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border ${colorClasses.border} overflow-hidden">
                        <div class="px-4 py-3 ${colorClasses.bg} border-b ${colorClasses.border} flex justify-between items-center">
                            <h4 class="text-sm font-bold ${colorClasses.text}">${title}</h4>
                            <span class="text-xs font-medium px-2 py-0.5 bg-white/50 dark:bg-black/20 rounded-full">Total: ${data.total}</span>
                        </div>
                        <div class="overflow-x-auto w-full max-w-full min-w-0 block">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="bg-gray-50 dark:bg-slate-700/50">
                                        <th class="px-4 py-2 text-[10px] font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">Opsi Jawaban</th>
                                        <th class="px-4 py-2 text-[10px] font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400 text-center">Jumlah</th>
                                        <th class="px-4 py-2 text-[10px] font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400 text-right">Persentase</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    ${rowsHtml}
                                </tbody>
                                <tfoot>
                                    <tr class="bg-gray-50/50 dark:bg-slate-700/30 font-bold border-t-2 border-gray-100 dark:border-slate-700">
                                        <td class="px-4 py-3 text-sm text-gray-800 dark:text-gray-200">TOTAL</td>
                                        <td class="px-4 py-3 text-sm text-center text-gray-800 dark:text-gray-200">${data.total}</td>
                                        <td class="px-4 py-3 text-sm text-right text-gray-800 dark:text-gray-200">100.0%</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                `;
            }

            // Initial render
            if (initialPayload) {
                renderAnalitikTables(initialPayload);
            }
        });
    </script>
    @endpush
@endsection
