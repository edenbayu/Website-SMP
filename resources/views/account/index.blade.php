@extends('layout.layout')
@section('content')
    <table>
        <thead>
            <tr>
                <th>Id</th>
                <th>Nama</th>
                <th>Username</th>
                <th>Passowrd</th>
                <th>Role</th>
            </tr>
        </thead>
        <tbody>
            @foreach($accounts as $account)
            <tr>
                <td>{{$account->id}}</td>
                <td>{{$account->name}}</td>
                <td>{{$account->username}}</td>
                <td>{{$account->password}}</td>
                <td>{{ $account->getRoleNames()->implode(', ') }}</td>
                <td>
                <a href="{{ route('account.edit', $account->id) }}" class="btn btn-primary">Edit</a>
                
                <form action="{{ route('account.destroy', $account->id) }}" method="POST" style="display:inline;">
                    @csrf
                    <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                </form>
            </td>
            </tr>
            @endforeach
        </tbody>
    </table>
@endsection