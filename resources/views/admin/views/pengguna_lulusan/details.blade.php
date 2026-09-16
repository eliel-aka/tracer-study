@extends('admin.layouts.app')
@section('title', 'Details')

@section('content')

<!-- table 1 info survei -->

        
        

        <!-- table 2 -->

        <div class="flex flex-wrap -mx-3">
          <div class="flex-none w-full max-w-full px-3 min-w-0">
            <div class="relative flex flex-col min-w-0 w-full max-w-full mb-6 break-words bg-white border-0 border-transparent border-solid shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border overflow-hidden">
              <div class="p-6 pb-0 mb-0 border-b-0 border-b-solid rounded-t-2xl border-b-transparent flex items-center justify-between">
                <h6 class="dark:text-white">Daftar Pengguna Lulusan</h6>
              </div>

              <div class="flex-auto px-0 pt-0 pb-2 min-w-0 w-full max-w-full">
                <div class="p-0 overflow-x-auto w-full max-w-full min-w-0 block">
                  <table class="items-center w-full mb-0 align-top border-collapse dark:border-white/40 text-slate-500">
                    <thead class="align-bottom">
                      <tr>
                        <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Nama</th>
                        <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">NIP</th>
                        <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Email</th>
                        <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">Jabatan</th> 
                      </tr>
                    </thead>
                    <tbody>
                    @foreach($lulusan as $lulusanus)  
                    <tr>
                      <td class="p-2 align-middle bg-transparent border-b dark:border-white/40 shadow-transparent">
                        <div class="flex flex-col px-2 py-1">
                          <h6 class="mb-0 text-sm leading-normal dark:text-white break-words whitespace-normal">
                            {{ $lulusanus->nama }}
                          </h6> 
                        </div>
                      </td>

                      <td class="p-2 text-center align-middle bg-transparent border-b dark:border-white/40 shadow-transparent">
                        <div class="flex flex-col text-xs font-semibold leading-tight dark:text-white dark:opacity-80 text-slate-400">
                          <span>Baru: {{ $lulusanus->nip_baru ?? '-' }}</span>
                          <span>Lama: {{ $lulusanus->nip_lama ?? '-' }}</span>
                        </div>
                      </td>

                      <td class="p-2 text-center align-middle bg-transparent border-b dark:border-white/40 shadow-transparent">
                        <span class="text-xs font-semibold leading-tight dark:text-white dark:opacity-80 text-slate-400 break-words whitespace-normal">
                          {{ $lulusanus->email }}
                        </span>
                      </td>

                      <td class="p-2 text-center align-middle bg-transparent border-b dark:border-white/40 shadow-transparent">
                        <span class="text-xs font-semibold leading-tight dark:text-white dark:opacity-80 text-slate-400 break-words whitespace-normal">
                          {{ $lulusanus->jabatan }}
                        </span>
                      </td>
                    </tr>

                    @endforeach
                    </tbody>
                  </table>
                  <div class="p-4">
                    {{ $lulusan->links() }}
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- table 3 -->

        

        <!-- biar otomatis ke bawah -->
        <script>
        window.addEventListener('DOMContentLoaded', () => {
          const cells = document.querySelectorAll('td span, td h6');

          cells.forEach(cell => {
            cell.style.wordWrap = 'break-word';
            cell.style.whiteSpace = 'normal';
          });
        });
      </script>

@endsection