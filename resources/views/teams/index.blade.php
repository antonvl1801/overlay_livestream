<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>Teams Management</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 2rem;
            background: #f9f9f9;
        }

        h1 {
            margin-bottom: 1rem;
        }

        .success {
            color: green;
            margin-bottom: 1rem;
        }

        .error {
            color: red;
            margin-bottom: 1rem;
        }

        form {
            background: white;
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 2rem;
            width: 400px;
        }

        label {
            display: block;
            margin-top: 1rem;
        }

        input[type=text],
        input[type=file] {
            width: 100%;
            padding: 0.5rem;
            margin-top: 0.3rem;
            box-sizing: border-box;
        }

        button {
            margin-top: 1rem;
            padding: 0.5rem 1rem;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
        }

        th,
        td {
            padding: 10px;
            border: 1px solid #ccc;
            text-align: left;
        }

        img {
            max-height: 50px;
        }

        .edit-btn {
            cursor: pointer;
            color: blue;
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <h1>Teams Management</h1>

    @if (session('success'))
        <div class="success">{{ session('success') }}</div>
    @endif

    @if ($errors->any())
        <div class="error">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>- {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Form tạo/sửa team -->
    <form id="team-form" method="POST" action="{{ route('teams.store') }}" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="_method" id="form-method" value="POST" />
        <input type="hidden" name="team_id" id="team-id" />

        <label for="name">Team Name *</label>
        <input type="text" id="name" name="name" required />

        <label for="code">Code</label>
        <input type="text" id="code" name="code" />

        <label for="logo">Logo</label>
        <input type="file" id="logo" name="logo" accept="image/*" />
        <div id="current-logo" style="margin-top:10px;"></div>

        <button type="submit" id="submit-btn">Add Team</button>
        <button type="button" id="cancel-btn" style="display:none; margin-left:10px;">Cancel</button>
    </form>

    <!-- Bảng danh sách teams -->
    <table>
        <thead>
            <tr>
                <th>Logo</th>
                <th>Name</th>
                <th>Code</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($teams as $team)
                <tr data-id="{{ $team->id }}" data-name="{{ $team->name }}"
                    data-code="{{ $team->code }}"
                    data-logo="{{ $team->logo_path ? asset('storage/' . $team->logo_path) : '' }}">
                    <td>
                        @if ($team->logo_path)
                            <img src="{{ asset('storage/' . $team->logo_path) }}" alt="Logo" />
                        @else
                            No logo
                        @endif
                    </td>
                    <td>{{ $team->name }}</td>
                    <td>{{ $team->code }}</td>
                    <td>
                        <span class="edit-btn">Edit</span> |
                        <form method="POST" action="{{ route('teams.destroy', $team->id) }}" style="display:inline;"
                            onsubmit="return confirm('Are you sure you want to delete this team?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                style="color: red; background: none; border: none; cursor: pointer;">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <script>
        const form = document.getElementById('team-form');
        const formMethod = document.getElementById('form-method');
        const teamIdInput = document.getElementById('team-id');
        const nameInput = document.getElementById('name');
        const codeInput = document.getElementById('code');
        const logoInput = document.getElementById('logo');
        const currentLogoDiv = document.getElementById('current-logo');
        const submitBtn = document.getElementById('submit-btn');
        const cancelBtn = document.getElementById('cancel-btn');

        // Chuyển sang mode sửa
        function editTeam(team) {
            form.action = `/teams/${team.id}`;
            formMethod.value = 'PUT';
            teamIdInput.value = team.id;
            nameInput.value = team.name;
            codeInput.value = team.code;
            logoInput.value = '';
            if (team.logo) {
                currentLogoDiv.innerHTML = `<img src="${team.logo}" alt="Current Logo" style="max-height:80px;">`;
            } else {
                currentLogoDiv.innerHTML = 'No current logo';
            }
            submitBtn.textContent = 'Update Team';
            cancelBtn.style.display = 'inline-block';
        }

        // Reset form về mode tạo mới
        function resetForm() {
            form.action = "{{ route('teams.store') }}";
            formMethod.value = 'POST';
            teamIdInput.value = '';
            nameInput.value = '';
            codeInput.value = '';
            logoInput.value = '';
            currentLogoDiv.innerHTML = '';
            submitBtn.textContent = 'Add Team';
            cancelBtn.style.display = 'none';
        }

        // Bắt sự kiện click nút edit
        document.querySelectorAll('.edit-btn').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const tr = e.target.closest('tr');
                const team = {
                    id: tr.getAttribute('data-id'),
                    name: tr.getAttribute('data-name'),
                    code: tr.getAttribute('data-code'),
                    logo: tr.getAttribute('data-logo'),
                };
                editTeam(team);
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            });
        });

        cancelBtn.addEventListener('click', () => {
            resetForm();
        });
    </script>

</body>

</html>
