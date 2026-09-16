<input type="date" 
       name="value" 
       value="{{ old('value', $existingAnswer ? $existingAnswer->jawaban : '') }}"
       {{ !empty($isIdentity) ? 'readonly' : '' }}
       class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm {{ !empty($isIdentity) ? 'bg-gray-100 cursor-not-allowed text-gray-700 font-medium' : '' }}">

@error('value')
    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
@enderror
