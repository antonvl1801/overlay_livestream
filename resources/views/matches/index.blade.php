<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Football Matches</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/app.css')
</head>

<body class="bg-gray-100 text-gray-900">

    <div class="max-w-6xl mx-auto p-6">
        <h1 class="text-3xl font-bold mb-6">⚽ Football Matches</h1>

        <!-- Match Form -->
        <div class="bg-white p-6 rounded shadow mb-8">
            <h2 class="text-xl font-semibold mb-4">{{ isset($editMatch) ? '✏️ Edit Match' : '➕ Add Match' }}</h2>
            <form action="{{ isset($editMatch) ? route('matches.update', $editMatch->id) : route('matches.store') }}"
                method="POST" class="space-y-4">
                @csrf
                @if (isset($editMatch))
                    @method('PUT')
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium">Match Code</label>
                        <input type="text" name="code" value="{{ old('code', $editMatch->code ?? '') }}" required
                            class="mt-1 block w-full border rounded p-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium">Start Time</label>
                        <input type="datetime-local" name="started_at"
                            value="{{ old('started_at', isset($editMatch) ? \Carbon\Carbon::parse($editMatch->started_at)->format('Y-m-d\TH:i') : '') }}"
                            class="mt-1 block w-full border rounded p-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium">Home Team</label>
                        <select name="home_team_id" class="mt-1 block w-full border rounded p-2" required>
                            <option value="">-- Select Home Team --</option>
                            @foreach ($teams as $team)
                                <option value="{{ $team->id }}" @selected(old('home_team_id', $editMatch->home_team_id ?? '') == $team->id)>
                                    {{ $team->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium">Away Team</label>
                        <select name="away_team_id" class="mt-1 block w-full border rounded p-2" required>
                            <option value="">-- Select Away Team --</option>
                            @foreach ($teams as $team)
                                <option value="{{ $team->id }}" @selected(old('away_team_id', $editMatch->away_team_id ?? '') == $team->id)>
                                    {{ $team->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="flex items-center space-x-4">
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                        {{ isset($editMatch) ? 'Update Match' : 'Add Match' }}
                    </button>
                    @if (isset($editMatch))
                        <a href="{{ route('matches.index') }}" class="text-sm text-gray-600 hover:underline">Cancel</a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Matches Table -->
        <div class="bg-white p-6 rounded shadow">
            <h2 class="text-xl font-semibold mb-4">📋 Match List</h2>
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-left font-medium text-gray-600">Code</th>
                        <th class="px-4 py-2 text-left font-medium text-gray-600">Home Team</th>
                        <th class="px-4 py-2 text-left font-medium text-gray-600">Away Team</th>
                        <th class="px-4 py-2 text-center font-medium text-gray-600">Score</th>
                        <th class="px-4 py-2 text-left font-medium text-gray-600">Start Time</th>
                        <th class="px-4 py-2 text-right font-medium text-gray-600">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($matches as $match)
                        <tr>
                            <td class="px-4 py-2">{{ $match->code }}</td>
                            <td class="px-4 py-2">{{ $match->homeTeam->name }}</td>
                            <td class="px-4 py-2">{{ $match->awayTeam->name }}</td>
                            <td class="px-4 py-2 text-center">{{ $match->home_score }} - {{ $match->away_score }}</td>
                            <td class="px-4 py-2">{{ $match->started_at ?? '-' }}</td>
                            <td class="px-4 py-2 text-right space-x-2">
                                <a href="{{ route('matches.edit', $match->id) }}"
                                    class="text-blue-600 hover:underline text-sm">Edit</a>
                                <form action="{{ route('matches.destroy', $match->id) }}" method="POST"
                                    class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:underline text-sm"
                                        onclick="return confirm('Are you sure you want to delete this match?')">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</body>

</html>
