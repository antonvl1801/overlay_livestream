<div class="fixed top-4 left-4 flex items-center justify-center font-semibold text-sm text-white">
    <img src="{{ asset('img/logo.png') }}" class="w-10 h-10 mr-2 object-cover" alt="Background" />
    <div>CUP BỔN MẠNG <br>CỘNG ĐOÀN KASAI</div>
</div>
<div class="fixed top-4 right-4 z-50 bg-amber">
    <div class="flex items-center rounded text-white shadow text-sm font-sans h-8">
        <div class="bg-{{ $colorA ?? 'green' }}-500 h-full w-1"></div>
        <div class="flex flex-col justify-center bg-primary h-full items-center w-32">
            {{ $teamA->name ?? $teamA }}
        </div>
        <div class="flex bg-teal-200 h-full">
            <div class="flex items-center justify-center text-primary w-6 h-full font-bold text-base">
                {{ $scoreA }}
            </div>
            <div class="text-emerald-950 font-bold flex items-center h-full">-</div>
            <div class="flex items-center justify-center text-primary w-6 h-full font-bold text-base">
                {{ $scoreB }}
            </div>
        </div>
        <div class="flex flex-col justify-center items-center bg-primary h-full w-32">
            {{ $teamB->name ?? $teamB }}
        </div>
        <div class="bg-{{ $colorB ?? 'violet' }}-500 h-full w-1"></div>
    </div>
    <div class="w-full flex items-center justify-center text-primary text-sm font-bold">
        <div id="timer" class="h-8 w-16 bg-amber-300 flex items-center justify-center">
            00:00
        </div>
    </div>
</div>
<script>
    document.addEventListener("DOMContentLoaded", () => {
        console.log("Scoreboard script loaded");

        let seconds = {{ $seconds }};
        const timer = document.getElementById("timer");

        function updateTime() {
            if (timer) {
                const mins = String(Math.floor(seconds / 60)).padStart(2, '0');
                const secs = String(seconds % 60).padStart(2, '0');
                timer.textContent = `${mins}:${secs}`;
                seconds++;
            }
        }

        updateTime();
        setInterval(updateTime, 1000);
    });
</script>
