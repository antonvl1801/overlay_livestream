<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Scoreboard - {{ $match->code }}</title>
  <style>
    /* CSS scoreboard như mình đã tạo trước */
    #scoreboard {
      position: fixed;
      top: 10px;
      right: 10px;
      z-index: 9999;

      display: flex;
      align-items: center;
      background-color: rgba(0, 0, 0, 0.75);
      color: white;
      padding: 10px 16px;
      border-radius: 12px;
      font-family: Arial, sans-serif;
      font-size: 18px;
      gap: 12px;
    }

    .team {
      display: flex;
      align-items: center;
      gap: 6px;
    }

    .logo {
      width: 32px;
      height: 32px;
      border-radius: 50%;
    }

    .score {
      font-weight: bold;
      font-size: 22px;
    }

    .clock {
      margin-left: 12px;
      font-size: 16px;
      min-width: 55px;
      text-align: center;
    }

    @media screen and (max-width: 600px) {
      #scoreboard {
        font-size: 14px;
        padding: 6px 10px;
        gap: 6px;
      }
      .logo {
        width: 24px;
        height: 24px;
      }
      .score {
        font-size: 18px;
      }
      .clock {
        font-size: 14px;
      }
    }
  </style>
</head>
<body>
  <div id="scoreboard">
    <div class="team team-home">
      <img src="{{ $match->homeTeam->logo ?? 'https://via.placeholder.com/32' }}" alt="Home Logo" class="logo" />
      <span class="team-name">{{ $match->homeTeam->name }}</span>
    </div>

    <div class="score">
      <span id="home-score">{{ $match->home_score }}</span>
      <span>:</span>
      <span id="away-score">{{ $match->away_score }}</span>
    </div>

    <div class="team team-away">
      <img src="{{ $match->awayTeam->logo ?? 'https://via.placeholder.com/32' }}" alt="Away Logo" class="logo" />
      <span class="team-name">{{ $match->awayTeam->name }}</span>
    </div>

    <div class="clock" id="clock">00:00</div>
  </div>

  <script>

    let startTime = new Date("{{ $match->start_time->toIso8601String() }}");
    const clockEl = document.getElementById('clock');
    const homeScoreEl = document.getElementById('home-score');
    const awayScoreEl = document.getElementById('away-score');

    function updateClock() {
      const now = new Date();
      let diff = Math.floor((now - startTime) / 1000);
      if (diff < 0) diff = 0;

      const minutes = String(Math.floor(diff / 60)).padStart(2, '0');
      const seconds = String(diff % 60).padStart(2, '0');
      clockEl.textContent = `${minutes}:${seconds}`;
    }

    async function fetchLiveData() {
      try {
        const response = await fetch("{{ route('scoreboard.apiData', ['code' => $match->code]) }}");
        if (!response.ok) throw new Error('Network error');
        const data = await response.json();

        homeScoreEl.textContent = data.home_score;
        awayScoreEl.textContent = data.away_score;
      } catch (e) {
        console.error('Failed to fetch live data', e);
      }
    }

    updateClock();
    fetchLiveData();

    setInterval(() => {
      updateClock();
      fetchLiveData();
    }, 5000);
  </script>
</body>
</html>
