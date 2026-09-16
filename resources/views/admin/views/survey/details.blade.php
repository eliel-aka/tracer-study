@extends('admin.layouts.app')
@section('title', 'Details Survey')

@section('content')

<!-- table 1 info survei -->
<div class="flex flex-wrap -mx-3">
            <div class="flex-none w-full max-w-full px-3 min-w-0">
              <div class="relative flex flex-col min-w-0 w-full max-w-full mb-6 break-words bg-white border-0 border-transparent border-solid shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border overflow-hidden">
                <div class="p-6 pb-0 mb-0 border-b-0 border-b-solid rounded-t-2xl border-b-transparent flex items-center justify-between">
                  <h6 class="dark:text-white">Informasi Survei</h6>
                  @if(!auth()->user()->hasRole('supervisor'))
                    <!-- <button type="button"
                        id="sendEmailBtn"
                        data-survey-id="{{ $survey->id }}"
                        class="inline-block px-8 py-2 font-bold leading-normal text-center text-white align-middle transition-all ease-in bg-blue-500 border-0 rounded-lg shadow-md cursor-pointer text-xs tracking-tight-rem hover:shadow-xs hover:-translate-y-px active:opacity-85">
                        <i class="fas fa-envelope mr-2"></i> Kirim Email ke Semua
                    </button> -->

                  @endif
                </div>
                @if(session('error'))
                  <div class="bg-red-100 border-t-4 border-red-500 rounded-b text-red-900 px-4 py-3 shadow-md alert alert-danger mb-6" role="alert">
                      <div class="flex">
                          <div class="py-1">
                              <svg class="fill-current h-6 w-6 text-red-500 mr-4" ...></svg>
                          </div>
                          <div>
                              <p class="font-bold">{{ session('error') }}</p>
                          </div>
                      </div>
                  </div>
              @endif

                <div class="flex-auto p-6 pt-2">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        
                        <!-- Item 1: Nama Survei -->
                        <div class="flex items-start gap-4">
                            <div class="flex-shrink-0 w-12 h-12 flex items-center justify-center rounded-xl bg-blue-50 text-blue-500 shadow-sm dark:bg-slate-800 dark:text-blue-400">
                                <i class="fas fa-clipboard-list text-xl"></i>
                            </div>
                            <div>
                                <span class="block text-xs font-bold tracking-wide text-slate-400 uppercase dark:text-white/60 mb-1">Nama Survei</span>
                                <h6 class="text-sm font-bold text-slate-700 dark:text-white mb-0 leading-snug">{{ $survey->nama }}</h6>
                            </div>
                        </div>

                        <!-- Item 2: Status -->
                        <div class="flex items-start gap-4">
                            <div class="flex-shrink-0 w-12 h-12 flex items-center justify-center rounded-xl bg-emerald-50 text-emerald-500 shadow-sm dark:bg-slate-800 dark:text-emerald-400">
                                <i class="fas fa-info-circle text-xl"></i>
                            </div>
                            <div>
                                <span class="block text-xs font-bold tracking-wide text-slate-400 uppercase dark:text-white/60 mb-1">Status</span>
                                <div class="bg-gradient-to-tl {{$survey->status =='Aktif' ? 'from-emerald-500 to-teal-400' : 'from-slate-600 to-slate-300'}} px-3 py-1 text-xs rounded-full inline-block font-bold uppercase text-white shadow-sm leading-none">{{ $survey->status }}</div>
                            </div>
                        </div>

                        <!-- Item 3: Tanggal Aktif -->
                        <div class="flex items-start gap-4">
                            <div class="flex-shrink-0 w-12 h-12 flex items-center justify-center rounded-xl bg-orange-50 text-orange-500 shadow-sm dark:bg-slate-800 dark:text-orange-400">
                                <i class="fas fa-calendar-alt text-xl"></i>
                            </div>
                            <div>
                                <span class="block text-xs font-bold tracking-wide text-slate-400 uppercase dark:text-white/60 mb-1">Periode Survei</span>
                                <h6 class="text-sm font-bold text-slate-700 dark:text-white mb-0">{{ $survey->tanggal_mulai }} <span class="text-slate-400 mx-1 font-normal">s/d</span> {{ $survey->tanggal_selesai }}</h6>
                            </div>
                        </div>

                        <!-- Item 4: Tipe Survei -->
                        <div class="flex items-start gap-4">
                            <div class="flex-shrink-0 w-12 h-12 flex items-center justify-center rounded-xl bg-purple-50 text-purple-500 shadow-sm dark:bg-slate-800 dark:text-purple-400">
                                <i class="fas fa-users text-xl"></i>
                            </div>
                            <div>
                                <span class="block text-xs font-bold tracking-wide text-slate-400 uppercase dark:text-white/60 mb-1">Tipe Survei</span>
                                <h6 class="text-sm font-bold text-slate-700 dark:text-white mb-0 capitalize">{{ $survey->type_survei }}</h6>
                            </div>
                        </div>

                    </div>
                </div>
              </div>
            </div>
          </div>



        <!-- Tabs -->
        <div class="mb-4">
            <ul class="flex flex-wrap -mb-px text-sm font-medium text-center" id="myTab" role="tablist">
                <li class="mr-2" role="presentation">
                    <button class="inline-block p-4 border-b-2 rounded-t-lg border-blue-500 text-blue-600 dark:text-blue-500 dark:border-blue-500 font-bold" id="pertanyaan-tab" type="button" role="tab" aria-controls="pertanyaan" aria-selected="true" onclick="switchTab('pertanyaan')">Daftar Pertanyaan</button>
                </li>
                <li class="mr-2" role="presentation">
                    <button class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300 dark:text-gray-400 font-bold" id="user-tab" type="button" role="tab" aria-controls="user" aria-selected="false" onclick="switchTab('user')">Daftar User</button>
                </li>
            </ul>
        </div>

        <div id="myTabContent">
            <!-- table 2 -->
            <div id="pertanyaan" role="tabpanel" aria-labelledby="pertanyaan-tab">
                <div class="flex flex-wrap -mx-3">
          <div class="flex-none w-full max-w-full px-3 min-w-0">
            <div class="relative flex flex-col min-w-0 w-full max-w-full mb-6 break-words bg-white border-0 border-transparent border-solid shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border overflow-hidden">
              <div class="p-6 pb-0 mb-0 border-b-0 border-b-solid rounded-t-2xl border-b-transparent flex items-center justify-between">
              </div>

                            <div class="flex-auto px-0 pt-0 pb-2 min-w-0 w-full max-w-full">
                                <div class="p-0 overflow-x-auto w-full max-w-full min-w-0 block">
                                    @foreach($surveyBlocks as $block)
                                        <div class="mb-8 bg-white border border-gray-100 rounded-2xl shadow-sm overflow-hidden">
                                            <!-- Block Header -->
                                            <div class="px-6 py-4 bg-gradient-to-r from-slate-50 to-white border-b border-gray-100 flex items-center justify-between">
                                                <div class="flex items-center gap-4">
                                                    <div class="w-12 h-12 flex items-center justify-center rounded-xl bg-blue-50 text-blue-600 shadow-sm">
                                                        <i class="fas fa-layer-group text-xl"></i>
                                                    </div>
                                                    <div>
                                                        <h5 class="font-bold text-slate-700 mb-0 text-base">Blok: {{ $block->nama }}</h5>
                                                        @if($block->deskripsi)
                                                            <span class="text-xs text-slate-500 block mt-1"><i class="fas fa-info-circle mr-1 opacity-70"></i>{{ $block->deskripsi }}</span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="text-right">
                                                    <span class="bg-blue-100 text-blue-700 text-xs font-bold px-4 py-1.5 rounded-full shadow-sm">
                                                        <i class="fas fa-list-ol mr-1"></i> {{ $block->questions->count() }} Pertanyaan
                                                    </span>
                                                </div>
                                            </div>
                                            
                                            <!-- Questions Table -->
                                            <div class="overflow-x-auto">
                                                <table class="w-full text-sm text-left">
                                                    <thead>
                                                        <tr class="border-b border-gray-100 bg-gray-50/50">
                                                            <th class="px-6 py-4 font-bold text-xs uppercase text-slate-400 tracking-wider">Pertanyaan</th>
                                                            <th class="px-6 py-4 font-bold text-xs uppercase text-slate-400 tracking-wider w-32 text-center">Tipe</th>
                                                            <th class="px-6 py-4 font-bold text-xs uppercase text-slate-400 tracking-wider">Pilihan Jawaban</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="divide-y divide-gray-100">
                                                        @foreach($block->questions as $question)
                                                            <tr class="hover:bg-slate-50/50 transition-colors duration-200">
                                                                <td class="px-6 py-4 align-top">
                                                                    <div class="font-semibold text-slate-700 text-sm mb-1">{{ $question->pertanyaan }}</div>
                                                                    @if($question->deskripsi_pertanyaan)
                                                                        <div class="text-xs text-slate-500"><i class="fas fa-asterisk mr-1 opacity-70 text-[10px]"></i>{{ $question->deskripsi_pertanyaan }}</div>
                                                                    @endif
                                                                </td>
                                                                <td class="px-6 py-4 align-top text-center">
                                                                    <span class="inline-block px-2.5 py-1 text-xs font-bold text-purple-600 bg-purple-50 rounded-md border border-purple-100 uppercase tracking-wide">
                                                                        {{ $question->tipe }}
                                                                    </span>
                                                                </td>
                                                                <td class="px-6 py-4 align-top">
                                                                    @if(in_array($question->tipe, ['radio','checkbox','select']) && $question->templateJawaban->count())
                                                                        <div class="flex flex-col gap-2">
                                                                            @foreach($question->templateJawaban as $jawaban)
                                                                                <div class="flex items-center gap-2 text-sm text-slate-600">
                                                                                    <div class="w-1.5 h-1.5 rounded-full bg-slate-300 flex-shrink-0"></div>
                                                                                    <span class="font-medium">{{ $jawaban->pilihan_jawaban }}</span>
                                                                                    @if($jawaban->navigation_target)
                                                                                        <span class="inline-flex items-center gap-1 text-xs font-medium text-orange-600 bg-orange-50 px-2 py-0.5 rounded border border-orange-100 ml-1">
                                                                                            <i class="fas fa-arrow-right text-[10px]"></i> {{ $jawaban->navigation_target }}
                                                                                        </span>
                                                                                    @endif
                                                                                </div>
                                                                            @endforeach
                                                                        </div>
                                                                    @else
                                                                        <span class="inline-block px-3 py-1 text-xs italic text-slate-400 bg-slate-50 rounded-md">
                                                                            Input Teks Bebas
                                                                        </span>
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
            </div>
          </div>
        </div>

        <!-- table 3 -->
            </div>
            
            <div id="user" class="hidden" role="tabpanel" aria-labelledby="user-tab">
                <div class="flex flex-wrap -mx-3">
            <div class="flex-none w-full max-w-full px-3 min-w-0">
              <div class="relative flex flex-col min-w-0 w-full max-w-full mb-6 break-words bg-white border-0 border-transparent border-solid shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border overflow-hidden">
              <div class="p-6 pb-0 mb-0 border-b-0 border-b-solid rounded-t-2xl border-b-transparent flex flex-nowrap items-end gap-4 w-full">
                  @if(!auth()->user()->hasRole('supervisor'))
                  <div class="flex flex-nowrap gap-2 shrink-0 mb-1">
                        <button type="button"
                                id="sendInvitationBtn"
                                data-survey-id="{{ $survey->id }}"
                                class="inline-block px-4 py-2 font-bold leading-normal text-center text-white align-middle transition-all ease-in bg-blue-500 border-0 rounded-lg shadow-md cursor-pointer text-xs tracking-tight-rem hover:shadow-xs hover:-translate-y-px active:opacity-85">
                            <i class="fas fa-paper-plane mr-2"></i>
                            <span class="hidden sm:inline">Kirim Undangan</span>
                            <span class="sm:hidden">Undangan</span>
                        </button>

                        <button type="button"
                                id="sendReminderBtn"
                                data-survey-id="{{ $survey->id }}"
                                class="inline-block px-4 py-2 font-bold leading-normal text-center text-white align-middle transition-all ease-in bg-orange-500 border-0 rounded-lg shadow-md cursor-pointer text-xs tracking-tight-rem hover:shadow-xs hover:-translate-y-px active:opacity-85">
                            <i class="fas fa-bell mr-2"></i>
                            <span class="hidden sm:inline">Reminder Pengerjaan</span>
                            <span class="sm:hidden">Reminder</span>
                        </button>

                        <button type="button"
                                id="sendThankYouBtn"
                                data-survey-id="{{ $survey->id }}"
                                class="inline-block px-4 py-2 font-bold leading-normal text-center text-white align-middle transition-all ease-in bg-green-500 border-0 rounded-lg shadow-md cursor-pointer text-xs tracking-tight-rem hover:shadow-xs hover:-translate-y-px active:opacity-85">
                            <i class="fas fa-handshake mr-2"></i>
                            <span class="hidden sm:inline">Ucapan Terima Kasih</span>
                            <span class="sm:hidden">Thanks</span>
                        </button>
                  </div>
                  <div class="flex flex-nowrap items-center gap-4 w-full min-w-0">
                                        <div class="relative flex-1">
                                            <label for="graduationYearSelect" class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Tambah Responden by Tahun Lulus: </label>
                      <select id="graduationYearSelect" class="w-full form-select focus:shadow-primary-outline dark:bg-slate-850 dark:text-white text-sm leading-5.6 ease block appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none">
                        <option value="">Pilih Tahun Lulus...</option>
                      </select>
                    </div>
                    <div class="relative flex-1">
                      <label for="userSelect" class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Tambah User Individual:</label>
                      <select id="userSelect" class="w-full form-select focus:shadow-primary-outline dark:bg-slate-850 dark:text-white text-sm leading-5.6 ease block appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none">
                            <option></option>
                        </select>
                    </div>
                  </div>
                  @endif
                </div>



                <div class="flex-auto px-0 pt-0 pb-2 min-w-0 w-full max-w-full">
                  <div class="px-6 py-4 w-full max-w-full min-w-0 block overflow-x-auto">
                    <table id="usersTable" class="items-center w-full mb-0 align-top border-collapse dark:border-white/40 text-slate-500">
                      <thead class="align-bottom">
                        <tr>
                            <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70"></th>
                            <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Nama</th>
                            <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Email</th>
                            <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Status Pengerjaan</th>
                            <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Waktu Undangan Terkirim</th>
                            <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Waktu Terakhir Reminder</th>
                            <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Total Reminder</th>
                            <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Waktu Jawaban Terkirim</th>
                            <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Aksi</th>
                        </tr>
                        <tr>
                            <th class="px-2 py-2 text-center whitespace-nowrap">
                                <label class="flex items-center justify-center gap-1 cursor-pointer mb-0">
                                    <input type="checkbox" id="selectAllUsers" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                    <span class="text-xs text-slate-500 font-medium">Select All</span>
                                </label>
                            </th>
                            <th class="px-2 py-2"><input type="text" class="w-full text-xs rounded border-gray-300 px-2 py-1" /></th>
                            <th class="px-2 py-2"><input type="text" class="w-full text-xs rounded border-gray-300 px-2 py-1" /></th>
                            <th class="px-2 py-2">
                                <select class="w-full text-xs rounded border-gray-300 px-2 py-1">
                                    <option value="">Semua</option>
                                    <option value="Email undangan terkirim">Email undangan terkirim</option>
                                    <option value="Survei dibuka/sedang mengisi">Survei dibuka/sedang mengisi</option>
                                    <option value="Jawaban dikirim">Jawaban dikirim</option>
                                    <option value="Belum diundang">Belum diundang</option>
                                </select>
                            </th>
                            <th class="px-2 py-2"><input type="text" class="w-full text-xs rounded border-gray-300 px-2 py-1" /></th>
                            <th class="px-2 py-2"><input type="text" class="w-full text-xs rounded border-gray-300 px-2 py-1" /></th>
                            <th class="px-2 py-2"><input type="text" class="w-full text-xs rounded border-gray-300 px-2 py-1" /></th>
                            <th class="px-2 py-2"><input type="text" class="w-full text-xs rounded border-gray-300 px-2 py-1" /></th>
                            <th></th>
                        </tr>
                      </thead>
                      <tbody>

                        @foreach($survey_user as $responden)
                                @php
                                    $statusPengerjaan = 'Belum diundang';
                                    $statusColor = 'bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-300';
                                    
                                    if ($responden->status == 1) {
                                        $statusPengerjaan = 'Jawaban dikirim';
                                        $statusColor = 'bg-emerald-100 text-emerald-600 dark:bg-emerald-800/30 dark:text-emerald-400';
                                    } elseif ($responden->status == 0 && ($responden->current_question_id || $responden->tanggal_mengisi)) {
                                        $statusPengerjaan = 'Survei dibuka/sedang mengisi';
                                        $statusColor = 'bg-amber-100 text-amber-600 dark:bg-amber-800/30 dark:text-amber-400';
                                    } elseif ($responden->status == 0 && $responden->invitation_email_sent_at) {
                                        $statusPengerjaan = 'Email undangan terkirim';
                                        $statusColor = 'bg-blue-100 text-blue-600 dark:bg-blue-800/30 dark:text-blue-400';
                                    }
                                @endphp
                                <tr>
                                    <td class="p-2 text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                        <input type="checkbox" class="user-checkbox rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" value="{{ $responden->user_id }}">
                                    </td>
                                    <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                        <div class="flex flec-col px-2 py-1">
                                            <h6 class="mb-0 text-sm leading-normal dark:text-white">{{ $responden->nama }}</h6>
                                        </div>
                                    </td>
                                    <td class="p-2 text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                        <span class="text-xs font-semibold leading-tight dark:text-white dark:opacity-80 text-slate-400">{{ $responden->email }}</span>
                                    </td>
                                    <td class="p-2 text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                        <span class="inline-block px-2.5 py-1 text-xs font-bold leading-tight rounded-md {{ $statusColor }}">{{ $statusPengerjaan }}</span>
                                    </td>
                                    <td class="p-2 text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                        <span class="text-xs font-semibold leading-tight dark:text-white dark:opacity-80 text-slate-400">{{ $responden->invitation_email_sent_at ? \Carbon\Carbon::parse($responden->invitation_email_sent_at)->format('d-m-Y H:i:s') : '-' }}</span>
                                    </td>
                                    <td class="p-2 text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                        <span class="text-xs font-semibold leading-tight dark:text-white dark:opacity-80 text-slate-400">{{ $responden->last_reminder_email_sent_at ? \Carbon\Carbon::parse($responden->last_reminder_email_sent_at)->format('d-m-Y H:i:s') : '-' }}</span>
                                    </td>
                                    <td class="p-2 text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                        <span class="text-xs font-semibold leading-tight dark:text-white dark:opacity-80 text-slate-400">{{ $responden->reminder_email_count }}</span>
                                    </td>
                                    <td class="p-2 text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                        <span class="text-xs font-semibold leading-tight dark:text-white dark:opacity-80 text-slate-400">{{ $responden->tanggal_mengisi ? \Carbon\Carbon::parse($responden->tanggal_mengisi)->format('d-m-Y H:i:s') : '-' }}</span>
                                    </td>
                                    <td class="p-2 text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                                    <div class="icon-container">
                                            <!-- Delete -->
                                            <a href="javascript:;" class="icon-link" data-tooltip="Delete" onclick="event.preventDefault(); document.getElementById('delete-form-{{ $responden->survey_user_id }}').submit();">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                            <form id="delete-form-{{ $responden->survey_user_id }}" action="{{ route('admin.survey_user.destroy', $responden->survey_user_id) }}" method="POST" style="display: none;">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                    </table>
                  </div>
                </div>
              </div>
            </div>
        </div>
        </div>

        <script>
            function switchTab(tabId) {
                // Hide all tab panels
                document.getElementById('pertanyaan').classList.add('hidden');
                document.getElementById('user').classList.add('hidden');
                
                // Reset all tab buttons
                const btnPertanyaan = document.getElementById('pertanyaan-tab');
                const btnUser = document.getElementById('user-tab');
                
                btnPertanyaan.className = "inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300 dark:text-gray-400 font-bold";
                btnUser.className = "inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300 dark:text-gray-400 font-bold";
                
                // Show selected tab panel
                document.getElementById(tabId).classList.remove('hidden');
                
                // Highlight selected tab button
                if(tabId === 'pertanyaan') {
                    btnPertanyaan.className = "inline-block p-4 border-b-2 rounded-t-lg border-blue-500 text-blue-600 dark:text-blue-500 dark:border-blue-500 font-bold";
                } else {
                    btnUser.className = "inline-block p-4 border-b-2 rounded-t-lg border-blue-500 text-blue-600 dark:text-blue-500 dark:border-blue-500 font-bold";
                }
            }
        </script>



@endsection

@push('scripts')
<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<style>
    .dataTables_wrapper {
        margin-top: 1.5rem;
    }
    .dataTables_filter {
        margin-bottom: 1rem !important;
    }
    .dataTables_length {
        margin: 0 !important;
    }
    .dataTables_length label, .dataTables_filter label {
        display: flex;
        align-items: center;
        margin-bottom: 0 !important;
    }
    .dataTables_length select {
        margin: 0 0.5rem !important;
        width: 65px !important;
        padding: 0.25rem 1.5rem 0.25rem 0.5rem !important;
        border-radius: 0.375rem !important;
        border-color: #d1d5db !important;
        font-size: 0.875rem !important;
    }
    .dataTables_filter input {
        border: 1px solid #d1d5db !important;
        border-radius: 0.375rem !important;
        padding: 0.25rem 0.5rem !important;
        margin-left: 0.5rem !important;
        outline: none !important;
        font-size: 0.875rem !important;
    }
    .dataTables_filter input:focus {
        border-color: #3b82f6 !important;
        box-shadow: 0 0 0 1px #3b82f6 !important;
    }
    table.dataTable thead th, table.dataTable tfoot th {
        border-bottom: 1px solid #e5e7eb !important;
    }
    table.dataTable.no-footer {
        border-bottom: 1px solid #e5e7eb !important;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button {
        padding: 0.25rem 0.75rem !important;
        margin-left: 0.25rem !important;
        border-radius: 0.375rem !important;
        border: 1px solid transparent !important;
        font-size: 0.875rem !important;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button.current, 
    .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
        background: #3b82f6 !important;
        color: white !important;
        border-color: #3b82f6 !important;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
        background: #e5e7eb !important;
        color: #374151 !important;
        border-color: #d1d5db !important;
    }
</style>
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    $('#userSelect').select2({
        theme: 'bootstrap-5',
        allowClear: true,
        dropdownParent: $('#userSelect').parent(),
        templateResult: formatUser,
        templateSelection: formatUser,
        width: '100%',
        ajax: {
            url: '{{ route("admin.search_user") }}',
            dataType: 'json',
            delay: 250,
            data: function(params) {
                return {
                    search: params.term,
                    type: '{{ $survey->type_survei }}',
                    survey_id: '{{ $survey->id }}'
                };
            },
            processResults: function(data) {
                return {
                    results: data.map(function(item) {
                        return {
                            id: item.id,
                            text: item.name + ' (' + item.email + ')',
                            user: item
                        };
                    })
                };
            },
            cache: true
        },
        minimumInputLength: 2
    });

    function formatUser(user) {
        if (!user.id) return user.text;
        return $(`
            <div class="flex items-center py-1">
                <div class="flex-1">
                    <div class="text-sm font-medium text-gray-900 dark:text-white">${user.text}</div>
                </div>
            </div>
        `);
    }

    $('#userSelect').on('select2:select', function(e) {
        const selectedUser = e.params.data.user;

        $.ajax({
            url: '{{ route("admin.survey.add_user") }}',
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                user_id: selectedUser.id,
                survey_id: '{{ $survey->id }}'
            },
            success: function(response) {
                const alertDiv = $(`
                    <div class="fixed top-4 right-4 bg-green-100 border-t-4 border-green-500 rounded-b text-green-900 px-4 py-3 shadow-md" role="alert">
                        <div class="flex">
                            <div class="py-1">
                                <svg class="fill-current h-6 w-6 text-green-500 mr-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path d="M10 0C4.48 0 0 4.48 0 10s4.48 10 10 10 10-4.48 10-10S15.52 0 10 0zm5 7.5l-6.25 6.25-3.75-3.75 1.41-1.41 2.34 2.34 4.84-4.84L15 7.5z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="font-bold">${response.message || 'User added successfully!'}</p>
                            </div>
                        </div>
                    </div>
                `);
                $('body').append(alertDiv);
                setTimeout(() => alertDiv.remove(), 3000);

                $('#userSelect').val(null).trigger('change');
                location.reload();
            },
            error: function(xhr) {
                console.error('Add user error:', xhr);
                const message = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Terjadi kesalahan saat menambahkan user.';
                const alertDiv = $(`
                    <div class="fixed top-4 right-4 bg-red-100 border-t-4 border-red-500 rounded-b text-red-900 px-4 py-3 shadow-md" role="alert">
                        <div class="flex">
                            <div class="py-1">
                                <svg class="fill-current h-6 w-6 text-red-500 mr-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path d="M2.93 17.07A10 10 0 1 1 17.07 2.93 10 10 0 0 1 2.93 17.07zm12.73-1.41A8 8 0 1 0 4.34 4.34a8 8 0 0 0 11.32 11.32zM9 11V9h2v6H9v-4zm0-6h2v2H9V5z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="font-bold">${message}</p>
                            </div>
                        </div>
                    </div>
                `);
                $('body').append(alertDiv);
                setTimeout(() => alertDiv.remove(), 3000);
            }
        });
    });

    // Load graduation years on page load
    $.ajax({
        url: '{{ route("admin.get_graduation_years") }}',
        method: 'GET',
        success: function(response) {
            if (response.success) {
                const graduationYearSelect = $('#graduationYearSelect');
                response.years.forEach(function(year) {
                    graduationYearSelect.append(`<option value="${year}">${year}</option>`);
                });
            }
        },
        error: function(xhr) {
            console.error('Error loading graduation years:', xhr.responseJSON);
        }
    });

    // Handle graduation year selection
    $('#graduationYearSelect').on('change', function() {
        const selectedYear = $(this).val();

        if (selectedYear) {
            if (confirm(`Apakah Anda yakin ingin menambahkan semua lulusan yang lulus pada tahun ${selectedYear} ke dalam survey ini?`)) {
                $.ajax({
                    url: '{{ route("admin.survey.add_lulusan_by_graduation_year") }}',
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        tahun_lulus: selectedYear,
                        survey_id: '{{ $survey->id }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            const alertDiv = $(`
                                <div class="fixed top-4 right-4 bg-green-100 border-t-4 border-green-500 rounded-b text-green-900 px-4 py-3 shadow-md" role="alert">
                                    <div class="flex">
                                        <div class="py-1">
                                            <svg class="fill-current h-6 w-6 text-green-500 mr-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                                <path d="M10 0C4.48 0 0 4.48 0 10s4.48 10 10 10 10-4.48 10-10S15.52 0 10 0zm5 7.5l-6.25 6.25-3.75-3.75 1.41-1.41 2.34 2.34 4.84-4.84L15 7.5z"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="font-bold">${response.message}</p>
                                        </div>
                                    </div>
                                </div>
                            `);
                            $('body').append(alertDiv);
                            setTimeout(() => alertDiv.remove(), 5000);

                            // Reset the select
                            $('#graduationYearSelect').val('');

                            // Reload the page to show updated user list
                            setTimeout(() => location.reload(), 1000);
                        } else {
                            const alertDiv = $(`
                                <div class="fixed top-4 right-4 bg-red-100 border-t-4 border-red-500 rounded-b text-red-900 px-4 py-3 shadow-md" role="alert">
                                    <div class="flex">
                                        <div class="py-1">
                                            <svg class="fill-current h-6 w-6 text-red-500 mr-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                                <path d="M2.93 17.07A10 10 0 1 1 17.07 2.93 10 10 0 0 1 2.93 17.07zm12.73-1.41A8 8 0 1 0 4.34 4.34a8 8 0 0 0 11.32 11.32zM9 11V9h2v6H9v-4zm0-6h2v2H9V5z"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="font-bold">${response.message}</p>
                                        </div>
                                    </div>
                                </div>
                            `);
                            $('body').append(alertDiv);
                            setTimeout(() => alertDiv.remove(), 5000);
                        }
                    },
                    error: function(xhr) {
                        const message = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Terjadi kesalahan saat menambahkan lulusan.';
                        const alertDiv = $(`
                            <div class="fixed top-4 right-4 bg-red-100 border-t-4 border-red-500 rounded-b text-red-900 px-4 py-3 shadow-md" role="alert">
                                <div class="flex">
                                    <div class="py-1">
                                        <svg class="fill-current h-6 w-6 text-red-500 mr-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                            <path d="M2.93 17.07A10 10 0 1 1 17.07 2.93 10 10 0 0 1 2.93 17.07zm12.73-1.41A8 8 0 1 0 4.34 4.34a8 8 0 0 0 11.32 11.32zM9 11V9h2v6H9v-4zm0-6h2v2H9V5z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="font-bold">Error: ${message}</p>
                                    </div>
                                </div>
                            </div>
                        `);
                        $('body').append(alertDiv);
                        setTimeout(() => alertDiv.remove(), 5000);
                    }
                });
            } else {
                // Reset selection if user cancels
                $('#graduationYearSelect').val('');
            }
        }
    });
});

$(document).ready(function() {
    // Function to show success message
    function showSuccessMessage(message) {
        const alertDiv = $(
            `<div class="fixed top-4 right-4 bg-green-100 border-t-4 border-green-500 rounded-b text-green-900 px-4 py-3 shadow-md z-50" role="alert">
                <div class="flex">
                    <div class="py-1">
                        <svg class="fill-current h-6 w-6 text-green-500 mr-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                            <path d="M10 0C4.48 0 0 4.48 0 10s4.48 10 10 10 10-4.48 10-10S15.52 0 10 0zm5 7.5l-6.25 6.25-3.75-3.75 1.41-1.41 2.34 2.34 4.84-4.84L15 7.5z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="font-bold">${message}</p>
                    </div>
                </div>
            </div>`
        );
        $('body').append(alertDiv);
        setTimeout(() => alertDiv.remove(), 5000);
    }

    // Function to show error message
    function showErrorMessage(message) {
        const alertDiv = $(
            `<div class="fixed top-4 right-4 bg-red-100 border-t-4 border-red-500 rounded-b text-red-900 px-4 py-3 shadow-md z-50" role="alert">
                <div class="flex">
                    <div class="py-1">
                        <svg class="fill-current h-6 w-6 text-red-500 mr-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                            <path d="M2.93 17.07A10 10 0 1 1 17.07 2.93 10 10 0 0 1 2.93 17.07zm12.73-1.41A8 8 0 1 0 4.34 4.34a8 8 0 0 0 11.32 11.32zM9 11V9h2v6H9v-4zm0-6h2v2H9V5z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="font-bold">${message}</p>
                    </div>
                </div>
            </div>`
        );
        $('body').append(alertDiv);
        setTimeout(() => alertDiv.remove(), 5000);
    }

    // Function to show loading state
    function setButtonLoading(btn, loading) {
        if (loading) {
            btn.prop('disabled', true);
            btn.find('i').removeClass().addClass('fas fa-spinner fa-spin mr-2');
            btn.append(' <span class="loading-text">Mengirim...</span>');
        } else {
            btn.prop('disabled', false);
            btn.find('.loading-text').remove();
        }
    }

    // Helper to get selected user IDs
    function getSelectedUserIds() {
        var userIds = [];
        $('.user-checkbox:checked').each(function() {
            userIds.push($(this).val());
        });
        return userIds;
    }

    // Send invitation emails
    $('#sendInvitationBtn').on('click', function() {
        const btn = $(this);
        const surveyId = btn.data('survey-id');
        const userIds = getSelectedUserIds();

        const confirmMsg = userIds.length > 0 
            ? `Apakah Anda yakin ingin mengirim email undangan ke ${userIds.length} pengguna terpilih?` 
            : 'Apakah Anda yakin ingin mengirim email undangan ke semua pengguna survei ini?';

        if (confirm(confirmMsg)) {
            setButtonLoading(btn, true);

            $.ajax({
                url: '{{ route("admin.send_email", ":id") }}'.replace(':id', surveyId),
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    user_ids: userIds
                },
                success: function(response) {
                    setButtonLoading(btn, false);
                    btn.find('i').removeClass().addClass('fas fa-paper-plane mr-2');
                    showSuccessMessage(response.message || 'Email undangan berhasil dikirim!');
                },
                error: function(xhr) {
                    setButtonLoading(btn, false);
                    btn.find('i').removeClass().addClass('fas fa-paper-plane mr-2');
                    const msg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Terjadi kesalahan saat mengirim email undangan.';
                    showErrorMessage(msg);
                }
            });
        }
    });

    // Send reminder emails
    $('#sendReminderBtn').on('click', function() {
        const btn = $(this);
        const surveyId = btn.data('survey-id');
        const userIds = getSelectedUserIds();

        const confirmMsg = userIds.length > 0 
            ? `Apakah Anda yakin ingin mengirim email reminder ke ${userIds.length} pengguna terpilih (yang belum mengisi survei)?` 
            : 'Apakah Anda yakin ingin mengirim email reminder ke semua pengguna yang belum mengisi survei?';

        if (confirm(confirmMsg)) {
            setButtonLoading(btn, true);

            $.ajax({
                url: '{{ route("admin.send_reminders", ":id") }}'.replace(':id', surveyId),
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    user_ids: userIds
                },
                success: function(response) {
                    setButtonLoading(btn, false);
                    btn.find('i').removeClass().addClass('fas fa-bell mr-2');
                    showSuccessMessage(response.message || 'Email reminder berhasil dikirim!');
                },
                error: function(xhr) {
                    setButtonLoading(btn, false);
                    btn.find('i').removeClass().addClass('fas fa-bell mr-2');
                    const msg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Terjadi kesalahan saat mengirim email reminder.';
                    showErrorMessage(msg);
                }
            });
        }
    });

    // Send thank you emails
    $('#sendThankYouBtn').on('click', function() {
        const btn = $(this);
        const surveyId = btn.data('survey-id');
        const userIds = getSelectedUserIds();

        const confirmMsg = userIds.length > 0 
            ? `Apakah Anda yakin ingin mengirim email terima kasih ke ${userIds.length} pengguna terpilih (yang sudah mengisi survei)?` 
            : 'Apakah Anda yakin ingin mengirim email terima kasih ke semua pengguna yang sudah mengisi survei?';

        if (confirm(confirmMsg)) {
            setButtonLoading(btn, true);

            $.ajax({
                url: '{{ route("admin.send_bulk_thank_you", ":id") }}'.replace(':id', surveyId),
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    user_ids: userIds
                },
                success: function(response) {
                    setButtonLoading(btn, false);
                    btn.find('i').removeClass().addClass('fas fa-handshake mr-2');
                    showSuccessMessage(response.message || 'Email terima kasih berhasil dikirim!');
                },
                error: function(xhr) {
                    setButtonLoading(btn, false);
                    btn.find('i').removeClass().addClass('fas fa-handshake mr-2');
                    const msg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Terjadi kesalahan saat mengirim email terima kasih.';
                    showErrorMessage(msg);
                }
            });
        }
    });

    // Existing sendEmailBtn handler (keep for backward compatibility)
    $('#sendEmailBtn').on('click', function() {
        var surveyId = $(this).data('survey-id');
        $.ajax({
            url: '{{ route("admin.send_email", ":id") }}'.replace(':id', surveyId),
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                showSuccessMessage(response.message);
            },
            error: function(xhr) {
                const msg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Terjadi kesalahan.';
                showErrorMessage(msg);
            }
        });
    });

    // Auto-hide existing alert messages
    setTimeout(() => {
        document.querySelectorAll('.alert-danger').forEach(el => {
            el.classList.add('opacity-0', 'transition-opacity', 'duration-500');
            setTimeout(() => el.remove(), 500);
        });
    }, 3000);

    // Initialize DataTables
    var table = $('#usersTable').DataTable({
        pageLength: 10,
        responsive: true,
        dom: 'rt<"flex flex-col sm:flex-row justify-between items-center mt-4"<"mb-4 sm:mb-0"i><"flex items-center gap-4"<"mb-4 sm:mb-0"l><"mb-4 sm:mb-0"p>>>',
        orderCellsTop: true,
        order: [], // Let user choose initial sorting or disable initial sort
        columnDefs: [
            { targets: -1, orderable: false, searchable: false } // Disable sort/search on Action column
        ]
    });

    // Event listener for "Select All" checkbox
    $('#selectAllUsers').on('click', function(){
        var rows = table.rows({ 'search': 'applied' }).nodes();
        $('input[type="checkbox"].user-checkbox', rows).prop('checked', this.checked);
    });

    // Apply column search
    $('#usersTable thead tr:eq(1) th').each(function(i) {
        // Skip the first column (Pilih) and the last column (Aksi)
        if (i === 0 || i === $('#usersTable thead tr:eq(1) th').length - 1) return;

        $('input, select', this).on('keyup change', function() {
            if (table.column(i).search() !== this.value) {
                table
                    .column(i)
                    .search(this.value)
                    .draw();
            }
        });
    });
});
</script>
@endpush