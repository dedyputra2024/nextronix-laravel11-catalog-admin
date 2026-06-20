@extends('layouts.admin', ['title' => 'Tambah Kategori'])

@section('admin_content')
<div class="bg-white p-4 rounded shadow-sm"><h3 class="mb-4">Tambah Kategori</h3>@include('admin.categories.form', ['category' => null, 'action' => route('admin.categories.store'), 'method' => 'POST'])</div>
@endsection
