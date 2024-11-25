<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rapot - {{ $studentName }}</title>
</head>
<body>
    <h1>Rapot Mid Semester - {{ $studentName }}</h1>

    <table>
        <thead>
            <tr>
                <th>Subject</th>
                <th>Grade</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($subjects as $subject => $grade)
                <tr>
                    <td>{{ $subject }}</td>
                    <td>{{ $grade }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Add Komentar to the PDF -->
    <h3>Komentar:</h3>
    <p>{{ $komentar }}</p>

    <!-- Add Prestasi to the PDF -->
    <h3>Prestasi:</h3>
    <ul>
        @foreach ($prestasi as $key => $prestasiItem)
            @if($prestasiItem)
                <li>{{ ucfirst(str_replace('_', ' ', $key)) }}: {{ $prestasiItem }}</li>
            @endif
        @endforeach
    </ul>

    <footer>
        <p>Generated on: {{ now() }}</p>
    </footer>
</body>
</html>
