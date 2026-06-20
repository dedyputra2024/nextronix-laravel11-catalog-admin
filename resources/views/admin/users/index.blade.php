@extends('layouts.admin', ['title' => 'Users'])

@section('admin_content')
<div class="bg-white rounded shadow-sm p-4">
    <div class="d-flex justify-content-between align-items-center mb-3"><h3 class="mb-0">Users</h3><a href="{{ route('admin.users.create') }}" class="btn btn-primary rounded-pill">Tambah User</a></div>
    <div class="table-responsive">
        <table class="table align-middle">
            <thead><tr><th>Nama</th><th>Email</th><th>Roles</th><th>Admin Flag</th><th></th></tr></thead>
            <tbody>
                @foreach($users as $user)
                    <tr>
                        <td>{{ $user->name }}</td><td>{{ $user->email }}</td>
                        <td>@foreach($user->roles as $role)<span class="badge bg-primary me-1">{{ $role->name }}</span>@endforeach</td>
                        <td>{{ $user->is_admin ? 'Yes' : 'No' }}</td>
                        <td class="text-end"><a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-outline-primary">Edit</a></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    {{ $users->links() }}
</div>
@endsection
