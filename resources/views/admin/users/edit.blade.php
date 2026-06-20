@extends('layouts.admin', ['title' => 'Edit User'])
@section('admin_content')
<div class="bg-white rounded shadow-sm p-4"><h3>Edit User</h3>@include('admin.users.form', ['action' => route('admin.users.update', $user), 'method' => 'PUT'])</div>
@endsection
