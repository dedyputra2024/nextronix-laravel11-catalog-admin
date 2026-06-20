<form action="{{ $action }}" method="POST" enctype="multipart/form-data">
    @csrf
    @if($method !== 'POST') @method($method) @endif
    <div class="row g-3">
        <div class="col-md-6"><label class="form-label">Kategori</label><select name="category_id" class="form-select" required>@foreach($categories as $category)<option value="{{ $category->id }}" @selected(old('category_id', $product->category_id ?? '') == $category->id)>{{ $category->name }}</option>@endforeach</select></div>
        <div class="col-md-6"><label class="form-label">Nama Produk</label><input name="name" class="form-control" value="{{ old('name', $product->name ?? '') }}" required></div>
        <div class="col-md-6"><label class="form-label">Slug</label><input name="slug" class="form-control" value="{{ old('slug', $product->slug ?? '') }}" placeholder="Kosongkan untuk otomatis"></div>
        <div class="col-md-6"><label class="form-label">SKU</label><input name="sku" class="form-control" value="{{ old('sku', $product->sku ?? '') }}"></div>
        <div class="col-md-6"><label class="form-label">Harga</label><input type="number" name="price" class="form-control" value="{{ old('price', $product->price ?? '') }}" required></div>
        <div class="col-md-6"><label class="form-label">Harga Diskon</label><input type="number" name="sale_price" class="form-control" value="{{ old('sale_price', $product->sale_price ?? '') }}"></div>
        <div class="col-md-6"><label class="form-label">Stok</label><input type="number" name="stock" class="form-control" value="{{ old('stock', $product->stock ?? 0) }}" required></div>
        <div class="col-md-6"><label class="form-label">Berat (gram)</label><input type="number" name="weight_gram" class="form-control" value="{{ old('weight_gram', $product->weight_gram ?? 1000) }}" required></div>
        <div class="col-md-4"><label class="form-label">Panjang (cm)</label><input type="number" name="length_cm" class="form-control" value="{{ old('length_cm', $product->length_cm ?? '') }}"></div>
        <div class="col-md-4"><label class="form-label">Lebar (cm)</label><input type="number" name="width_cm" class="form-control" value="{{ old('width_cm', $product->width_cm ?? '') }}"></div>
        <div class="col-md-4"><label class="form-label">Tinggi (cm)</label><input type="number" name="height_cm" class="form-control" value="{{ old('height_cm', $product->height_cm ?? '') }}"></div>
        <div class="col-md-6"><label class="form-label">Path Image</label><input name="image" class="form-control" value="{{ old('image', $product->image ?? '') }}" placeholder="img/product-1.png"></div>
        <div class="col-md-6"><label class="form-label">Upload Image Baru</label><input type="file" name="image_file" class="form-control" accept="image/*"></div>
        <div class="col-12"><label class="form-label">Deskripsi</label><textarea name="description" class="form-control" rows="4">{{ old('description', $product->description ?? '') }}</textarea></div>
        <div class="col-12 d-flex gap-4">
            <div class="form-check"><input type="checkbox" name="is_featured" value="1" class="form-check-input" id="is_featured" @checked(old('is_featured', $product->is_featured ?? false))><label for="is_featured" class="form-check-label">Produk Unggulan</label></div>
            <div class="form-check"><input type="checkbox" name="is_active" value="1" class="form-check-input" id="is_active" @checked(old('is_active', $product->is_active ?? true))><label for="is_active" class="form-check-label">Aktif</label></div>
        </div>
    </div>
    <div class="mt-4"><button class="btn btn-primary rounded-pill px-4">Simpan</button><a href="{{ route('admin.products.index') }}" class="btn btn-link">Batal</a></div>
</form>
