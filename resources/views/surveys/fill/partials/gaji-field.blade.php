@php
    $minGaji = $pertanyaan->min_gaji ?? 0;
    $minGajiFormatted = number_format($minGaji, 0, ',', '.');
@endphp
<div class="space-y-4" x-data="gajiInput()">
    <div class="relative w-full flex items-center">
        <div class="absolute inset-y-0 left-0 flex items-center pointer-events-none" style="left: 1rem; z-index: 10;">
            <span class="text-gray-500 font-semibold sm:text-sm">Rp</span>
        </div>
        <input type="text" 
               x-model="formattedValue" 
               @input="updateValue"
               @keydown="isNumber($event)"
               class="focus:ring-blue-500 focus:border-blue-500 block w-full pr-12 sm:text-sm border-gray-300 rounded-md py-3 shadow-sm" 
               style="padding-left: 3.25rem !important;"
               placeholder="Contoh: 5000000">
    </div>
    <input type="hidden" name="value" :value="rawValue">
    @if($minGaji > 0)
    <div x-show="rawValue && !isNaN(parseInt(rawValue, 10)) && parseInt(rawValue, 10) < {{ $minGaji }}" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 transform -translate-y-1"
         x-transition:enter-end="opacity-100 transform translate-y-0"
         class="p-3 bg-amber-50 border border-amber-300 text-amber-800 rounded-md text-xs sm:text-sm flex items-center gap-2 shadow-sm">
        <i class="fas fa-exclamation-triangle text-amber-500 text-sm flex-shrink-0"></i>
        <span>Peringatan: Nominal gaji yang dimasukkan kurang dari Rp {{ $minGajiFormatted }}. Mohon pastikan nominal yang Anda masukkan sudah benar.</span>
    </div>
    @endif
    <p class="text-xs text-gray-500 mt-2">
        <i class="fas fa-info-circle mr-1"></i> Masukkan angka saja tanpa tanda titik atau koma.
    </p>
</div>

<script>
function gajiInput() {
    return {
        rawValue: '{{ $existingAnswer ? $existingAnswer->jawaban : "" }}',
        formattedValue: '',
        init() {
            if (this.rawValue) {
                this.formatInitial();
            }
        },
        updateValue(e) {
            // Remove all non-numeric characters
            let val = e.target.value.replace(/\D/g, '');
            this.rawValue = val;
            
            // Format for display
            if (val) {
                this.formattedValue = new Intl.NumberFormat('id-ID').format(val);
            } else {
                this.formattedValue = '';
            }
        },
        formatInitial() {
            if (this.rawValue) {
                this.formattedValue = new Intl.NumberFormat('id-ID').format(this.rawValue);
            }
        },
        isNumber(e) {
            // Allow: Backspace, Tab, End, Home, Left, Right, Delete
            if ([46, 8, 9, 27, 13].indexOf(e.keyCode) !== -1 ||
                // Allow: Ctrl+A, Command+A
                (e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) ||
                // Allow: home, end, left, right, down, up
                (e.keyCode >= 35 && e.keyCode <= 40)) {
                return;
            }
            // Ensure that it is a number and stop the keypress if not
            if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                e.preventDefault();
            }
        }
    }
}
</script>
