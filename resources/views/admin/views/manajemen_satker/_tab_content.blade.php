{{-- Partial view for each tab's content (Jabatan / Satuan Kerja / Unit Kerja) --}}
<div class="p-4 min-w-0 w-full max-w-full">
    <!-- Action Buttons -->
    @if(!auth()->user()->hasRole('supervisor'))
    <div class="flex flex-wrap gap-2 mb-4">
        <button type="button" onclick="openCreateModal('{{ $type }}', '{{ $label }}')"
            class="inline-flex items-center px-4 py-2 font-bold text-center text-white bg-blue-500 border-0 rounded-lg shadow-md cursor-pointer text-xs hover:shadow-xs hover:-translate-y-px active:opacity-85 transition-all">
            <i class="fas fa-plus mr-2"></i> Tambah {{ $label }}
        </button>
        <button type="button" onclick="openImportModal('{{ $type }}', '{{ $label }}')"
            class="inline-flex items-center px-4 py-2 font-bold text-center text-white bg-blue-500 border-0 rounded-lg shadow-md cursor-pointer text-xs hover:shadow-xs hover:-translate-y-px active:opacity-85 transition-all">
            <i class="fas fa-file-upload mr-2"></i> Import
        </button>
        <a href="{{ route('admin.manajemenSatker.export', ['type' => $type]) }}">
            <button type="button"
                class="inline-flex items-center px-4 py-2 font-bold text-center text-white bg-green-500 border-0 rounded-lg shadow-md cursor-pointer text-xs hover:shadow-xs hover:-translate-y-px active:opacity-85 transition-all">
                <i class="fas fa-file-excel mr-2"></i> Export
            </button>
        </a>
    </div>
    @endif

    <!-- Table -->
    <div class="overflow-x-auto w-full max-w-full min-w-0 block">
        <table class="items-center w-full mb-0 align-top border-collapse dark:border-white/40 text-slate-500">
            <thead class="align-bottom">
                <tr>
                    <th class="px-3 py-2 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70" style="width: 50px;">
                        No</th>
                    <th class="px-3 py-2 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70">
                        Nama {{ $label }}</th>
                    @if(!auth()->user()->hasRole('supervisor'))
                    <th class="px-3 py-2 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none dark:border-white/40 dark:text-white text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 opacity-70" style="width: 120px;">
                        Aksi</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @forelse($items as $index => $item)
                <tr class="hover:bg-gray-50 dark:hover:bg-slate-700">
                    <td class="px-3 py-2 align-middle bg-transparent border-b dark:border-white/40 shadow-transparent">
                        <span class="text-xs leading-tight dark:text-white dark:opacity-80 text-slate-400">
                            {{ $items->firstItem() + $index }}
                        </span>
                    </td>
                    <td class="px-3 py-2 align-middle bg-transparent border-b dark:border-white/40 shadow-transparent">
                        <span class="text-xs leading-tight dark:text-white dark:opacity-80 text-slate-600 font-semibold">
                            {{ $item->nama }}
                        </span>
                    </td>
                    @if(!auth()->user()->hasRole('supervisor'))
                    <td class="px-3 py-2 text-center align-middle bg-transparent border-b dark:border-white/40 shadow-transparent">
                        <div class="flex items-center justify-center gap-2">
                            <a href="javascript:;" class="icon-link" data-tooltip="Edit"
                                onclick="openEditModal({{ $item->id }}, '{{ $type }}', '{{ $label }}', '{{ addslashes($item->nama) }}')">
                                <i class="fas fa-edit text-xs text-blue-500 hover:text-blue-700"></i>
                            </a>
                            <form action="{{ route('admin.manajemenSatker.destroy', $item->id) }}" method="POST" class="inline"
                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                                @csrf
                                @method('DELETE')
                                <input type="hidden" name="type" value="{{ $type }}">
                                <button type="submit" class="icon-link" data-tooltip="Delete">
                                    <i class="fas fa-trash text-xs text-red-500 hover:text-red-700"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                    @endif
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="px-3 py-8 text-center text-xs text-slate-400 dark:text-white/60">
                        <div class="flex flex-col items-center">
                            <i class="fas fa-inbox text-3xl text-slate-300 dark:text-slate-600 mb-2"></i>
                            <span>Belum ada data {{ $label }}</span>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($items->hasPages())
    <div class="p-3 border-t dark:border-white/10">
        {{ $items->links() }}
    </div>
    @endif
</div>
