@extends('layouts.admin', ['title' => 'Tambah User'])
@section('admin_content')
<div class="bg-white rounded shadow-sm p-4"><h3>Tambah User</h3>@include('admin.users.form', ['action' => route('admin.users.store'), 'method' => 'POST', 'user' => null])</div>
@endsection
