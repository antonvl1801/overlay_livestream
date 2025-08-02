<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Live Match</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/app.css')
</head>

<body class="bg-gray-100 text-gray-900">
    @if (session('success'))
        <div class="bg-green-100 text-green-700 p-2 rounded mb-2">
            {{ session('success') }}
        </div>
    @endif

    <div class="max-w-4xl mx-auto py-8 px-4">
        <h1 class="text-3xl font-bold mb-4">
            {{ $match->homeTeam->name }} vs {{ $match->awayTeam->name }}
        </h1>
        <p class="text-xl mb-2">
            Score: <span class="font-semibold">{{ $match->home_score }} - {{ $match->away_score }}</span>
        </p>

        <hr class="my-6">

        <h2 class="text-2xl font-semibold mb-2">➕ Add Goal</h2>
        <form method="POST" action="{{ route('live.match.goal', $match->code) }}"
            class="space-y-4 bg-white p-4 rounded shadow">
            @csrf
            <div>
                <label class="block text-sm font-medium">Team</label>
                <select name="team_id" class="mt-1 block w-full border rounded p-2">
                    <option value="{{ $match->home_team_id }}">{{ $match->homeTeam->name }}</option>
                    <option value="{{ $match->away_team_id }}">{{ $match->awayTeam->name }}</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium">Scorer Name</label>
                <input type="text" name="scorer_name" class="mt-1 block w-full border rounded p-2" required>
            </div>
            <div>
                <label class="block text-sm font-medium">Minute</label>
                <input type="number" name="minute" class="mt-1 block w-full border rounded p-2" required>
            </div>
            <div class="flex items-center space-x-2">
                <input type="checkbox" name="is_own_goal" id="is_own_goal">
                <label for="is_own_goal">Own Goal</label>
            </div>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                Add Goal
            </button>
        </form>

        <hr class="my-6">

        <h2 class="text-2xl font-semibold mb-4">⚽ Goals</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div>
                <h3 class="text-xl font-medium mb-2">{{ $match->homeTeam->name }}</h3>
                <ul class="space-y-1">
                    @foreach ($homeGoals as $goal)
                        <li class="bg-gray-200 p-2 rounded flex justify-between items-center">
                            <span>
                                {{ $goal->minute }}' - {{ $goal->scorer_name }}
                                {{ $goal->is_own_goal ? '(OG)' : '' }}
                            </span>

                            <form method="POST" action="{{ route('goals.destroy', $goal->id) }}"
                                onsubmit="return confirm('Xoá bàn thắng này?')">
                                @csrf
                                @method('DELETE')
                                <button class="text-red-600 hover:underline text-sm ml-4">Xoá</button>
                            </form>
                        </li>
                    @endforeach
                </ul>
            </div>

            <div>
                <h3 class="text-xl font-medium mb-2">{{ $match->awayTeam->name }}</h3>
                <ul class="space-y-1">
                    @foreach ($awayGoals as $goal)
                        <li class="bg-gray-200 p-2 rounded flex justify-between items-center">
                            <span>
                                {{ $goal->minute }}' - {{ $goal->scorer_name }}
                                {{ $goal->is_own_goal ? '(OG)' : '' }}
                            </span>

                            <form method="POST" action="{{ route('goals.destroy', $goal->id) }}"
                                onsubmit="return confirm('Xoá bàn thắng này?')">
                                @csrf
                                @method('DELETE')
                                <button class="text-red-600 hover:underline text-sm ml-4">Xoá</button>
                            </form>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>

    </div>
</body>

</html>
