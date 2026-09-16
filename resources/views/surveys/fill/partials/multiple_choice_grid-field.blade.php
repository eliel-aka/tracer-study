@php
    $configuredColumns = collect($question->grid_columns ?? [])
        ->map(fn ($column) => is_string($column) ? trim($column) : '')
        ->filter()
        ->values();

    if ($configuredColumns->count() < 2) {
        $configuredColumns = collect([
            'Sangat Tidak Setuju',
            'Tidak Setuju',
            'Netral',
            'Setuju',
            'Sangat Setuju',
        ]);
    }

    $scaleColumns = $configuredColumns->values();

    $existingGrid = [];
    if (!empty($existingAnswer?->jawaban)) {
        $decoded = json_decode($existingAnswer->jawaban, true);
        if (is_array($decoded)) {
            $existingGrid = $decoded;
        }
    }
@endphp

<style>
    .grid-desktop-view {
        display: block !important;
        width: 100%;
    }
    .grid-mobile-view {
        display: none !important;
    }
    @media (max-width: 767px) {
        .grid-desktop-view {
            display: none !important;
        }
        .grid-mobile-view {
            display: flex !important;
            flex-direction: column;
            gap: 1rem;
            width: 100%;
        }
    }
    .grid-mobile-card {
        background-color: #ffffff;
        border: 1px solid #cbd5e1;
        border-radius: 0.75rem;
        padding: 1rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        box-sizing: border-box;
        width: 100%;
    }
    .dark .grid-mobile-card {
        background-color: #111a2e;
        border-color: #334155;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.3);
    }
    .grid-mobile-statement {
        display: flex;
        align-items: flex-start;
        gap: 0.5rem;
        margin-bottom: 0.75rem;
        padding-bottom: 0.75rem;
        border-bottom: 1px solid #e2e8f0;
    }
    .dark .grid-mobile-statement {
        border-bottom-color: #1e293b;
    }
    .grid-mobile-stmt-number {
        font-size: 0.9375rem;
        font-weight: 700;
        color: #2563eb;
        line-height: 1.45;
        flex-shrink: 0;
    }
    .grid-mobile-stmt-text {
        font-size: 0.9375rem;
        font-weight: 600;
        line-height: 1.45;
        color: #0f172a;
        word-break: break-word;
    }
    .dark .grid-mobile-stmt-text {
        color: #f8fafc;
    }
    .grid-mobile-options {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }
    .grid-mobile-option-label {
        display: flex;
        align-items: center;
        padding: 0.625rem 0.75rem;
        border-radius: 0.5rem;
        border: 1px solid #e2e8f0;
        background-color: #f8fafc;
        cursor: pointer;
        transition: all 0.15s ease;
        user-select: none;
    }
    .dark .grid-mobile-option-label {
        border-color: #334155;
        background-color: #18243a;
    }
    .grid-mobile-option-label:hover {
        border-color: #93c5fd;
        background-color: rgba(59, 130, 246, 0.08);
    }
    .grid-mobile-option-label.is-selected {
        background-color: #eff6ff !important;
        border-color: #3b82f6 !important;
    }
    .dark .grid-mobile-option-label.is-selected {
        background-color: rgba(37, 99, 235, 0.2) !important;
        border-color: #60a5fa !important;
    }
    .grid-mobile-bullet {
        width: 1.125rem !important;
        height: 1.125rem !important;
        margin: 0 !important;
        margin-right: 0.75rem !important;
        flex-shrink: 0;
        cursor: pointer;
    }
    .grid-mobile-option-body {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        flex: 1;
        min-width: 0;
    }
    .grid-mobile-score {
        width: 22px;
        height: 22px;
        min-width: 22px;
        border-radius: 50%;
        background-color: #e2e8f0;
        color: #334155;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 0.75rem;
        font-weight: 700;
        flex-shrink: 0;
        transition: all 0.15s ease;
    }
    .dark .grid-mobile-score {
        background-color: #1e293b;
        color: #cbd5e1;
    }
    .grid-mobile-option-label.is-selected .grid-mobile-score {
        background-color: #2563eb;
        color: #ffffff;
    }
    .dark .grid-mobile-option-label.is-selected .grid-mobile-score {
        background-color: #3b82f6;
        color: #ffffff;
    }
    .grid-mobile-text {
        font-size: 0.875rem;
        font-weight: 500;
        line-height: 1.4;
        color: #334155;
        cursor: pointer;
        word-break: break-word;
    }
    .dark .grid-mobile-text {
        color: #cbd5e1;
    }
</style>

<!-- Desktop Table View (>= 768px) -->
<div class="grid-desktop-view">
    <div class="w-full max-w-full min-w-0 rounded-xl border border-slate-300 dark:border-slate-700 shadow-sm overflow-hidden bg-white dark:bg-dark-2">
        <div class="w-full max-w-full min-w-0 overflow-x-auto block">
            <table class="min-w-full border-collapse border-spacing-0 text-left">
                <thead class="bg-slate-100 dark:bg-slate-800">
                    <tr>
                        <th class="text-left text-xs sm:text-sm font-semibold text-slate-800 dark:text-slate-100 px-4 py-3.5 border-b-2 border-r-2 border-slate-300 dark:border-slate-700 min-w-[190px] sm:min-w-[260px]">
                            Pernyataan
                        </th>
                        @foreach($scaleColumns as $index => $label)
                            @php($score = $index + 1)
                            <th class="text-center text-xs font-semibold text-slate-800 dark:text-slate-100 px-3 py-3 border-b-2 border-r border-slate-300 dark:border-slate-700 min-w-[90px] sm:min-w-[110px] w-[95px] sm:w-[125px] last:border-r-0">
                                <div class="flex flex-col items-center justify-center gap-1.5">
                                    <div class="inline-flex shrink-0 items-center justify-center w-7 h-7 rounded-full bg-blue-100 text-blue-700 dark:bg-blue-900/60 dark:text-blue-300 font-bold text-xs sm:text-sm shadow-sm border border-blue-200 dark:border-blue-800">
                                        {{ $score }}
                                    </div>
                                    <div class="text-[11px] sm:text-xs font-semibold text-slate-600 dark:text-slate-300 leading-snug">{{ $label }}</div>
                                </div>
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach($options as $rowIdx => $option)
                        @php($isEven = $rowIdx % 2 === 0)
                        <tr class="transition-colors duration-150 {{ $isEven ? 'bg-white dark:bg-dark-2' : 'bg-slate-50/80 dark:bg-slate-800/40' }} hover:bg-blue-50/60 dark:hover:bg-blue-900/25">
                            <td class="text-xs sm:text-sm font-medium text-slate-900 dark:text-slate-100 px-4 py-3 sm:py-3.5 border-b border-r-2 border-slate-300 dark:border-slate-700 align-middle">
                                {{ $option->pilihan_jawaban }}
                            </td>

                            @foreach($scaleColumns as $index => $label)
                                @php($score = $index + 1)
                                @php($isSelected = (string)($existingGrid[$option->id] ?? '') === (string)$score)
                                <td class="text-center px-2 py-2.5 border-b border-r border-slate-300 dark:border-slate-700 last:border-r-0 align-middle {{ $isSelected ? 'bg-blue-100/60 dark:bg-blue-900/40' : '' }}">
                                    <label class="flex items-center justify-center w-full h-full min-h-[38px] cursor-pointer m-0">
                                        <input type="radio"
                                               name="answer_grid[{{ $option->id }}]"
                                               value="{{ $score }}"
                                               @if($isSelected) checked @endif
                                               class="w-4.5 h-4.5 text-blue-600 focus:ring-blue-500 border-slate-300 cursor-pointer transition-transform hover:scale-110">
                                    </label>
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Mobile Stacked Statement Cards View (< 768px) -->
<div class="grid-mobile-view">
    @foreach($options as $rowIdx => $option)
        @php($rowVal = (string)($existingGrid[$option->id] ?? ''))
        <div class="grid-mobile-card">
            <div class="grid-mobile-statement">
                <span class="grid-mobile-stmt-number">{{ $rowIdx + 1 }}.</span>
                <span class="grid-mobile-stmt-text">{{ $option->pilihan_jawaban }}</span>
            </div>
            <div class="grid-mobile-options">
                @foreach($scaleColumns as $index => $label)
                    @php($score = $index + 1)
                    @php($isSelected = $rowVal === (string)$score)
                    <label class="grid-mobile-option-label {{ $isSelected ? 'is-selected' : '' }}">
                        <input type="radio"
                               name="answer_grid[{{ $option->id }}]"
                               value="{{ $score }}"
                               @if($isSelected) checked @endif
                               class="grid-mobile-bullet text-blue-600 focus:ring-blue-500 border-slate-300"
                               onchange="this.closest('.grid-mobile-options').querySelectorAll('.grid-mobile-option-label').forEach(el => el.classList.remove('is-selected')); this.closest('.grid-mobile-option-label').classList.add('is-selected');">
                        <div class="grid-mobile-option-body">
                            <span class="grid-mobile-score">{{ $score }}</span>
                            <span class="grid-mobile-text">{{ $label }}</span>
                        </div>
                    </label>
                @endforeach
            </div>
        </div>
    @endforeach
</div>

@error('answer_grid')
    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
@enderror
@error('answer_grid.*')
    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
@enderror
