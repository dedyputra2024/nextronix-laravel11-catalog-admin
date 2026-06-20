@extends('layouts.admin', ['title' => 'Edit Kategori'])

@section('admin_content')
<div class="bg-white p-4 rounded shadow-sm"><h3 class="mb-4">Edit Kategori</h3>@include('admin.categories.form', ['category' => $category, 'action' => route('admin.categories.update', $category), 'method' => 'PUT'])</div>
@endsection
