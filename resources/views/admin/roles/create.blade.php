@extends('layouts.admin', ['title' => 'Tambah Role'])
@section('admin_content')
<div class="bg-white rounded shadow-sm p-4"><h3>Tambah Role</h3>@include('admin.roles.form', ['action' => route('admin.roles.store'), 'method' => 'POST', 'role' => null])</div>
@endsection
