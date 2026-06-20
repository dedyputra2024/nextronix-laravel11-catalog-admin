<form action="{{ $action }}" method="POST">
    @csrf
    @if($method !== 'POST') @method($method) @endif
    <div class="row g-3">
        <div class="col-md-6"><label class="form-label">Nama</label><input name="name" class="form-control" value="{{ old('name', $user->name ?? '') }}" required></div>
        <div class="col-md-6"><label class="form-label">Email</label><input name="email" type="email" class="form-control" value="{{ old('email', $user->email ?? '') }}" required></div>
        <div class="col-md-6"><label class="form-label">Password</label><input name="password" type="password" class="form-control" {{ isset($user) ? '' : 'required' }}><small class="text-muted">Kosongkan saat edit jika tidak ingin mengganti.</small></div>
        <div class="col-md-6 d-flex align-items-center"><div class="form-check mt-4"><input type="checkbox" name="is_admin" value="1" id="is_admin" class="form-check-input" @checked(old('is_admin', $user->is_admin ?? false))><label class="form-check-label" for="is_admin">Admin flag bypass</label></div></div>
        <div class="col-12"><label class="form-label">Roles</label><div class="row g-2">@foreach($roles as $role)<div class="col-md-4"><label class="border rounded p-2 d-block"><input type="checkbox" name="roles[]" value="{{ $role->name }}" @checked(in_array($role->name, old('roles', isset($user) ? $user->roles->pluck('name')->all() : [])))> {{ $role->name }}</label></div>@endforeach</div></div>
    </div>
    <div class="mt-4"><button class="btn btn-primary rounded-pill px-4">Simpan</button><a href="{{ route('admin.users.index') }}" class="btn btn-link">Batal</a></div>
</form>
@if(isset($user) && $user->id !== auth()->id())
<form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="mt-3" onsubmit="return confirm('Hapus user ini?')">@csrf @method('DELETE')<button class="btn btn-outline-danger rounded-pill px-4">Hapus User</button></form>
@endif
