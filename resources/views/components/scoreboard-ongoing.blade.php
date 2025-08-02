<!-- Background -->
<div class="fixed inset-0 z-0">
    <img src="{{ asset('img/background.webp') }}" class="w-full h-full object-cover" alt="Background" />
</div>
<div class="fixed top-4 left-4 flex items-center justify-center font-semibold text-[10px] text-white">
    <img src="{{ asset('img/logo.png') }}" class="w-8 h-8 mr-2 object-cover" alt="Background" />
    <div>CUP BỔN MẠNG <br>CỘNG ĐOÀN KASAI</div>
</div>
<div class="fixed top-4 right-4 z-50 bg-amber">
    <div class="flex items-center rounded text-white shadow text-[10px] font-sans h-6">
        <div class="bg-green-500 h-full w-1"></div>
        <div class="flex flex-col justify-center bg-primary h-full items-center w-28">
            {{ $teamA->name ?? $teamA }}
        </div>
        <div class="flex bg-teal-200 h-full">
            <div class="flex items-center justify-center text-primary w-6 h-full font-bold text-sm">
                {{ $scoreA }}
            </div>
            <div class="text-emerald-950 font-bold flex items-center h-full">-</div>
            <div class="flex items-center justify-center text-primary w-6 h-full font-bold text-sm">
                {{ $scoreB }}
            </div>
        </div>
        <div class="flex flex-col justify-center items-center bg-primary h-full w-28">
            {{ $teamB->name ?? $teamB }}
        </div>
        <div class="bg-violet-500 h-full w-1"></div>
    </div>
    <div class="w-full flex items-center justify-center text-primary text-xs font-bold">
        <div id="timer" class="h-6 w-16 bg-amber-300 flex items-center justify-center">
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
