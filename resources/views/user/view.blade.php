@extends('layouts.app')

@section('breadcrumb')
<div class="row mb-2">
  <div class="col-sm-6">
    <h1>{{ __('User') }}</h1>
  </div>
  <div class="col-sm-6">
    <ol class="breadcrumb float-sm-right">
      <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
      <li class="breadcrumb-item"><a href="{{ route('users.index') }}">{{ __('Users') }}</a></li>
      <li class="breadcrumb-item">{{ __('User') }}</li>
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
          <span class="card-title">{{ __('Base Info') }} -- ID: {{ $user->id ?? '--'}}</span>
          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
            </button>
          </div>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
          <div class="form-group row">
            <label for="name" class="col-sm-2 col-form-label">{{ __('Name') }}</label>
            <div class="col-sm-10">
              <span>{{ $user->name }}</span>
            </div>
          </div>
          <div class="form-group row">
            <label for="name" class="col-sm-2 col-form-label">{{ __('Phone') }}</label>
            <div class="col-sm-10">
              <span>{{ $user->phone }}</span>
            </div>
          </div>
          <div class="form-group row">
            <label for="email" class="col-sm-2 col-form-label">{{ __('Email') }}</label>
            <div class="col-sm-10">
              <span>{{ $user->email }}</span>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-sm-2 col-form-label">{{ __('Birthday') }}</label>
            <div class="col-sm-10">
              <span>{!! $user->birthday ? $user->birthday->format('d/m/Y') : '' !!}</span>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-sm-2 col-form-label">{{ __('Gender') }}</label>
            <div class="col-sm-10">
              <span>{{ $user->getGenderString() }}</span>
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