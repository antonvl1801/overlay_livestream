<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Manage Matches</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 40px;
            background-color: #f4f4f4;
        }

        h2 {
            margin-bottom: 20px;
        }

        form {
            margin-bottom: 30px;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        label {
            display: block;
            margin-top: 10px;
        }

        input,
        select {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
        }

        button {
            margin-top: 15px;
            padding: 10px 20px;
            background-color: #2196f3;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #fff;
        }

        th,
        td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: center;
        }

        th {
            background-color: #e0e0e0;
        }

        .edit-form {
            background-color: #fffae6;
        }

        td input[type="number"] {
            width: 50px;
            padding: 4px;
            box-sizing: border-box;
            text-align: center;
        }

        td input[type="number"]:focus {
            outline: none;
            border: 1px solid #007bff;
        }

        td {
            white-space: nowrap;
            vertical-align: middle;
        }

        td form {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        td select,
        td button {
            padding: 4px;
            font-size: 14px;
        }
    </style>
</head>

<body>
    <h2>Thêm Trận Đấu Mới</h2>
    <form method="POST" action="{{ route('matches.store') }}">
        @csrf
        <label>Mã trận đấu</label>
        <input type="text" name="code" required>

        <label>Đội nhà</label>
        <select name="home_team_id" required>
            @foreach ($teams as $team)
                <option value="{{ $team->id }}">{{ $team->name }}</option>
            @endforeach
        </select>

        <label>Đội khách</label>
        <select name="away_team_id" required>
            @foreach ($teams as $team)
                <option value="{{ $team->id }}">{{ $team->name }}</option>
            @endforeach
        </select>

        <label>Thời gian bắt đầu</label>
        <input type="datetime-local" name="start_time" required>

        <label>Trạng thái</label>
        <select name="status" required>
            <option value="scheduled">Scheduled</option>
            <option value="live">Live</option>
            <option value="finished">Finished</option>
        </select>

        <button type="submit">Thêm trận đấu</button>
    </form>

    <h2>Danh Sách Trận Đấu</h2>
    <table>
        <thead>
            <tr>
                <th>Mã</th>
                <th>Đội nhà</th>
                <th>Tỉ số</th>
                <th>Đội khách</th>
                <th>Thời gian</th>
                <th>Trạng thái</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($matches as $match)
                <tr>
                    <td>{{ $match->code }}</td>
                    <td>{{ $match->homeTeam->name }}</td>
                    <td>
                        <input type="number" value="{{ $match->home_score }}"
                            onchange="updateScore({{ $match->id }}, 'home', this.value)">
                        -
                        <input type="number" value="{{ $match->away_score }}"
                            onchange="updateScore({{ $match->id }}, 'away', this.value)">
                    </td>
                    <td>{{ $match->awayTeam->name }}</td>
                    <td>{{ $match->start_time }}</td>
                    <td>{{ ucfirst($match->status) }}</td>
                    <td>
                        <form method="POST" action="{{ route('matches.update', $match->id) }}"
                            style="display: flex; align-items: center; gap: 5px;">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="code" value="{{ $match->code }}">
                            <select name="status" style="padding: 4px;">
                                <option value="scheduled" @selected($match->status === 'scheduled')>Scheduled</option>
                                <option value="live" @selected($match->status === 'live')>Live</option>
                                <option value="finished" @selected($match->status === 'finished')>Finished</option>
                            </select>
                            <button type="submit" style="padding: 4px 8px;">Cập nhật</button>
                        </form>
                    </td>
                    <td>
                        <button onclick="deleteMatch({{ $match->id }})">Xoá</button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
<script>
    function deleteMatch(id) {
        if (confirm('Bạn có chắc muốn xoá trận đấu này không?')) {
            fetch(`/matches/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    },
                })
                .then(response => {
                    if (response.ok) {
                        location.reload();
                    } else {
                        alert('Xoá thất bại!');
                    }
                });
        }
    }

    function updateScore(id, team, score) {
        fetch(`/matches/${id}/update-score`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    team: team,
                    score: score
                })
            })
            .then(response => response.json())
            .then(data => {
                if (!data.success) {
                    alert('Cập nhật thất bại!');
                }
            });
    }
</script>

</html>
