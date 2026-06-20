@extends('layouts.admin', ['title' => 'Tambah Produk'])

@section('admin_content')
<div class="bg-white p-4 rounded shadow-sm"><h3 class="mb-4">Tambah Produk</h3>@include('admin.products.form', ['product' => null, 'action' => route('admin.products.store'), 'method' => 'POST'])</div>
@endsection
