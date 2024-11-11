@extends('layout.layout')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.bootstrap5.css">
@section('content')

<div class="container-fluid mt-3">
    <div class="card mb-3 border-0 shadow-sm" style="background-color:#f2f2f2;">
        <div class="card-body">
            <h2 class="m-0">Daftar Akun</h2>
        </div>
    </div>

    <table id="example" class="table table-striped" style="width:100%">
        <thead>
            <tr>
                <th class="text-start">ID</th>
                <th>Nama</th>
                <th>Username</th>
                <th>Passowrd</th>
                <th>Role</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($accounts as $account)
            <tr>
                <td class="text-start">{{$account->id}}</td>
                <td>{{$account->name}}</td>
                <td>{{$account->username}}</td>
                <td>{{$account->password}}</td>
                <td>{{ $account->getRoleNames()->implode(', ') }}</td>
                <td>
                    <a href="{{ route('account.edit', $account->id) }}" class="btn btn-warning px-4">Edit</a>

                    <form action="{{ route('account.destroy', $account->id) }}" method="POST" style="display:inline;">
                        @csrf
                        <button type="submit" class="btn btn-danger px-3" onclick="return confirm('Are you sure?')">Hapus</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.1.8/js/dataTables.bootstrap5.js"></script>
    <script>
        new DataTable('#example');
    </script>
</div>
@endsection