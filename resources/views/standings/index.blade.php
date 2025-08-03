<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Bảng xếp hạng</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/app.css')
</head>

<body>
    <div class="fixed inset-0 flex items-center justify-center z-10 text-white text-sm font-sans">
        <div class="bg-primary fill-white drop-shadow-xl/50 py-6 px-8 flex flex-col items-center justify-center">
            <p class="font-bold text-center text-sm mb-2">CUP BỔN MẠNG CỘNG ĐOÀN KASAI</p>
            <div class="max-w-4xl w-full px-4 text-primary">
                <h1 class="text-xl font-bold text-center text-white">BẢNG XẾP HẠNG</h1>
                <p class="text-center text-xs text-white mb-2">(tính đến hiện tại)</p>
                <table class="min-w-full bg-white border border-gray-300 rounded shadow text-sm">
                    <thead class="bg-teal-200">
                        <tr>
                            <th class="py-2 px-4 border">STT</th>
                            <th class="py-2 px-4 border">Đội</th>
                            <th class="py-2 px-4 border">Điểm</th>
                            <th class="py-2 px-4 border">Hiệu số</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($sortedStandings as $index => $team)
                            <tr class="text-center">
                                <td class="py-2 px-4 border">{{ $index + 1 }}</td>
                                <td class="py-2 px-4 border text-left min-w-20">
                                    <div class="flex items-center space-x-2">
                                        <img src="{{ asset('storage/' . $team['logo']) }}"
                                            alt="Logo {{ $team['team_name'] }}" class="h-8 w-8 object-cover">
                                        <span class="font-bold">{{ $team['team_name'] }}</span>
                                    </div>
                                </td>
                                <td class="py-2 px-4 border">{{ $team['points'] }}</td>
                                <td class="py-2 px-4 border">{{ $team['goal_difference'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-2 justify-center items-center flex">
                <div class="flex items-center justify-center w-fit h-8 px-2 ">
                    <img src="{{ asset('img/logo.png') }}" class="w-6 h-6 mr-2 object-cover" alt="Background" />
                    <p class="text-center text-[10px] flex justify-center items-center font-medium  text-white">
                        LIVESTREAM TRỰC TIẾP: KASAI MEDIA
                    </p>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
