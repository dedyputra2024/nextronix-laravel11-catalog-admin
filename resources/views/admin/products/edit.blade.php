@extends('layouts.admin', ['title' => 'Edit Produk'])

@section('admin_content')
<div class="bg-white p-4 rounded shadow-sm"><h3 class="mb-4">Edit Produk</h3>@include('admin.products.form', ['product' => $product, 'action' => route('admin.products.update', $product), 'method' => 'PUT'])</div>
@endsection
