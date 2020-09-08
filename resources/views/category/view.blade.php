@extends('layouts.app')

@section('breadcrumb')
<div class="row mb-2">
  <div class="col-sm-6">
    <h1> Users</h1>
  </div>
  <div class="col-sm-6">
    <ol class="breadcrumb float-sm-right">
      <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
      <li class="breadcrumb-item"><a href="{{ route('users.index') }}">Users</a></li>
      <li class="breadcrumb-item">Users View</li>
    </ol>
  </div>
</div>
@endsection
@section('content')
<input type="hidden" id="active_nav" value="{{ Route::currentRouteName() }}">
<div class="row justify-content-md-center">
  <div class="col-sm-12">
      <div class="card">
        <div class="card-header">
          <span class="card-title">ID: {{ $user->id ?? '--'}}</span>
          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
            </button>
          </div>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
          <div class="form-group row">
            <label for="name" class="col-sm-2 col-form-label">Name</label>
            <div class="col-sm-10">
              <span>{{ $user->name }}</span>
            </div>
          </div>
          <div class="form-group row">
            <label for="createdated" class="col-sm-2 col-form-label">{{ __('Created at')}}</label>
            <div class="col-sm-10">
              <span>{{ $user->created_at->format('d/m/Y G:i:s') }}</span>
            </div>
          </div>
          <div class="form-group row">
            <label for="date" class="col-sm-2 col-form-label">{{ __('Update at')}}</label>
            <div class="col-sm-10">
              <span>{{ $user->updated_at->format('d/m/Y G:i:s') }}</span>
            </div>
          </div>
        </div>
        <!-- /.card-body -->
      </div>
  </div>
</div>
@endsection