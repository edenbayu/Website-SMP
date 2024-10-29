@extends('layout/layout')
@section('content')
    @if(session('success'))
        @hasanyrole('Admin')
            <p>I am Admin</p>
        @endhasanyrole
        @role('Guru')
            <p>I am Guru</p>
        @endrole

        @role('Wali Kelas')
            <p>I am Wali Kelas</p>
        @endrole

        @role('Siswa')
            <p>I am Siswa</p>
        @endrole

    @endif

    <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button type="submit">Logout</button>
    </form>
@endsection
