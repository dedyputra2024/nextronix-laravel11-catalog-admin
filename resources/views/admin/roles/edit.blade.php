@extends('layouts.admin', ['title' => 'Edit Role'])
@section('admin_content')
<div class="bg-white rounded shadow-sm p-4"><h3>Edit Role</h3>@include('admin.roles.form', ['action' => route('admin.roles.update', $role), 'method' => 'PUT'])</div>
@endsection
