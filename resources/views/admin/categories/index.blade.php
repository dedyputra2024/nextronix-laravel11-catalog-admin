@extends('layouts.admin', ['title' => 'Admin Kategori'])

@section('admin_content')
<div class="bg-white p-4 rounded shadow-sm">
    <div class="d-flex justify-content-between align-items-center mb-4"><h3>Kategori</h3><a href="{{ route('admin.categories.create') }}" class="btn btn-primary rounded-pill">Tambah Kategori</a></div>
    <div class="table-responsive"><table class="table align-middle"><thead><tr><th>Nama</th><th>Slug</th><th>Produk</th><th>Status</th><th>Aksi</th></tr></thead><tbody>
        @foreach($categories as $category)
            <tr>
                <td>{{ $category->name }}</td><td>{{ $category->slug }}</td><td>{{ $category->products_count }}</td><td>{{ $category->is_active ? 'Aktif' : 'Nonaktif' }}</td>
                <td class="d-flex gap-2"><a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-sm btn-outline-primary">Edit</a><form action="{{ route('admin.categories.destroy', $category) }}" method="POST" onsubmit="return confirm('Hapus kategori?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger">Hapus</button></form></td>
            </tr>
        @endforeach
    </tbody></table></div>
    {{ $categories->links() }}
</div>
@endsection
