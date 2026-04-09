@extends('admin.layouts.app')
@section('title', 'Monitoring Survei')

@section('content')
    <!-- table 1 -->
    <div class="flex flex-wrap -mx-3">
        <div class="flex-none w-full max-w-full px-3">
            <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
                
                <!-- Header & Filter -->
                <div class="p-4 pb-0 mb-0 border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                    <h6 class="dark:text-white mb-3 text-sm font-bold">Daftar Survei</h6>
                    <div class="relative flex items-center w-full">
                        <div class="relative flex items-stretch w-full">
                            <!-- Form Filter: Grid responsive -->
                            <form id="monitoringFilterForm" action="{{ route('admin.monitoring.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-5 gap-3 items-end w-full">
                                <div>
                                    <label class="block text-xs mb-1 text-slate-500 dark:text-slate-300">Cari Survei</label>
                                    <div class="relative">
                                        <span class="text-sm ease leading-5.6 absolute z-50 flex h-full items-center whitespace-nowrap rounded-lg rounded-tr-none rounded-br-none border border-r-0 border-transparent bg-transparent py-2 px-2.5 text-center font-normal text-slate-500 transition-all">
                                            <i class="fas fa-search"></i>
                                        </span>
                                        <input type="text" name="search" value="{{ request('search') }}"
                                            class="pl-9 text-[11px] sm:text-xs focus:shadow-primary-outline ease w-full leading-5.6 relative block min-w-0 flex-auto rounded-lg border border-solid border-gray-300 dark:bg-slate-850 dark:text-white bg-white bg-clip-padding py-1.5 sm:py-2 pr-3 text-gray-700 transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none focus:transition-shadow"
                                            placeholder="Nama atau tipe survei" />
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-xs mb-1 text-slate-500 dark:text-slate-300">Survei</label>
                                    <select id="surveySelect" name="survey_id"
                                        class="text-[11px] sm:text-xs w-full rounded-lg border border-solid border-gray-300 dark:bg-slate-850 dark:text-white bg-white py-1.5 sm:py-2 px-3 text-gray-700 focus:border-blue-500 focus:outline-none">
                                        <option value="">Semua Survei</option>
                                        @foreach ($surveyOptions as $option)
                                            <option value="{{ $option->id }}"
                                                {{ (string) $selectedSurveyId === (string) $option->id ? 'selected' : '' }}>
                                                {{ $option->nama }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs mb-1 text-slate-500 dark:text-slate-300">Tahun Lulus</label>
                                    <select id="graduationYearSelect" name="tahun_lulus"
                                        class="text-[11px] sm:text-xs w-full rounded-lg border border-solid border-gray-300 dark:bg-slate-850 dark:text-white bg-white py-1.5 sm:py-2 px-3 text-gray-700 focus:border-blue-500 focus:outline-none">
                                        <option value="">Semua Tahun</option>
                                        @foreach ($graduationYears as $year)
                                            <option value="{{ $year }}"
                                                {{ (string) $selectedGraduationYear === (string) $year ? 'selected' : '' }}>
                                                {{ $year }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs mb-1 text-slate-500 dark:text-slate-300">Program Studi</label>
                                    <select id="studyProgramSelect" name="prodi"
                                        class="text-[11px] sm:text-xs w-full rounded-lg border border-solid border-gray-300 dark:bg-slate-850 dark:text-white bg-white py-1.5 sm:py-2 px-3 text-gray-700 focus:border-blue-500 focus:outline-none">
                                        <option value="">Semua Prodi</option>
                                        @foreach ($studyPrograms as $program)
                                            <option value="{{ $program }}"
                                                {{ (string) $selectedStudyProgram === (string) $program ? 'selected' : '' }}>
                                                {{ $program }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs mb-1 text-slate-500 dark:text-slate-300">Jenis Visualisasi</label>
                                    <select name="chart_type"
                                        class="text-[11px] sm:text-xs w-full rounded-lg border border-solid border-gray-300 dark:bg-slate-850 dark:text-white bg-white py-1.5 sm:py-2 px-3 text-gray-700 focus:border-blue-500 focus:outline-none">
                                        <option value="bar" {{ $selectedChartType === 'bar' ? 'selected' : '' }}>Bar Chart</option>
                                        <option value="pie" {{ $selectedChartType === 'pie' ? 'selected' : '' }}>Pie Chart</option>
                                    </select>
                                </div>
                                <!-- Tombol Filter -->
                                <div class="sm:col-span-2 xl:col-span-5 flex items-center justify-end gap-2 mt-2 md:mt-0 flex-wrap">
                                    <button type="button" id="exportVisualizationBtn"
                                        style="background-color:#16a34a; color:white;"
                                        class="px-3 sm:px-4 py-2 text-[11px] sm:text-xs font-bold rounded-lg
                                            whitespace-nowrap"
                                        disabled>
                                        Export Hasil Visualisasi
                                    </button>
                                    <a href="{{ route('admin.monitoring.index') }}"
                                        class="text-center px-3 sm:px-4 py-2 text-[11px] sm:text-xs font-bold rounded-lg border border-gray-300 text-slate-600 hover:bg-slate-100 dark:text-white dark:border-slate-600 dark:hover:bg-slate-700 whitespace-nowrap">
                                        Reset
                                    </a>
                                    <button type="submit"
                                        class="px-3 sm:px-4 py-2 text-[11px] sm:text-xs font-bold rounded-lg bg-blue-600 text-white hover:bg-blue-700 whitespace-nowrap">
                                        Terapkan Filter
                                    </button>
                                </div>
                                <div id="monitoringFilterValidation" class="sm:col-span-2 xl:col-span-5 text-xs text-red-500"></div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="px-4 pt-4">
                    <div class="rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 p-4">
                        <div class="flex items-center justify-between mb-3">
                            <h6 id="visualizationTitle" class="text-sm font-bold text-slate-700 dark:text-white">Visualisasi Response Rate</h6>
                            <span id="visualizationLegendTitle" class="text-xs text-slate-500 dark:text-slate-300">Legend</span>
                        </div>
                        <div id="visualizationEmptyState" class="text-xs text-slate-500 dark:text-slate-300 py-6 text-center leading-relaxed">
                            Pilih 1 survei untuk menampilkan visualisasi response rate. Dimensi sumbu akan menyesuaikan otomatis berdasarkan kombinasi filter Tahun Lulus dan Program Studi.
                        </div>
                        <div id="visualizationCanvasContainer" class="hidden visualization-canvas-wrap">
                            <canvas id="responseRateChart" height="110"></canvas>
                        </div>
                    </div>
                </div>

                <div class="flex-auto px-0 pt-0 pb-2">
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
                                        Response Rate<br>(Keseluruhan)</th>
                                    <th class="px-3 py-2 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                        Response Rate<br>(Sesuai Filter)</th>
                                    <th class="px-3 py-2 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                                        Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($survey as $srvy)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-slate-700">
                                        <!-- Kolom 1: Sticky Kiri -->
                                        <td class="sticky left-0 z-10 bg-white dark:bg-slate-850 p-1.5 align-middle border-b dark:border-white/40 shadow-transparent">
                                            <div class="flex flex-col px-2 py-1">
                                                <h6 class="mb-0 text-xs leading-normal dark:text-white font-semibold whitespace-nowrap">
                                                    {{ $srvy->nama }}
                                                </h6>
                                            </div>
                                        </td>

                                        <td class="p-1.5 text-xs leading-normal text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                            <span class="bg-gradient-to-tl {{ $srvy->status == 'Aktif' ? 'from-emerald-500 to-teal-400' : 'from-slate-600 to-slate-300' }} px-2.5 text-xs rounded-1.8 py-1.4 inline-block whitespace-nowrap text-center align-baseline font-bold uppercase leading-none text-white">
                                                {{ $srvy->status }}
                                            </span>
                                        </td>
                                        <td class="p-1.5 text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                            <span class="text-xs font-semibold leading-tight dark:text-white dark:opacity-80 text-slate-400">
                                                {{ $srvy->tanggal_mulai . ' - ' . $srvy->tanggal_selesai }}
                                            </span>
                                        </td>
                                        <td class="p-1.5 text-center align-middle bg-transparent border-b dark:border-white/40 shadow-transparent whitespace-nowrap">
                                            <span class="text-xs font-semibold leading-tight dark:text-white dark:opacity-80 text-slate-400 capitalize">
                                                {{ $srvy->type_survei == 'lulusan' ? 'Lulusan' : 'Pengguna Lulusan' }}
                                            </span>
                                        </td>

                                        <td class="p-1.5 text-center align-middle bg-transparent border-b dark:border-white/40 shadow-transparent whitespace-nowrap">
                                            <div class="text-xs font-bold dark:text-white text-slate-600">
                                                {{ number_format($srvy->overall_rate, 2) }}%
                                            </div>
                                            <div class="text-xs text-slate-400">
                                                {{ $srvy->overall_completed }} / {{ $srvy->overall_total }}
                                            </div>
                                        </td>
                                        <td class="p-1.5 text-center align-middle bg-transparent border-b dark:border-white/40 shadow-transparent whitespace-nowrap">
                                            <div class="text-xs font-bold dark:text-white text-slate-600">
                                                {{ number_format($srvy->filtered_rate, 2) }}%
                                            </div>
                                            <div class="text-xs text-slate-400">
                                                {{ $srvy->filtered_completed }} / {{ $srvy->filtered_total }}
                                            </div>
                                        </td>

                                        <td class="p-1.5 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                            <div class="flex items-center justify-center">
                                                <a href="{{ route('admin.monitoring.export', $srvy->id) }}" class="icon-link p-2 hover:bg-gray-100 dark:hover:bg-slate-600 rounded-full" data-tooltip="Export Hasil Survei">
                                                    <i class="fas fa-file-excel text-emerald-500"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach

                                @if ($survey->isEmpty())
                                    <tr>
                                        <td colspan="7" class="p-4 text-center text-xs text-slate-400 border-b dark:border-white/40">
                                            Data survei tidak ditemukan untuk filter yang dipilih.
                                        </td>
                                    </tr>
                                @endif
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

    <!-- Scripts -->
    @php
        $monitoringChartPayload = [
            'selectedChartType' => $selectedChartType,
            'selectedSurveyName' => $selectedSurveyForVisualization ? $selectedSurveyForVisualization->nama : null,
            'activeFilterType' => $activeFilterType,
            'activeFilterValue' => $activeFilterValue,
            'selectedSurveyId' => $selectedSurveyId,
            'selectedGraduationYear' => $selectedGraduationYear,
            'selectedStudyProgram' => $selectedStudyProgram,
            'filterOptionsBySurvey' => $filterOptionsBySurvey,
            'chartLabels' => $dynamicChart['labels'] ?? [],
            'chartRates' => $dynamicChart['rates'] ?? [],
            'chartTargets' => $dynamicChart['targets'] ?? [],
            'chartResponded' => $dynamicChart['responded'] ?? [],
            'overallRate' => $dynamicChart['overallRate'] ?? 0,
            'overallTarget' => $dynamicChart['overallTarget'] ?? 0,
            'overallResponded' => $dynamicChart['overallResponded'] ?? 0,
            'xAxisTitle' => $dynamicChart['xAxisTitle'] ?? 'Program Studi',
            'yAxisTitle' => $dynamicChart['yAxisTitle'] ?? 'Response Rate (%)',
            'chartScenario' => $dynamicChart['scenario'] ?? null,
            'showChart' => (bool) ($dynamicChart['showChart'] ?? false),
        ];
    @endphp

    <style>
        .visualization-canvas-wrap {
            position: relative;
            min-height: 280px;
        }

        @media (max-width: 767px) {
            .visualization-canvas-wrap {
                min-height: 340px;
            }
        }
    </style>

    <script id="monitoringChartPayload" type="application/json">{!! json_encode($monitoringChartPayload) !!}</script>

    <script>
        (function() {
            const payloadElement = document.getElementById('monitoringChartPayload');
            const payload = payloadElement ? JSON.parse(payloadElement.textContent || '{}') : {};
            const selectedChartType = payload.selectedChartType || '';
            const selectedSurveyName = payload.selectedSurveyName || '';
            const activeFilterType = payload.activeFilterType || '';
            const activeFilterValue = payload.activeFilterValue || '';
            const selectedSurveyId = payload.selectedSurveyId ? String(payload.selectedSurveyId) : '';
            const selectedGraduationYear = payload.selectedGraduationYear || '';
            const selectedStudyProgram = payload.selectedStudyProgram || '';
            const filterOptionsBySurvey = payload.filterOptionsBySurvey || {};
            const chartLabels = Array.isArray(payload.chartLabels) ? payload.chartLabels : [];
            const chartRates = Array.isArray(payload.chartRates) ? payload.chartRates : [];
            const chartTargets = Array.isArray(payload.chartTargets) ? payload.chartTargets : [];
            const chartResponded = Array.isArray(payload.chartResponded) ? payload.chartResponded : [];
            const overallRate = Number(payload.overallRate ?? 0);
            const overallTarget = Number(payload.overallTarget ?? 0);
            const overallResponded = Number(payload.overallResponded ?? 0);
            const xAxisTitle = payload.xAxisTitle || 'Program Studi';
            const yAxisTitle = payload.yAxisTitle || 'Response Rate (%)';
            const chartScenario = payload.chartScenario || 'all';
            const showChart = Boolean(payload.showChart);

            const filterForm = document.getElementById('monitoringFilterForm');
            const surveySelect = document.getElementById('surveySelect');
            const graduationYearSelect = document.getElementById('graduationYearSelect');
            const studyProgramSelect = document.getElementById('studyProgramSelect');
            const filterValidation = document.getElementById('monitoringFilterValidation');

            const exportButton = document.getElementById('exportVisualizationBtn');
            const emptyState = document.getElementById('visualizationEmptyState');
            const canvasContainer = document.getElementById('visualizationCanvasContainer');
            const title = document.getElementById('visualizationTitle');
            const legendTitle = document.getElementById('visualizationLegendTitle');
            const canvas = document.getElementById('responseRateChart');

            let chartInstance = null;

            const palette = [
                '#1D4ED8', '#059669', '#DC2626', '#D97706', '#7C3AED',
                '#0F766E', '#BE185D', '#4338CA', '#65A30D', '#EA580C'
            ];

            function populateSelectOptions(selectElement, values, defaultText, selectedValue) {
                selectElement.innerHTML = '';

                const defaultOption = document.createElement('option');
                defaultOption.value = '';
                defaultOption.textContent = defaultText;
                selectElement.appendChild(defaultOption);

                values.forEach(function(value) {
                    const option = document.createElement('option');
                    option.value = value;
                    option.textContent = value;
                    if (String(value) === String(selectedValue)) {
                        option.selected = true;
                    }
                    selectElement.appendChild(option);
                });
            }

            function updateFilterOptionsBySurvey(keepCurrentSelection) {
                const surveyId = surveySelect.value ? String(surveySelect.value) : '';
                const optionSet = filterOptionsBySurvey[surveyId] || {
                    years: [],
                    programs: [],
                    programsByYear: {},
                    yearsByProgram: {}
                };

                const currentYear = keepCurrentSelection ? selectedGraduationYear : graduationYearSelect.value;
                const currentProgram = keepCurrentSelection ? selectedStudyProgram : studyProgramSelect.value;

                const availableYears = Array.isArray(optionSet.years) ? optionSet.years : [];
                const availablePrograms = Array.isArray(optionSet.programs) ? optionSet.programs : [];

                let filteredYears = availableYears;
                if (currentProgram && optionSet.yearsByProgram && Array.isArray(optionSet.yearsByProgram[currentProgram])) {
                    filteredYears = optionSet.yearsByProgram[currentProgram];
                }

                let filteredPrograms = availablePrograms;
                if (currentYear && optionSet.programsByYear && Array.isArray(optionSet.programsByYear[currentYear])) {
                    filteredPrograms = optionSet.programsByYear[currentYear];
                }

                populateSelectOptions(
                    graduationYearSelect,
                    filteredYears,
                    'Semua Tahun',
                    filteredYears.includes(currentYear) ? currentYear : ''
                );

                populateSelectOptions(
                    studyProgramSelect,
                    filteredPrograms,
                    'Semua Prodi',
                    filteredPrograms.includes(currentProgram) ? currentProgram : ''
                );
            }

            graduationYearSelect.addEventListener('change', function() {
                updateFilterOptionsBySurvey(false);
            });

            studyProgramSelect.addEventListener('change', function() {
                updateFilterOptionsBySurvey(false);
            });

            surveySelect.addEventListener('change', function() {
                updateFilterOptionsBySurvey(false);
                filterValidation.textContent = '';
            });

            if (selectedSurveyId) {
                surveySelect.value = selectedSurveyId;
            }

            updateFilterOptionsBySurvey(true);

            filterForm.addEventListener('submit', function(event) {
                const hasSurvey = !!surveySelect.value;
                const hasYear = !!graduationYearSelect.value;
                const hasProgram = !!studyProgramSelect.value;
                const anyFilterApplied = hasYear || hasProgram;

                if (anyFilterApplied && !hasSurvey) {
                    event.preventDefault();
                    filterValidation.textContent = 'Pilih survei terlebih dahulu jika ingin menampilkan visualisasi berdasarkan filter.';
                    return;
                }

                filterValidation.textContent = '';
            });

            function ensureChartLibrary(callback) {
                if (typeof Chart !== 'undefined') {
                    callback();
                    return;
                }

                let retries = 0;
                const timer = setInterval(() => {
                    if (typeof Chart !== 'undefined') {
                        clearInterval(timer);
                        callback();
                        return;
                    }

                    retries += 1;
                    if (retries >= 40) {
                        clearInterval(timer);
                        emptyState.textContent = 'Library chart tidak berhasil dimuat. Silakan refresh halaman.';
                    }
                }, 100);
            }

            function renderChart() {
                if (!selectedSurveyId) {
                    emptyState.textContent = 'Pilih 1 survei untuk menampilkan visualisasi response rate.';
                    emptyState.classList.remove('hidden');
                    canvasContainer.classList.add('hidden');
                    exportButton.disabled = true;
                    return;
                }

                if (!showChart || !selectedChartType || !['bar', 'pie'].includes(selectedChartType) || chartLabels.length === 0 || chartRates.length === 0) {
                    emptyState.textContent = 'Data response rate tidak tersedia untuk kombinasi filter yang dipilih.';
                    emptyState.classList.remove('hidden');
                    canvasContainer.classList.add('hidden');
                    exportButton.disabled = true;
                    return;
                }

                emptyState.classList.add('hidden');
                canvasContainer.classList.remove('hidden');

                const isMobile = window.matchMedia('(max-width: 767px)').matches;
                const isDesktop = window.matchMedia('(min-width: 1024px)').matches;

                let scenarioLabel = 'Semua Tahun dan Semua Prodi';
                if (chartScenario === 'year_only') {
                    scenarioLabel = `Tahun Lulus ${selectedGraduationYear || '-'}`;
                } else if (chartScenario === 'program_only') {
                    scenarioLabel = `Program Studi ${selectedStudyProgram || '-'}`;
                } else if (chartScenario === 'year_and_program') {
                    scenarioLabel = `Tahun ${selectedGraduationYear || '-'} - ${selectedStudyProgram || '-'}`;
                }

                title.textContent = selectedChartType === 'bar'
                    ? `Visualisasi Response Rate (${scenarioLabel})`
                    : `Visualisasi Distribusi Response Rate (${scenarioLabel})`;

                legendTitle.textContent = selectedChartType === 'bar'
                    ? ''
                    : `X-axis: ${xAxisTitle} | Y-axis: ${yAxisTitle}`;

                const renderedLabels = chartLabels.slice();
                const renderedRates = chartRates.slice();
                const renderedTargets = chartTargets.slice();
                const renderedResponded = chartResponded.slice();

                if (selectedChartType === 'bar') {
                    const overallLabel = 'Politeknik Statistika STIS';
                    const overallLabelIndex = renderedLabels.findIndex(function(label) {
                        return String(label) === overallLabel;
                    });

                    if (overallLabelIndex >= 0) {
                        renderedRates[overallLabelIndex] = overallRate;
                        renderedTargets[overallLabelIndex] = overallTarget;
                        renderedResponded[overallLabelIndex] = overallResponded;
                    } else {
                        renderedLabels.push(overallLabel);
                        renderedRates.push(overallRate);
                        renderedTargets.push(overallTarget);
                        renderedResponded.push(overallResponded);
                    }
                }

                const datasetConfig = {
                    label: 'Response Rate (%)',
                    data: renderedRates,
                    backgroundColor: renderedLabels.map((_, index) => palette[index % palette.length]),
                    borderColor: selectedChartType === 'bar' ? '#1E3A8A' : '#FFFFFF',
                    borderWidth: selectedChartType === 'bar' ? 1 : 2,
                    radius: selectedChartType === 'pie' ? '78%' : undefined,
                };

                if (chartInstance) {
                    chartInstance.destroy();
                }

                chartInstance = new Chart(canvas, {
                    type: selectedChartType,
                    data: {
                        labels: renderedLabels,
                        datasets: [datasetConfig],
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: selectedChartType !== 'bar',
                                position: 'bottom',
                                labels: {
                                    boxWidth: isMobile ? 10 : 12,
                                    font: {
                                        size: isMobile ? 10 : 12,
                                    },
                                }
                            },
                            title: {
                                display: false,
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        const label = context.label || '';
                                        const value = context.raw ?? 0;
                                        const index = context.dataIndex || 0;
                                        const responded = renderedResponded[index] ?? 0;
                                        const target = renderedTargets[index] ?? 0;
                                        return `${label}: ${Number(value).toFixed(2)}% (${responded}/${target})`;
                                    }
                                }
                            }
                        },
                        scales: selectedChartType === 'bar' ? {
                            y: {
                                beginAtZero: true,
                                max: 100,
                                ticks: {
                                    font: {
                                        size: isMobile ? 10 : 12,
                                    },
                                    callback: function(value) {
                                        return `${value}%`;
                                    }
                                },
                                title: {
                                    display: true,
                                    text: yAxisTitle
                                }
                            },
                            x: {
                                ticks: {
                                    font: {
                                        size: isMobile ? 9 : 12,
                                    },
                                    autoSkip: false,
                                    maxRotation: isMobile ? 0 : 25,
                                    minRotation: 0,
                                },
                                title: {
                                    display: true,
                                    text: xAxisTitle,
                                }
                            }
                        } : undefined,
                    }
                });

                exportButton.disabled = false;
            }

            exportButton.addEventListener('click', function() {
                if (!chartInstance || !selectedChartType) {
                    return;
                }

                const link = document.createElement('a');
                const timestamp = new Date().toISOString().slice(0, 19).replace(/[:T]/g, '-');
                link.href = chartInstance.toBase64Image('image/png', 1);
                const safeScenario = (chartScenario || 'all').replace(/[^a-zA-Z0-9_-]/g, '_');
                link.download = `visualisasi-response-rate-${safeScenario}-${selectedChartType}-${timestamp}.png`;
                link.click();
            });

            ensureChartLibrary(renderChart);
        })();

        // Auto resize textareas
        document.addEventListener('input', function (e) {
            if (e.target.classList.contains('auto-resize')) {
                e.target.style.height = 'auto';
                e.target.style.height = (e.target.scrollHeight) + 'px';
            }
        });
    </script>
@endsection