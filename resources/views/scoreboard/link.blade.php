<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Scoreboard - {{ $teamA }} vs {{ $teamB }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/app.css')
</head>

<body>
    @if ($status == 1)
        <x-scoreboard-ongoing :teamA="$teamA" :teamB="$teamB" :scoreA="$scoreA" :scoreB="$scoreB" :seconds="$seconds"
            :colorA="$colorA" :colorB="$colorB" />
    @else
        <x-scoreboard-stop :teamA="$teamA" :teamB="$teamB" :scoreA="$scoreA" :scoreB="$scoreB" :colorA="$colorA"
            :status="$status" :colorB="$colorB" :goals="$goals" />
    @endif
</body>

</html>
