<form action="{{ $action }}" method="POST">
    @csrf
    @if($method !== 'POST') @method($method) @endif
    <div class="mb-3"><label class="form-label">Nama Role</label><input name="name" class="form-control" value="{{ old('name', $role->name ?? '') }}" required></div>
    <label class="form-label">Permissions</label>
    @foreach($permissions as $group => $items)
        <div class="border rounded p-3 mb-3">
            <strong class="text-capitalize">{{ $group }}</strong>
            <div class="row g-2 mt-1">
                @foreach($items as $permission)
                    <div class="col-md-4"><label class="d-block"><input type="checkbox" name="permissions[]" value="{{ $permission->name }}" @checked(in_array($permission->name, old('permissions', isset($role) ? $role->permissions->pluck('name')->all() : [])))> {{ $permission->name }}</label></div>
                @endforeach
            </div>
        </div>
    @endforeach
    <div class="mt-4"><button class="btn btn-primary rounded-pill px-4">Simpan</button><a href="{{ route('admin.roles.index') }}" class="btn btn-link">Batal</a></div>
</form>
@if(isset($role) && !in_array($role->name, ['super-admin','customer']))
<form action="{{ route('admin.roles.destroy', $role) }}" method="POST" class="mt-3" onsubmit="return confirm('Hapus role ini?')">@csrf @method('DELETE')<button class="btn btn-outline-danger rounded-pill px-4">Hapus Role</button></form>
@endif
