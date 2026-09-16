@php
    $isFieldLocked = !empty($isIdentity) || (!empty($question) && !empty($question->is_readonly));
@endphp
<div class="space-y-3">
    @foreach($options as $option)
        <label class="flex items-center p-3 border border-gray-200 rounded-lg {{ $isFieldLocked ? 'bg-gray-50 opacity-80 cursor-not-allowed pointer-events-none' : 'hover:bg-gray-50 cursor-pointer' }} transition-colors">
            <input type="radio" 
                   name="answer_option_id" 
                   value="{{ $option->id }}"
                   @if($existingAnswer && $existingAnswer->jawaban == $option->id) checked @endif
                   {{ $isFieldLocked ? 'disabled' : '' }}
                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 {{ $isFieldLocked ? 'cursor-not-allowed' : '' }}"
                   style="margin-right: 0.75rem !important;">
            <span class="text-gray-900 {{ $isFieldLocked ? 'cursor-not-allowed' : '' }}">{{ $option->pilihan_jawaban }}</span>
        </label>
    @endforeach
</div>

@error('answer_option_id')
    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
@enderror
