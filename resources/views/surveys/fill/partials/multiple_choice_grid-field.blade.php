@php
    $scaleColumns = [
        1 => 'Sangat Tidak Setuju',
        2 => 'Tidak Setuju',
        3 => 'Netral',
        4 => 'Setuju',
        5 => 'Sangat Setuju',
    ];

    $existingGrid = [];
    if (!empty($existingAnswer?->jawaban)) {
        $decoded = json_decode($existingAnswer->jawaban, true);
        if (is_array($decoded)) {
            $existingGrid = $decoded;
        }
    }
@endphp

<div class="overflow-x-auto">
    <table class="min-w-full border border-gray-200 rounded-lg overflow-hidden">
        <thead class="bg-gray-50">
            <tr>
                <th class="text-left text-sm font-semibold text-gray-700 px-4 py-3 border-b border-r border-gray-200 min-w-[220px]">
                    Pernyataan
                </th>
                @foreach($scaleColumns as $score => $label)
                    <th class="text-center text-xs font-semibold text-gray-700 px-3 py-3 border-b border-gray-200 min-w-[120px]">
                        <div>{{ $score }}</div>
                        <div class="text-[11px] text-gray-500 mt-1">{{ $label }}</div>
                    </th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($options as $option)
                <tr class="hover:bg-gray-50">
                    <td class="text-sm text-gray-900 px-4 py-3 border-b border-r border-gray-200 align-top">
                        {{ $option->pilihan_jawaban }}
                    </td>

                    @foreach($scaleColumns as $score => $label)
                        <td class="text-center px-3 py-3 border-b border-gray-200">
                            <input type="radio"
                                   name="answer_grid[{{ $option->id }}]"
                                   value="{{ $score }}"
                                   @if((string)($existingGrid[$option->id] ?? '') === (string)$score) checked @endif
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                        </td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

@error('answer_grid')
    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
@enderror
@error('answer_grid.*')
    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
@enderror
