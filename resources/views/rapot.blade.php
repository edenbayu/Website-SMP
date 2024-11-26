<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rapot - {{ $studentName }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        h1, h3 {
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        table, th, td {
            border: 1px solid #000;
        }
        th, td {
            text-align: left;
            padding: 8px;
        }
        th {
            background-color: #f4f4f4;
        }
        footer {
            text-align: center;
            margin-top: 30px;
            font-size: 0.9em;
        }
    </style>
</head>
<body>
    <h1>Rapot Mid Semester</h1>
    <h2>{{ $studentName }}</h2>

    <!-- Main Table: Subjects, Grades, Comments -->
    <h3>Subjects, Grades, and Comments</h3>
    @if (!empty($subjects))
        <table>
            <thead>
                <tr>
                    <th>Subject</th>
                    <th>Grade</th>
                    <th>Comments</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($subjects as $subject => $grade)
                    <tr>
                        <td>{{ $subject }}</td>
                        <td>{{ $grade }}</td>
                        <td>
                            Ananda {{$studentName}} telah menguasai
                            @if (!empty($komentarRapot[$subject]))
                                {{ implode(', ', $komentarRapot[$subject]) }}
                            @else
                                <span></span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>No subjects available.</p>
    @endif

    <!-- Komentar Section -->
    <h3>General Comment</h3>
    <p>{{ $komentar ?: 'No general comment available.' }}</p>

    <!-- Prestasi Section -->
    <h3>Achievements (Prestasi)</h3>
    <ul>
        @foreach ($prestasi as $key => $prestasiItem)
            @if ($prestasiItem)
                <li>{{ ucfirst(str_replace('_', ' ', $key)) }}: {{ $prestasiItem }}</li>
            @endif
        @endforeach
    </ul>

    <!-- Extracurricular Data Section -->
    <h3>Extracurricular Activities (Ekstrakurikuler)</h3>
    @if ($ekskulData->isNotEmpty())
        <table>
            <thead>
                <tr>
                    <th>Class</th>
                    <th>Activity</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($ekskulData as $ekskul)
                    <tr>
                        <td>{{ $ekskul->rombongan_belajar }}</td>
                        <td>{{ $ekskul->nilai }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>No extracurricular data available.</p>
    @endif

    <!-- Attendance Summary Section -->
    <h3>Attendance Summary</h3>
    @if ($absensiSummary->isNotEmpty())
        <table>
            <thead>
                <tr>
                    <th>Status</th>
                    <th>Count</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($absensiSummary as $record)
                    <tr>
                        <td>{{ ucfirst($record->status) }}</td>
                        <td>{{ $record->count }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>No attendance data available.</p>
    @endif

    <!-- Footer -->
    <footer>
        <p>Generated on: {{ now()->format('d M Y, H:i') }}</p>
    </footer>
</body>
</html>
