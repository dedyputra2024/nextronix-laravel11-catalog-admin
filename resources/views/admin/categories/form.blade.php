<form action="{{ $action }}" method="POST">
    @csrf
    @if($method !== 'POST') @method($method) @endif
    <div class="mb-3"><label class="form-label">Nama</label><input name="name" class="form-control" value="{{ old('name', $category->name ?? '') }}" required></div>
    <div class="mb-3"><label class="form-label">Slug</label><input name="slug" class="form-control" value="{{ old('slug', $category->slug ?? '') }}" placeholder="Kosongkan untuk otomatis"></div>
    <div class="mb-3"><label class="form-label">Deskripsi</label><textarea name="description" class="form-control" rows="3">{{ old('description', $category->description ?? '') }}</textarea></div>
    <div class="mb-3"><label class="form-label">Path Image</label><input name="image" class="form-control" value="{{ old('image', $category->image ?? '') }}" placeholder="img/product-banner.jpg"></div>
    <div class="form-check mb-4"><input type="checkbox" name="is_active" value="1" class="form-check-input" id="is_active" @checked(old('is_active', $category->is_active ?? true))><label for="is_active" class="form-check-label">Aktif</label></div>
    <button class="btn btn-primary rounded-pill px-4">Simpan</button>
    <a href="{{ route('admin.categories.index') }}" class="btn btn-link">Batal</a>
</form>
