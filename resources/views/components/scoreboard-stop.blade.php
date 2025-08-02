@php
    use App\Enums\MatchStatus;
@endphp

<div class="fixed inset-0 z-0">
    <img src="{{ asset('img/background.webp') }}" class="w-full h-full object-cover" alt="Background" />
</div>


<!-- Overlay content -->
<div class="fixed inset-0 flex items-center justify-center z-10 text-white text-sm font-sans">
    <div class="bg-primary fill-white drop-shadow-xl/50 py-6 px-8 flex flex-col items-center justify-center">
        <p class="font-bold text-center mb-2">CUP BỔN MẠNG CỘNG ĐOÀN KASAI</p>
        <p class="text-center text-[10px] mb-1">13:00 ~ 18:00, 03/08/2025</p>
        <p class="text-center text-[10px] mb-4">Sân bóng Kasai Rinkai</p>
        <div class="flex justify-between items-center mb-4">
            <div class="flex flex-col items-center w-40">
                <img src="{{ isset($teamA->logo_path) ? asset('storage/' . $teamA->logo_path) : asset('img/logo.png') }}"
                    class="h-24 w-24 mb-2 animate-flipY" alt="Logo" />
                <span
                    class="text-lg text-base font-semibold text-white mb-1">{{ isset($teamA->name) ? $teamA->name : $teamA }}</span>
            </div>
            @if ($status == 0)
                <img src="{{ asset('img/vs.jpg') }}" class="h-24 w-24 mb-2" alt="Logo" />
            @else
                <div class="flex flex-col items-center justify-center">
                    @if ($status == MatchStatus::FINISHED->value)
                        <span class="text-xs font-bold text-white mb-2">(Kết Thúc)</span>
                    @endif
                    <div class="flex items-center justify-center">
                        <div class="text-4xl px-2 font-bold">{{ $scoreA }}</div>
                        <div class="flex justify-center items-center text-4xl px-2 font-bold">:</div>
                        <div class="text-4xl px-2 font-bold">{{ $scoreB }}</div>
                    </div>
                </div>
            @endif
            <div class="flex flex-col items-center w-40">
                <img src="{{ isset($teamB->logo_path) ? asset('storage/' . $teamB->logo_path) : asset('img/logo.png') }}"
                    class="h-24 w-24 mb-2 animate-flipY" alt="Logo" />
                <span
                    class="text-lg text-base font-semibold text-white mb-1">{{ isset($teamB->name) ? $teamB->name : $teamA }}</span>
            </div>
        </div>
        <div class="flex justify-between items-center">
            <div class="flex flex-col text-center mr-20">
                @if ($status == MatchStatus::FINISHED->value)
                    @foreach ($goals['home'] as $goal)
                        <div class="text-xs text-white w-40">
                            {{ $goal['minute'] }}' - {{ $goal['scorer_name'] }}
                            {{ $goal['is_own_goal'] ? '(OG)' : '' }}
                        </div>
                    @endforeach
                @endif

            </div>
            <div class="flex flex-col text-center">
                @if ($status == MatchStatus::FINISHED->value)
                    @foreach ($goals['away'] as $goal)
                        <div class="text-xs text-white w-40">
                            {{ $goal['minute'] }}' - {{ $goal['scorer_name'] }}
                            {{ $goal['is_own_goal'] ? '(OG)' : '' }}
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
        @if ($status == MatchStatus::SCHEDULED->value)
            <div class="mt-2 justify-center items-center flex">
                <div class="flex items-center justify-center w-fit h-8 px-2 ">
                    <img src="{{ asset('img/logo.png') }}" class="w-6 h-6 mr-2 object-cover" alt="Background" />
                    <p class="text-center text-[10px] flex justify-center items-center font-medium  text-white">
                        LIVESTREAM TRỰC TIẾP: KASAI MEDIA
                    </p>
                </div>
            </div>
        @endif
    </div>
</div>
