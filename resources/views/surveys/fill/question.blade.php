@extends('surveys.fill.layout')

@section('title', $survey->nama)

@section('content')
<div x-data="surveyQuestion()" class="space-y-6">
    <!-- Survey Info -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 md:p-8 relative overflow-hidden">
        <!-- Decorative background element -->
        <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-blue-50 rounded-full opacity-50 blur-xl pointer-events-none"></div>
        
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 relative z-10">
            <div class="flex-1">
                <h2 class="text-2xl font-bold text-slate-800 tracking-tight">{{ $survey->nama }}</h2>
                @if($survey->deskripsi)
                    <p class="text-slate-500 mt-2 text-sm leading-relaxed">{{ $survey->deskripsi }}</p>
                @endif
            </div>
            <div class="flex-shrink-0 bg-slate-50 rounded-xl p-4 border border-slate-100 min-w-[140px] text-center">
                <div class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-1">Pertanyaan</div>
                <div class="text-2xl font-black text-blue-600">
                    {{ $progress['current'] }} <span class="text-slate-300 text-lg mx-1">/</span> <span class="text-slate-600 text-lg">{{ $progress['total'] }}</span>
                </div>
            </div>
        </div>

        <!-- Progress Bar -->
        <div class="mt-8 relative z-10">
            <div class="flex justify-between items-end mb-2">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Progress</span>
                <span class="text-sm font-bold text-blue-600">{{ $progress['percent'] }}%</span>
            </div>
            <div class="w-full bg-slate-100 rounded-full h-2.5 overflow-hidden shadow-inner">
                <div class="bg-gradient-to-r from-blue-500 to-indigo-500 h-full rounded-full transition-all duration-500 ease-out relative" 
                     style="width: {{ $progress['percent'] }}%">
                    <!-- Shimmer effect -->
                    <div class="absolute top-0 right-0 bottom-0 left-0 bg-gradient-to-r from-transparent via-white/20 to-transparent -translate-x-full animate-[shimmer_2s_infinite]"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Question Card -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 min-w-0 w-full max-w-full overflow-hidden">
        <form action="{{ route('surveys.submit-answer', [$survey, $question]) }}" method="POST" class="p-6 md:p-8">
            @csrf
            
            <!-- Block Info -->
            @if($question->block)
                <div class="mb-6 flex flex-wrap items-center gap-2">
                    <span class="inline-flex items-center px-3 py-1 rounded-md text-xs font-bold uppercase tracking-wider bg-indigo-50 text-indigo-600 border border-indigo-100 shadow-sm">
                        <i class="fas fa-layer-group mr-1.5 opacity-70"></i> {{ $question->block->kode }} - {{ $question->block->nama }}
                    </span>
                    @if(!empty($isIdentity))
                        <span class="inline-flex items-center px-3 py-1 rounded-md text-xs font-bold uppercase tracking-wider bg-amber-50 text-amber-600 border border-amber-100 shadow-sm">
                            <i class="fas fa-lock mr-1.5 opacity-70"></i> Data Identitas (Terkunci)
                        </span>
                    @endif
                </div>
            @endif

            <!-- Question Title -->
            <div class="mb-8">
                <h3 class="text-xl md:text-2xl font-bold text-slate-800 mb-3 leading-snug">
                    @if(isset($question->block->metadata['is_kompetensi']) && $question->block->metadata['is_kompetensi'] == '1' && !empty($question->block->metadata['pertanyaan_utama']))
                        @php
                            $pertanyaanUtama = $question->block->metadata['pertanyaan_utama'];
                            $indikator = $question->pertanyaan;
                            if (stripos($pertanyaanUtama, '[indikator]') !== false) {
                                $tampilanPertanyaan = str_ireplace('[indikator]', '<span class="text-blue-600 bg-blue-50 px-2 py-0.5 rounded">'.$indikator.'</span>', $pertanyaanUtama);
                            } else {
                                $tampilanPertanyaan = $pertanyaanUtama . ' <span class="text-blue-600 bg-blue-50 px-2 py-0.5 rounded">(' . $indikator . ')</span>';
                            }
                        @endphp
                        {!! $tampilanPertanyaan !!}
                    @else
                        {{ $question->pertanyaan }}
                    @endif
                    
                    @if($question->is_required)
                        <span class="required-asterisk text-red-500 font-black ml-1 text-2xl leading-none" style="color: #ef4444 !important;">*</span>
                    @endif
                </h3>
                @if($question->deskripsi_pertanyaan)
                    <div class="flex items-start gap-2 text-slate-500 bg-slate-50 p-4 rounded-xl border border-slate-100">
                        <i class="fas fa-info-circle mt-0.5 text-blue-400"></i>
                        <p class="text-sm leading-relaxed m-0">{{ $question->deskripsi_pertanyaan }}</p>
                    </div>
                @endif
            </div>

            <!-- Answer Field Based on Question Type -->
            <div class="mb-8">
                @switch($question->tipe)
                    @case('radio')
                        @include('surveys.fill.partials.radio-field', [
                            'options' => $answerOptions,
                            'existingAnswer' => $existingAnswer
                        ])
                        @break

                    @case('multiple_choice_grid')
                        @include('surveys.fill.partials.multiple_choice_grid-field', [
                            'options' => $answerOptions,
                            'existingAnswer' => $existingAnswer
                        ])
                        @break

                    @case('checkbox')
                        @include('surveys.fill.partials.checkbox-field', [
                            'options' => $answerOptions,
                            'existingAnswer' => $existingAnswer
                        ])
                        @break

                    @case('select')
                        @include('surveys.fill.partials.select-field', [
                            'options' => $answerOptions,
                            'existingAnswer' => $existingAnswer
                        ])
                        @break

                    @case('text')
                        @include('surveys.fill.partials.text-field', [
                            'existingAnswer' => $existingAnswer
                        ])
                        @break

                    @case('textarea')
                        @include('surveys.fill.partials.textarea-field', [
                            'existingAnswer' => $existingAnswer
                        ])
                        @break

                    @case('number')
                        @include('surveys.fill.partials.number-field', [
                            'existingAnswer' => $existingAnswer
                        ])
                        @break

                    @case('date')
                        @include('surveys.fill.partials.date-field', [
                            'existingAnswer' => $existingAnswer
                        ])
                        @break

                    @case('gaji')
                        @include('surveys.fill.partials.gaji-field', [
                            'existingAnswer' => $existingAnswer
                        ])
                        @break

                    @default
                        @include('surveys.fill.partials.text-field', [
                            'existingAnswer' => $existingAnswer
                        ])
                @endswitch
            </div>

            <!-- Actions -->
            <div class="mt-10 pt-6 border-t border-slate-100 flex justify-end">
                <button type="submit" 
                        class="inline-flex items-center px-8 py-3.5 border border-transparent text-base font-bold rounded-xl text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-500/30 transition-all shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                    <span>Lanjutkan</span>
                    <i class="fas fa-arrow-right ml-2.5"></i>
                </button>
            </div>
        </form>
    </div>

    <!-- Help Text -->
    <div class="bg-blue-50/70 border border-blue-100 rounded-2xl p-5 shadow-sm">
        <div class="flex items-start gap-4">
            <div class="flex-shrink-0 w-10 h-10 flex items-center justify-center rounded-full bg-blue-100 text-blue-600">
                <i class="fas fa-lightbulb text-lg"></i>
            </div>
            <div class="pt-2">
                <p class="text-sm text-slate-700 leading-relaxed m-0">
                    <span class="font-bold text-blue-800">Tips:</span> Jawab dengan jujur dan lengkap. Anda dapat mengubah jawaban sebelum menyelesaikan survei.
                </p>
            </div>
        </div>
    </div>
</div>

<script>
function surveyQuestion() {
    return {
        init() {
            // Auto-focus on first input
            const firstInput = document.querySelector('input[type="radio"]:first-of-type, input[type="checkbox"]:first-of-type, input[type="text"], textarea, select');
            if (firstInput) {
                firstInput.focus();
            }
        }
    }
}
</script>
@endsection

