<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Scoreboard - Match {{ $match->id }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #222;
            color: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .scoreboard {
            background: #333;
            padding: 20px 40px;
            border-radius: 10px;
            text-align: center;
            width: 400px;
        }
        .teams {
            display: flex;
            justify-content: space-between;
            font-size: 24px;
            margin-bottom: 10px;
        }
        .score {
            font-size: 48px;
            font-weight: bold;
            margin-bottom: 20px;
        }
        .goals-list {
            text-align: left;
            max-height: 150px;
            overflow-y: auto;
            font-size: 14px;
            background: #111;
            padding: 10px;
            border-radius: 5px;
        }
        .goal-item {
            border-bottom: 1px solid #444;
            padding: 4px 0;
        }
        .own-goal {
            color: #f66;
            font-style: italic;
        }
    </style>
</head>
<body>
    <div class="scoreboard">
        <div class="teams">
            <div id="home-team">{{ $match->homeTeam->name }}</div>
            <div id="away-team">{{ $match->awayTeam->name }}</div>
        </div>
        <div class="score" id="score">{{ $match->home_score }} - {{ $match->away_score }}</div>

        <div class="goals-list" id="goals-list">
            @foreach ($match->goals as $goal)
                <div class="goal-item {{ $goal->is_own_goal ? 'own-goal' : '' }}">
                    {{ $goal->minute }}' - {{ $goal->scorer_name }} ({{ $goal->team->name }})
                    @if($goal->is_own_goal) (Own Goal) @endif
                </div>
            @endforeach
        </div>
    </div>

<script>
    const matchId = {{ $match->id }};
    const goalsListEl = document.getElementById('goals-list');
    const scoreEl = document.getElementById('score');

    async function fetchScoreData() {
        try {
            const res = await fetch(`/scoreboard/${matchId}/data`);
            if (!res.ok) throw new Error('Network error');
            const data = await res.json();

            scoreEl.textContent = `${data.home_score} - ${data.away_score}`;

            // Cập nhật danh sách bàn thắng
            goalsListEl.innerHTML = '';
            data.goals.forEach(goal => {
                const div = document.createElement('div');
                div.className = 'goal-item' + (goal.own_goal ? ' own-goal' : '');
                div.textContent = `${goal.minute}' - ${goal.scorer} (${goal.team})` + (goal.own_goal ? ' (Own Goal)' : '');
                goalsListEl.appendChild(div);
            });
        } catch(e) {
            console.error('Fetch error', e);
        }
    }

    // Cứ 10 giây lấy dữ liệu mới 1 lần
    setInterval(fetchScoreData, 10000);
</script>
</body>
</html>
