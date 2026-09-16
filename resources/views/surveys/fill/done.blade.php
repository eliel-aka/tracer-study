@extends('surveys.fill.layout')

@section('title', 'Survei Selesai - ' . $survey->nama)

@section('content')
<div class="max-w-2xl mx-auto mt-8">
    <!-- Success Card -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-8 md:p-10 text-center relative overflow-hidden">
        <!-- Background Decoration -->
        <div class="absolute top-0 left-1/2 -translate-x-1/2 -mt-10 w-40 h-40 bg-green-50 rounded-full opacity-50 blur-2xl pointer-events-none"></div>

        <div class="mx-auto flex items-center justify-center h-20 w-20 rounded-full bg-gradient-to-br from-green-100 to-emerald-50 mb-6 shadow-sm border border-green-100 relative z-10">
            <i class="fas fa-check text-green-500 text-3xl drop-shadow-sm"></i>
        </div>
        
        <h2 class="text-3xl font-black text-slate-800 mb-3 relative z-10 tracking-tight">
            Terima Kasih!
        </h2>
        
        <p class="text-slate-500 mb-8 text-lg leading-relaxed relative z-10">
            Anda telah berhasil menyelesaikan survei "<strong>{{ $survey->nama }}</strong>".<br>
            Jawaban Anda telah tersimpan dengan aman.
        </p>

        @if($surveyUser && $surveyUser->tanggal_mengisi)
            <div class="bg-blue-50/50 border border-blue-100 rounded-xl p-4 mb-8 inline-block shadow-sm">
                <div class="flex items-center justify-center">
                    <i class="fas fa-clock text-blue-500 mr-2.5"></i>
                    <span class="text-sm font-medium text-blue-800">
                        Diselesaikan pada: 
                        <strong class="ml-1 text-blue-900">{{ \Carbon\Carbon::parse($surveyUser->tanggal_mengisi)->format('d M Y H:i') }}</strong>
                    </span>
                </div>
            </div>
        @endif

        <!-- Survey Info -->
        <div class="bg-slate-50 border border-slate-100 rounded-2xl p-6 mb-8 relative z-10">
            <h3 class="text-lg font-bold text-slate-800 mb-4 border-b border-slate-200 pb-2">Informasi Survei</h3>
            <div class="text-left space-y-3 text-sm text-slate-600">
                <div class="flex justify-between">
                    <span>Nama Survei:</span>
                    <span class="font-medium">{{ $survey->nama }}</span>
                </div>
                @if($survey->deskripsi)
                    <div class="flex justify-between">
                        <span>Deskripsi:</span>
                        <span class="font-medium text-right max-w-xs">{{ $survey->deskripsi }}</span>
                    </div>
                @endif
                <div class="flex justify-between">
                    <span>Periode:</span>
                    <span class="font-medium">
                        {{ \Carbon\Carbon::parse($survey->tanggal_mulai)->format('d M Y') }} - 
                        {{ \Carbon\Carbon::parse($survey->tanggal_selesai)->format('d M Y') }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row flex-wrap justify-center gap-4 relative z-10">
            @if(!empty($canEdit))
                <a href="{{ route('surveys.start', $survey) }}" 
                   class="inline-flex justify-center items-center px-6 py-3 border border-transparent text-base font-bold rounded-xl text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-4 focus:ring-indigo-500/30 transition-all shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                    <i class="fas fa-pen-to-square mr-2.5"></i>
                    Edit Jawaban
                </a>
            @endif

            @auth
                <a href="{{ route('user.monitoring.index') }}" 
                   class="inline-flex justify-center items-center px-6 py-3 border border-transparent text-base font-bold rounded-xl text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-500/30 transition-all shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                    <i class="fas fa-chart-bar mr-2.5"></i>
                    Lihat Survei Saya
                </a>
            @endauth
            
            <button onclick="window.close()" 
                    class="inline-flex justify-center items-center px-6 py-3 border-2 border-slate-200 text-base font-bold rounded-xl text-slate-600 bg-white hover:bg-slate-50 hover:text-slate-800 hover:border-slate-300 focus:outline-none focus:ring-4 focus:ring-slate-100 transition-all">
                <i class="fas fa-times mr-2.5"></i>
                Tutup Halaman
            </button>
        </div>
    </div>

    <!-- Additional Info -->
    <div class="mt-8 bg-blue-50 border border-blue-200 rounded-lg p-4">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-info-circle text-blue-400"></i>
            </div>
            <div class="ml-3">
                <h4 class="text-sm font-medium text-blue-800 mb-1">Informasi Penting</h4>
                <div class="text-sm text-blue-700 space-y-1">
                    <p>• Data yang Anda berikan akan dijaga kerahasiaannya</p>
                    <p>• Hasil survei akan digunakan untuk keperluan akademis dan pengembangan program</p>
                    <p>• Jika ada pertanyaan, silakan hubungi admin sistem</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
