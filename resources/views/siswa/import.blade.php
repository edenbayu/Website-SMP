@extends('layout/layout')
@section('content')
<!DOCTYPE html>
<html>

<head>
    <title>Laravel 11 Import Export Excel to Database Example - ItSolutionStuff.com</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
</head>

<body>

    <div class="container">
        <div class="card mt-5">
            <h3 class="card-header p-3"><i class="fa fa-star"></i> Import Data PPDB</h3>
            <div class="card-body">

                @if ($errors->any())
                <div class="alert alert-danger">
                    <strong>Whoops!</strong> Tolong periksa kembali file excel anda!<br><br>
                    <!-- <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul> -->
                </div>
                @endif

                <form action="{{ route('siswa.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <input type="file" name="file" class="form-control">

                    <br>
                    <button class="btn btn-success"><i class="fa fa-file"></i> Import Data</button>
                </form>

            </div>
        </div>
    </div>

</body>

</html>
@endsection