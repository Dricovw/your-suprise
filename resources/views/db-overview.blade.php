<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>DB Overview</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-950 text-gray-100 p-8 font-mono">

    <h1 class="text-3xl font-bold text-white mb-2">🗄️ Database Overview</h1>
    <p class="text-gray-400 mb-8">{{ count($overview) }} tables found</p>

    @foreach ($overview as $table)
    <div class="mb-10 border border-gray-700 rounded-xl overflow-hidden">

        {{-- Table Header --}}
        <div class="bg-gray-800 px-6 py-4 flex justify-between items-center">
            <div>
                <span class="text-indigo-400 font-bold text-lg">{{ $table['name'] }}</span>
                <span class="ml-3 text-gray-400 text-sm">
                    {{ count($table['columns']) }} columns
                </span>
            </div>
            <span class="bg-indigo-600 text-white text-xs px-3 py-1 rounded-full">
                {{ number_format($table['count']) }} rows
            </span>
        </div>

        {{-- Column Badges --}}
        <div class="bg-gray-900 px-6 py-3 flex flex-wrap gap-2 border-b border-gray-700">
            @foreach ($table['columns'] as $col)
            <span class="bg-gray-800 border border-gray-600 text-xs px-2 py-1 rounded text-gray-300">
                <span class="text-indigo-300">{{ $col->name }}</span>
                <span class="text-gray-500 ml-1">{{ $col->type }}</span>
                @if ($col->pk)
                    <span class="text-yellow-400 ml-1">PK</span>
                @endif
            </span>
            @endforeach
        </div>

        {{-- Data Preview --}}
        @if (count($table['rows']) > 0)
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-800 text-gray-400 text-xs uppercase">
                    <tr>
                        @foreach ($table['columns'] as $col)
                        <th class="px-4 py-2 text-left">{{ $col->name }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach ($table['rows'] as $i => $row)
                    <tr class="{{ $i % 2 === 0 ? 'bg-gray-950' : 'bg-gray-900' }} border-t border-gray-800 hover:bg-gray-800 transition">
                        @foreach ($table['columns'] as $col)
                        <td class="px-4 py-2 text-gray-300 max-w-xs truncate">
                            {{ $row->{$col->name} ?? 'NULL' }}
                        </td>
                        @endforeach
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <p class="px-6 py-4 text-gray-500 text-sm">No data in this table.</p>
        @endif

    </div>
    @endforeach

</body>
</html>