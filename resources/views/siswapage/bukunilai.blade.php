@extends('layout.layout')
@section ('content')
<!-- Form to select a Mapel -->
<select id="mapelSelect">
    <option value="">Select Mapel</option>
    @foreach ($mapels as $mapel)
        <option value="{{ $mapel->nama }}">{{ $mapel->nama }}</option>
    @endforeach
</select>

<!-- Display Average Scores -->
<div>
    <h3>Average Tugas: <span id="nilaiAkhirTugas"></span></h3>
    <h3>Average UH: <span id="nilaiAkhirUH"></span></h3>
</div>

<!-- Table to display Penilaian details -->
<table id="penilaianTable">
    <thead>
        <tr>
            <th>Judul</th>
            <th>Tipe</th>
            <th>TP</th>
            <th>Nilai</th>
        </tr>
    </thead>
    <tbody>
        <!-- This will be populated dynamically by AJAX -->
    </tbody>
</table>

<!-- Add AJAX script to fetch data dynamically -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('#mapelSelect').change(function() {
            var namaMapel = $(this).val();

            if (namaMapel) {
                $.ajax({
                    url: '{{ route('fetchBukuNilai') }}',  // Use the correct route for the AJAX call
                    method: 'GET',
                    data: { namaMapel: namaMapel },
                    success: function(response) {
                        // Update the scores and table data
                        $('#nilaiAkhirTugas').text(response.nilaiAkhirTugas);
                        $('#nilaiAkhirUH').text(response.nilaiAkhirUH);

                        // Populate the table with penilaian data
                        var penilaianTableBody = $('#penilaianTable tbody');
                        penilaianTableBody.empty();  // Clear the existing table rows

                        response.penilaians.forEach(function(item) {
                            penilaianTableBody.append(`
                                <tr>
                                    <td>${item.judul}</td>
                                    <td>${item.tipe}</td>
                                    <td>${item.nomor_cp}.${item.nomor_tp}</td>
                                    <td>${item.nilai}</td>
                                </tr>
                            `);
                        });
                    }
                });
            }
        });
    });
</script>
@endsection