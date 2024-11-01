@extends('layout.layout')

@section('content')
    <div class="container">
        <h1>Edit Account</h1>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('account.update', $account->id) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Name Input -->
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $account->name) }}" required>
            </div>

            <!-- Username Input -->
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" name="username" id="username" class="form-control" value="{{ old('username', $account->username) }}" required>
            </div>

            <!-- Password Input -->
            <div class="form-group">
                <label for="password">Password</label>
                <input type="text" name="password" id="password" class="form-control" value="{{ old('password', $account->password) }}" required>
            </div>

            <!-- Role Selection Dropdown -->
            <div class="form-group">
                <label for="role">Role</label>
                <select name="role" id="role" class="form-control" required>
                    @foreach($roles as $role)
                        <option value="{{ $role->name }}" {{ $account->hasRole($role->name) ? 'selected' : '' }}>
                            {{ $role->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Update</button>
            <a href="{{ route('account.index') }}" class="btn btn-secondary">Back to List</a>
        </form>
    </div>
@endsection
