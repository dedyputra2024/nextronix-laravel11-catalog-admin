@extends('layouts.admin', ['title' => 'Roles'])
@section('admin_content')
<div class="bg-white rounded shadow-sm p-4">
    <div class="d-flex justify-content-between align-items-center mb-3"><h3 class="mb-0">Roles & Permissions</h3><a href="{{ route('admin.roles.create') }}" class="btn btn-primary rounded-pill">Tambah Role</a></div>
    <div class="table-responsive">
        <table class="table align-middle"><thead><tr><th>Role</th><th>Jumlah Permission</th><th></th></tr></thead><tbody>@foreach($roles as $role)<tr><td>{{ $role->name }}</td><td>{{ $role->permissions_count }}</td><td class="text-end"><a href="{{ route('admin.roles.edit', $role) }}" class="btn btn-sm btn-outline-primary">Edit</a></td></tr>@endforeach</tbody></table>
    </div>{{ $roles->links() }}
</div>
@endsection
