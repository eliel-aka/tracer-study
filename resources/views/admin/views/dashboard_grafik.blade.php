@extends('admin.layouts.app')
@section('title', 'Dashboard Visualisasi Survei')

@section('content')
    <div class="w-full px-6 py-6 mx-auto">
        <div class="flex flex-wrap -mx-3">
            <div class="w-full px-3">
                <div class="relative flex flex-col min-w-0 break-words bg-white border-0 border-transparent border-solid shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
                    <div class="p-6 pb-0 mb-0 border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                        <div class="flex justify-between items-center mb-3">
                            <h6 class="dark:text-white text-sm font-bold">Visualisasi Hasil Survei</h6>
                            <a href="{{ route('admin.dashboard.analitik', $survey->id) }}" class="inline-flex items-center px-3 py-1.5 text-xs font-bold rounded-lg border border-indigo-600 text-indigo-600 hover:bg-indigo-50 dark:text-indigo-400 dark:border-indigo-400 transition-colors">
                                <i class="fas fa-table mr-2"></i> Lihat Tabel Analitik
                            </a>
                        </div>

                        <form id="dashboardFilterForm" class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-3 items-end w-full" action="{{ route('admin.dashboard.grafik', $survey->id) }}" method="GET">
                            <div>
                                <label class="block text-xs mb-1 text-slate-500 dark:text-slate-300">Survei</label>
                                <select id="surveySelect" class="text-[11px] sm:text-xs w-full rounded-lg border border-solid border-gray-300 dark:bg-slate-850 dark:text-white bg-white py-1.5 sm:py-2 px-3 text-gray-700 focus:border-blue-500 focus:outline-none">
                                    @foreach ($allSurveys as $option)
                                        <option value="{{ $option->id }}" {{ (string) $survey->id === (string) $option->id ? 'selected' : '' }}>{{ $option->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs mb-1 text-slate-500 dark:text-slate-300">Breakdown Grafik</label>
                                <select id="segmentDimensionSelect" name="breakdown_by" class="text-[11px] sm:text-xs w-full rounded-lg border border-solid border-gray-300 dark:bg-slate-850 dark:text-white bg-white py-1.5 sm:py-2 px-3 text-gray-700 focus:border-blue-500 focus:outline-none">
                                    <option value="prodi" {{ $selectedSegmentDimension === 'prodi' ? 'selected' : '' }}>Program Studi</option>
                                    <option value="tahun_lulus" {{ $selectedSegmentDimension === 'tahun_lulus' ? 'selected' : '' }}>Tahun Lulus</option>
                                </select>
                            </div>
                            <div class="flex items-center justify-end gap-2 mt-2 sm:mt-0">
                                <a id="resetFilterBtn" href="{{ route('admin.dashboard.grafik', $survey->id) }}" class="text-center px-3 sm:px-4 py-2 text-[11px] sm:text-xs font-bold rounded-lg border border-gray-300 text-slate-600 hover:bg-slate-100 dark:text-white dark:border-slate-600 dark:hover:bg-slate-700 whitespace-nowrap">Reset</a>
                                <button type="submit" class="px-3 sm:px-4 py-2 text-[11px] sm:text-xs font-bold rounded-lg bg-blue-600 text-white hover:bg-blue-700 whitespace-nowrap">Terapkan Filter</button>
                            </div>
                            <div id="dashboardFilterValidation" class="sm:col-span-2 xl:col-span-4 text-xs text-red-500"></div>
                        </form>


                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                            <div class="bg-blue-50 dark:bg-blue-900 rounded-lg p-4">
                                <h4 class="text-sm font-semibold text-blue-600 dark:text-blue-300">Total Responden</h4>
                                <p id="summaryTotalResponses" class="text-2xl font-bold text-blue-800 dark:text-blue-100">{{ $initialChartPayload['summary']['totalResponses'] ?? 0 }}</p>
                            </div>
                            <div class="bg-green-50 dark:bg-green-900 rounded-lg p-4">
                                <h4 class="text-sm font-semibold text-green-600 dark:text-green-300">Responden Selesai</h4>
                                <p id="summaryCompletedResponses" class="text-2xl font-bold text-green-800 dark:text-green-100">{{ $initialChartPayload['summary']['completedResponses'] ?? 0 }}</p>
                            </div>
                            <div class="bg-purple-50 dark:bg-purple-900 rounded-lg p-4">
                                <h4 class="text-sm font-semibold text-purple-600 dark:text-purple-300">Tingkat Penyelesaian</h4>
                                <p id="summaryCompletionRate" class="text-2xl font-bold text-purple-800 dark:text-purple-100">{{ $initialChartPayload['summary']['completionRate'] ?? 0 }}%</p>
                            </div>
                        </div>
                    </div>

                    <div class="flex-auto px-0 pt-0 pb-2">
                        <div class="p-6">
                            @if ($questions->isEmpty())
                                <div class="text-center text-sm text-slate-500 dark:text-slate-300 py-6">
                                    Pertanyaan dengan visualisasi belum tersedia untuk survei ini.
                                </div>
                            @endif

                            <div id="chartsContainer">
                                @foreach ($questions as $question)
                                    <div class="dashboard-question-section mb-10 border-b border-gray-200 dark:border-gray-700 pb-8"
                                         data-question-id="{{ $question->id }}">
                                        <div class="mb-4 flex justify-between items-start">
                                            <div>
                                                <h3 class="text-lg font-semibold dark:text-white">{{ $question->pertanyaan }}</h3>
                                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $question->blok }}</p>
                                            </div>
                                        </div>

                                        <div id="loading-{{ $question->id }}" class="text-center py-4 text-gray-600 dark:text-gray-400">
                                            <div class="animate-spin inline-block w-6 h-6 border-[3px] border-current border-t-transparent text-blue-600 dark:text-blue-500 rounded-full" role="status"><span class="sr-only">Loading...</span></div>
                                            <p class="mt-2">Memuat data chart...</p>
                                        </div>
                                        <div id="error-{{ $question->id }}" class="text-red-500 dark:text-red-400 text-center hidden">
                                            <i class="fas fa-exclamation-circle mr-2"></i>Error loading chart data
                                        </div>
                                        <div id="empty-{{ $question->id }}" class="text-slate-500 dark:text-slate-300 text-center hidden py-6 text-sm">
                                            Data tidak tersedia untuk filter yang dipilih.
                                        </div>

                                        <div id="charts-grid-{{ $question->id }}" class="grid grid-cols-1 md:grid-cols-2 gap-6 hidden">
                                            <div class="bg-white dark:bg-slate-800 p-4 rounded-lg shadow">
                                                <div class="flex justify-between items-center mb-2">
                                                    <h4 id="agg-title-{{ $question->id }}" class="text-base font-bold text-center w-full dark:text-white">Politeknik Statistika STIS</h4>
                                                    <button onclick="downloadCanvasChart('agg-chart-{{ $question->id }}', 'Politeknik Statistika STIS')" class="ml-2 px-2 py-1 text-xs text-blue-600 dark:text-blue-400 hover:text-blue-800 border border-blue-600 dark:border-blue-400 rounded hover:bg-blue-50 dark:hover:bg-blue-900" title="Download"><i class="fas fa-download"></i></button>
                                                </div>
                                                <div style="position:relative; height:320px;">
                                                    <canvas id="agg-chart-{{ $question->id }}" class="p-2"></canvas>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @php
        $dashboardChartPayload = [
            'selectedSurveyId' => (string) $survey->id,
            'selectedSegmentDimension' => $selectedSegmentDimension,
            'chartsDataUrlBase' => url('admin/dashboard/charts-data'),
            'initialPayload' => $initialChartPayload,
        ];
    @endphp

    <script id="dashboardChartPayload" type="application/json">{!! json_encode($dashboardChartPayload) !!}</script>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.2.0/dist/chartjs-plugin-datalabels.min.js"></script>
        <script>
            // ─── ROOT CAUSE FIX #1 ──────────────────────────────────────────────────────
            // ChartDataLabels WAJIB di-register secara global SEBELUM chart apapun dibuat.
            Chart.register(ChartDataLabels);

            const DP = JSON.parse(document.getElementById('dashboardChartPayload').textContent || '{}');
            const chartInstances = new Map();
            const chartDataCache = new Map();
            const chartsDataUrlBase = DP.chartsDataUrlBase || '';
            let selectedSurveyId = String(DP.selectedSurveyId || '');
            let selectedSegmentDimension = DP.selectedSegmentDimension || 'prodi';

            const questionSections = Array.from(document.querySelectorAll('.dashboard-question-section'));

            const CHART_PALETTE = [
                '#4285F4', '#EA4335', '#FBBC04', '#34A853',
                '#FF6D01', '#46BDC6', '#7B61FF', '#E91E63',
                '#00ACC1', '#8D6E63'
            ];
            let refreshRequestId = 0;

            function hideEl(id) { const e = document.getElementById(id); if (e) e.style.display = 'none'; }
            function showEl(id) { const e = document.getElementById(id); if (e) e.style.display = 'block'; }
            function hideElClass(id) { const e = document.getElementById(id); if (e) e.classList.add('hidden'); }
            function showElClass(id) { const e = document.getElementById(id); if (e) e.classList.remove('hidden'); }

            function destroyChart(canvasId) {
                if (chartInstances.has(canvasId)) {
                    chartInstances.get(canvasId).destroy();
                    chartInstances.delete(canvasId);
                }
            }

            function downloadCanvasChart(canvasId, title) {
                const chart = chartInstances.get(canvasId);
                if (!chart) return alert('Chart belum dimuat.');
                const c = document.createElement('canvas');
                const ctx = c.getContext('2d');
                c.width = chart.canvas.width; c.height = chart.canvas.height;
                ctx.fillStyle = '#ffffff'; ctx.fillRect(0, 0, c.width, c.height);
                ctx.drawImage(chart.canvas, 0, 0);
                const a = document.createElement('a');
                a.download = `chart-${title.replace(/[^a-zA-Z0-9]/g, '_')}.png`;
                a.href = c.toDataURL('image/png'); a.click();
            }

            function createChartOnCanvas(canvasId, chartType, labels, percentages, counts, title) {
                destroyChart(canvasId);
                const canvas = document.getElementById(canvasId);
                if (!canvas) return;

                const ctx = canvas.getContext('2d');
                const isDark = document.documentElement.classList.contains('dark');
                if (isDark) ctx.canvas.style.backgroundColor = 'rgb(30, 41, 59)';
                else ctx.canvas.style.backgroundColor = '#ffffff';

                const bgColors = labels.map((_, i) => CHART_PALETTE[i % CHART_PALETTE.length]);
                const isPie = chartType === 'pie' || chartType === 'doughnut';
                const maxPct = percentages.length > 0 ? Math.max(...percentages) : 0;

                const config = {
                    type: chartType,
                    data: {
                        labels: labels,
                        datasets: [{
                            label: title,
                            data: percentages,
                            backgroundColor: bgColors,
                            borderWidth: isPie ? 2 : 0,
                            borderColor: isPie ? '#fff' : undefined,
                            categoryPercentage: 0.8,
                            barPercentage: 0.9,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        layout: {
                            padding: isPie
                                ? { top: 10, bottom: 10, left: 10, right: 10 }
                                : { top: 35, bottom: 5, left: 5, right: 5 },
                        },
                        animation: { duration: 400, easing: 'easeOutQuart' },
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    padding: 14, usePointStyle: true,
                                    font: { size: 11 },
                                    color: isDark ? '#fff' : '#333',
                                    generateLabels: function(chart) {
                                        const data = chart.data;
                                        return data.labels.map((label, i) => ({
                                            text: label,
                                            fillStyle: data.datasets[0].backgroundColor[i],
                                            strokeStyle: data.datasets[0].backgroundColor[i],
                                            hidden: false,
                                            index: i,
                                            pointStyle: 'rectRounded',
                                        }));
                                    }
                                },
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(ctx) {
                                        const pct = Number(ctx.raw || 0).toFixed(2);
                                        const count = counts[ctx.dataIndex] || 0;
                                        return `${ctx.label}: ${pct}% (${count} responden)`;
                                    }
                                },
                                backgroundColor: 'rgba(0,0,0,0.85)', padding: 10,
                            },
                            title: {
                                display: true,
                                text: title,
                                font: { size: 16, weight: 'bold' },
                                color: isDark ? '#fff' : '#1e293b',
                                padding: { bottom: 25 },
                            },
                            datalabels: isPie ? {
                                display: true,
                                anchor: 'center', align: 'center',
                                color: '#ffffff',
                                font: { weight: 'bold', size: 13 },
                                formatter: function(value) {
                                    if (value === null || value === undefined || value <= 0) return '';
                                    return value.toFixed(2).replace('.', ',') + '%';
                                },
                                textShadowColor: 'rgba(0,0,0,0.6)', textShadowBlur: 4,
                            } : {
                                display: true,
                                anchor: 'end', align: 'top',
                                offset: 4,
                                color: isDark ? '#e2e8f0' : '#1e293b',
                                font: { weight: 'bold', size: 12 },
                                formatter: function(value) {
                                    if (value === null || value === undefined || value <= 0) return '';
                                    return value.toFixed(2).replace('.', ',') + '%';
                                },
                            },
                        },
                        scales: isPie ? undefined : {
                            y: {
                                beginAtZero: true,
                                suggestedMax: maxPct + 15,
                                grid: { color: isDark ? 'rgba(255,255,255,0.1)' : 'rgba(0,0,0,0.08)' },
                                ticks: {
                                    color: isDark ? '#fff' : '#333',
                                    callback: function(v) { return v + '%'; },
                                },
                            },
                            x: {
                                grid: { color: isDark ? 'rgba(255,255,255,0.1)' : 'rgba(0,0,0,0.08)' },
                                ticks: { color: isDark ? '#fff' : '#333' },
                            },
                        },
                    },
                };

                chartInstances.set(canvasId, new Chart(ctx, config));
            }

            function createSalaryChartOnCanvas(canvasId, labels, averages, counts, title) {
                destroyChart(canvasId);
                const canvas = document.getElementById(canvasId);
                if (!canvas) return;

                const ctx = canvas.getContext('2d');
                const isDark = document.documentElement.classList.contains('dark');
                if (isDark) ctx.canvas.style.backgroundColor = 'rgb(30, 41, 59)';
                else ctx.canvas.style.backgroundColor = '#ffffff';

                const bgColors = labels.map((_, i) => CHART_PALETTE[i % CHART_PALETTE.length]);
                const maxVal = averages.length > 0 ? Math.max(...averages.filter(v => v > 0)) : 0;

                const config = {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Rata-rata Gaji',
                            data: averages,
                            backgroundColor: bgColors,
                            borderWidth: 0, borderRadius: 4,
                            categoryPercentage: 0.8, barPercentage: 0.9,
                        }]
                    },
                    options: {
                        responsive: true, maintainAspectRatio: false,
                        layout: { padding: { top: 40, bottom: 5, left: 5, right: 5 } },
                        animation: { duration: 400, easing: 'easeOutQuart' },
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                callbacks: {
                                    label: function(ctx) {
                                        const val = ctx.raw || 0;
                                        const count = counts[ctx.dataIndex] || 0;
                                        return `Rata-rata: Rp ${new Intl.NumberFormat('id-ID').format(val)} (${count} responden)`;
                                    }
                                },
                                backgroundColor: 'rgba(0,0,0,0.85)', padding: 10,
                            },
                            title: {
                                display: true, text: title,
                                font: { size: 16, weight: 'bold' },
                                color: isDark ? '#fff' : '#1e293b',
                                padding: { bottom: 35 },
                            },
                            datalabels: {
                                display: true,
                                anchor: 'end', align: 'top',
                                offset: 4,
                                color: isDark ? '#e2e8f0' : '#1e293b',
                                font: { weight: 'bold', size: 12 },
                                formatter: function(v) {
                                    if (v === null || v === undefined || v <= 0) return '';
                                    return new Intl.NumberFormat('id-ID').format(Math.round(v));
                                },
                            },
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                suggestedMax: maxVal > 0 ? maxVal * 1.2 : undefined,
                                grid: { color: isDark ? 'rgba(255,255,255,0.1)' : 'rgba(0,0,0,0.08)' },
                                ticks: {
                                    color: isDark ? '#fff' : '#333',
                                    callback: function(v) {
                                        return 'Rp ' + new Intl.NumberFormat('id-ID', { notation: 'compact' }).format(v);
                                    },
                                },
                            },
                            x: {
                                grid: { display: false },
                                ticks: { color: isDark ? '#fff' : '#333', font: { size: 11, weight: '500' } },
                            },
                        },
                    },
                };

                chartInstances.set(canvasId, new Chart(ctx, config));
            }

            function createStackedBarChartOnCanvas(canvasId, labels, datasets, title) {
                destroyChart(canvasId);
                const canvas = document.getElementById(canvasId);
                if (!canvas) return;

                const ctx = canvas.getContext('2d');
                const isDark = document.documentElement.classList.contains('dark');
                if (isDark) ctx.canvas.style.backgroundColor = 'rgb(30, 41, 59)';
                else ctx.canvas.style.backgroundColor = '#ffffff';

                datasets.forEach((ds, i) => {
                    ds.backgroundColor = CHART_PALETTE[i % CHART_PALETTE.length];
                    ds.borderWidth = 0;
                    ds.categoryPercentage = 0.6;
                    ds.barPercentage = 0.9;
                });

                const config = {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: datasets
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        layout: { padding: { top: 35, bottom: 5, left: 5, right: 5 } },
                        animation: { duration: 400, easing: 'easeOutQuart' },
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    padding: 14, usePointStyle: true,
                                    font: { size: 11 },
                                    color: isDark ? '#fff' : '#333',
                                },
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(ctx) {
                                        const count = ctx.raw || 0;
                                        return `${ctx.dataset.label}: ${count} responden`;
                                    }
                                },
                                backgroundColor: 'rgba(0,0,0,0.85)', padding: 10,
                            },
                            title: {
                                display: true, text: title,
                                font: { size: 16, weight: 'bold' },
                                color: isDark ? '#fff' : '#1e293b',
                                padding: { bottom: 25 },
                            },
                            datalabels: {
                                display: true,
                                anchor: 'center', align: 'center',
                                color: '#ffffff',
                                font: { weight: 'bold', size: 12 },
                                formatter: function(value) {
                                    if (value === null || value === undefined || value <= 0) return '';
                                    return value;
                                },
                                textShadowColor: 'rgba(0,0,0,0.6)', textShadowBlur: 3,
                            },
                        },
                        scales: {
                            y: {
                                stacked: true,
                                beginAtZero: true,
                                grid: { color: isDark ? 'rgba(255,255,255,0.1)' : 'rgba(0,0,0,0.08)' },
                                ticks: { color: isDark ? '#fff' : '#333' },
                            },
                            x: {
                                stacked: true,
                                grid: { display: false },
                                ticks: { color: isDark ? '#fff' : '#333', font: { size: 12, weight: '500' } },
                            },
                        },
                    },
                };

                chartInstances.set(canvasId, new Chart(ctx, config));
            }

            function renderQuestionCharts(qId, chartData) {
                const grid = document.getElementById(`charts-grid-${qId}`);
                if (!grid) return;

                if (chartData.type === 'average_card') {
                    if (!chartData.aggregate || chartData.aggregate.count === 0) {
                        grid.classList.add('hidden');
                        showElClass(`empty-${qId}`);
                        return;
                    }

                    hideElClass(`empty-${qId}`);
                    hideElClass(`error-${qId}`);
                    grid.classList.remove('hidden');

                    grid.querySelectorAll('.seg-card-dynamic').forEach(el => el.remove());
                    grid.classList.remove('md:grid-cols-2');
                    grid.classList.add('md:grid-cols-1');

                    const aggTitle = document.getElementById(`agg-title-${qId}`);
                    if (aggTitle) {
                        aggTitle.textContent = 'Rata-rata Pendapatan';
                        const titleRow = aggTitle.parentElement;
                        titleRow.classList.remove('hidden');
                        const downloadBtn = titleRow.querySelector('button');
                        if (downloadBtn) {
                            downloadBtn.onclick = () => downloadCanvasChart(`agg-chart-${qId}`, 'Rata-rata Pendapatan');
                        }
                    }

                    const aggContainer = document.getElementById(`agg-chart-${qId}`)
                        ? document.getElementById(`agg-chart-${qId}`).parentElement
                        : null;
                    if (aggContainer) {
                        if (!document.getElementById(`agg-chart-${qId}`)) {
                            aggContainer.innerHTML = `<canvas id="agg-chart-${qId}" class="p-2"></canvas>`;
                        }
                        aggContainer.style.height = '480px';
                    }

                    const segments = chartData.segments || [];
                    const labels   = segments.map(s => s.segmentLabel);
                    const averages = segments.map(s => s.average);
                    const counts   = segments.map(s => s.count);

                    labels.push(chartData.aggregate.title);
                    averages.push(chartData.aggregate.average);
                    counts.push(chartData.aggregate.count);

                    createSalaryChartOnCanvas(`agg-chart-${qId}`, labels, averages, counts, 'Rata-rata Pendapatan');
                    return;
                }

                if (chartData.type === 'stacked_bar') {
                    if (!chartData.aggregate || !chartData.aggregate.labels || chartData.aggregate.labels.length === 0) {
                        grid.classList.add('hidden');
                        showElClass(`empty-${qId}`);
                        return;
                    }

                    hideElClass(`empty-${qId}`);
                    hideElClass(`error-${qId}`);
                    grid.classList.remove('hidden');

                    grid.querySelectorAll('.seg-card-dynamic').forEach(el => el.remove());
                    grid.classList.remove('md:grid-cols-2');
                    grid.classList.add('md:grid-cols-1');

                    const aggTitle = document.getElementById(`agg-title-${qId}`);
                    if (aggTitle) {
                        aggTitle.textContent = chartData.aggregate.title;
                        const titleRow = aggTitle.parentElement;
                        titleRow.classList.remove('hidden');
                        const downloadBtn = titleRow.querySelector('button');
                        if (downloadBtn) {
                            downloadBtn.onclick = () => downloadCanvasChart(`agg-chart-${qId}`, chartData.aggregate.title);
                        }
                    }

                    const aggContainer = document.getElementById(`agg-chart-${qId}`)
                        ? document.getElementById(`agg-chart-${qId}`).parentElement
                        : null;
                    if (aggContainer) {
                        if (!document.getElementById(`agg-chart-${qId}`)) {
                            aggContainer.innerHTML = `<canvas id="agg-chart-${qId}" class="p-2"></canvas>`;
                        }
                        aggContainer.style.height = '480px';
                    }

                    createStackedBarChartOnCanvas(`agg-chart-${qId}`, chartData.aggregate.labels, chartData.aggregate.datasets, chartData.aggregate.title);
                    return;
                }

                grid.classList.remove('md:grid-cols-1');
                grid.classList.add('md:grid-cols-2');
                const aggTitleRow = document.getElementById(`agg-title-${qId}`)
                    ? document.getElementById(`agg-title-${qId}`).parentElement
                    : null;
                if (aggTitleRow) {
                    aggTitleRow.classList.remove('hidden');
                    const downloadBtn = aggTitleRow.querySelector('button');
                    if (downloadBtn) {
                        downloadBtn.onclick = () => downloadCanvasChart(`agg-chart-${qId}`, 'Politeknik Statistika STIS');
                    }
                }

                const labels = chartData.aggregate?.labels || [];
                if (labels.length === 0) {
                    grid.classList.add('hidden');
                    showElClass(`empty-${qId}`);
                    return;
                }

                hideElClass(`empty-${qId}`);
                hideElClass(`error-${qId}`);
                grid.classList.remove('hidden');

                grid.querySelectorAll('.seg-card-dynamic').forEach(el => el.remove());

                const aggContainer = document.getElementById(`agg-title-${qId}`)
                    ? document.getElementById(`agg-title-${qId}`).parentElement.nextElementSibling
                    : null;
                if (aggContainer && !aggContainer.querySelector('canvas')) {
                    aggContainer.innerHTML = `<canvas id="agg-chart-${qId}" class="p-2"></canvas>`;
                }

                createChartOnCanvas(
                    `agg-chart-${qId}`, chartData.type,
                    chartData.aggregate.labels, chartData.aggregate.percentages,
                    chartData.aggregate.counts, chartData.aggregate.title
                );

                const segments = chartData.segments || [];
                segments.forEach((seg, idx) => {
                    const cardId   = `seg-card-${qId}-${idx}`;
                    const canvasId = `seg-chart-${qId}-${idx}`;
                    let card = document.getElementById(cardId);
                    if (!card) {
                        card = document.createElement('div');
                        card.id = cardId;
                        card.className = 'bg-white dark:bg-slate-800 p-4 rounded-lg shadow seg-card-dynamic';
                        card.innerHTML = `
                            <div class="flex justify-between items-center mb-2">
                                <h4 class="text-base font-bold text-center w-full dark:text-white">${seg.segmentLabel}</h4>
                                <button onclick="downloadCanvasChart('${canvasId}', '${seg.segmentLabel.replace(/'/g, "\\'")}')" class="ml-2 px-2 py-1 text-xs text-blue-600 dark:text-blue-400 hover:text-blue-800 border border-blue-600 dark:border-blue-400 rounded hover:bg-blue-50 dark:hover:bg-blue-900" title="Download"><i class="fas fa-download"></i></button>
                            </div>
                            <div style="position:relative; height:320px;">
                                <canvas id="${canvasId}" class="p-2"></canvas>
                            </div>`;
                        grid.appendChild(card);
                    }
                    createChartOnCanvas(canvasId, chartData.type, seg.labels, seg.percentages, seg.counts, seg.segmentLabel);
                });
            }

            function getCacheKey() {
                return `${selectedSurveyId}|${selectedSegmentDimension}`;
            }

            async function fetchChartsData(force = false) {
                const key = getCacheKey();
                if (!force && chartDataCache.has(key)) return chartDataCache.get(key);
                const params = new URLSearchParams();
                if (selectedSegmentDimension) params.set('breakdown_by', selectedSegmentDimension);
                const url = `${chartsDataUrlBase}/${selectedSurveyId}${params.toString() ? '?' + params : ''}`;
                const res = await fetch(url, { headers: { 'Accept': 'application/json' }, credentials: 'same-origin' });
                if (!res.ok) throw new Error('Network error');
                const data = await res.json();
                chartDataCache.set(key, data);
                return data;
            }

            function updateSummary(s) {
                const safe = s || {};
                const totalEl = document.getElementById('summaryTotalResponses');
                const completedEl = document.getElementById('summaryCompletedResponses');
                const rateEl = document.getElementById('summaryCompletionRate');
                if (totalEl) totalEl.textContent = safe.totalResponses || 0;
                if (completedEl) completedEl.textContent = safe.completedResponses || 0;
                if (rateEl) rateEl.textContent = `${Number(safe.completionRate || 0).toFixed(1)}%`;
            }

            async function refreshCharts(force = false) {
                const requestId = ++refreshRequestId;
                const validationEl = document.getElementById('dashboardFilterValidation');
                if (validationEl) validationEl.textContent = '';
                if (force) chartDataCache.delete(getCacheKey());

                questionSections.forEach(sec => {
                    const qId = sec.dataset.questionId;
                    showEl(`loading-${qId}`);
                    hideElClass(`error-${qId}`);
                    hideElClass(`empty-${qId}`);
                    const grid = document.getElementById(`charts-grid-${qId}`);
                    if (grid) grid.classList.add('hidden');
                });

                try {
                    const payload = await fetchChartsData(force);
                    if (requestId !== refreshRequestId) return;
                    updateSummary(payload.summary);
                    questionSections.forEach(sec => {
                        const qId = sec.dataset.questionId;
                        hideEl(`loading-${qId}`);
                        const chartData = (payload.charts || {})[qId];
                        if (chartData) {
                            renderQuestionCharts(qId, chartData);
                        } else {
                            const grid = document.getElementById(`charts-grid-${qId}`);
                            if (grid) grid.classList.add('hidden');
                            showElClass(`empty-${qId}`);
                        }
                    });
                    const params = new URLSearchParams();
                    if (selectedSegmentDimension) params.set('breakdown_by', selectedSegmentDimension);
                    const basePath = `${window.location.origin}/admin/dashboard/grafik/${selectedSurveyId}`;
                    window.history.replaceState({}, '', params.toString() ? `${basePath}?${params}` : basePath);
                } catch (e) {
                    if (requestId !== refreshRequestId) return;
                    if (validationEl) validationEl.textContent = 'Gagal memuat visualisasi. Silakan coba lagi.';
                    questionSections.forEach(sec => {
                        const qId = sec.dataset.questionId;
                        hideEl(`loading-${qId}`);
                        showElClass(`error-${qId}`);
                    });
                }
            }

            let refreshTimer = null;
            function scheduleRefresh(force = false) {
                if (refreshTimer) clearTimeout(refreshTimer);
                refreshTimer = setTimeout(() => refreshCharts(force), 100);
            }

            document.addEventListener('DOMContentLoaded', () => {
                const initialKey = getCacheKey();
                if (DP.initialPayload) chartDataCache.set(initialKey, DP.initialPayload);
                const surveySelect = document.getElementById('surveySelect');
                if (surveySelect) {
                    surveySelect.addEventListener('change', function() {
                        if (this.value) window.location.href = `{{ url('admin/dashboard/grafik') }}/${this.value}`;
                    });
                }
                const segmentSelect = document.getElementById('segmentDimensionSelect');
                if (segmentSelect) {
                    segmentSelect.addEventListener('change', function() {
                        selectedSegmentDimension = this.value || 'prodi';
                        scheduleRefresh(false);
                    });
                }
                const filterForm = document.getElementById('dashboardFilterForm');
                if (filterForm) {
                    filterForm.addEventListener('submit', function(e) {
                        e.preventDefault();
                        scheduleRefresh(true);
                    });
                }
                refreshCharts(false);
            });

            new MutationObserver(() => {
                chartInstances.forEach(c => { if (c) c.destroy(); });
                chartInstances.clear();
                chartDataCache.clear();
                refreshCharts(false);
            }).observe(document.documentElement, { attributes: true, attributeFilter: ['class'] });
        </script>
    @endpush
@endsection