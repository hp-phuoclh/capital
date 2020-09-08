@extends('layouts.app')

@section('breadcrumb')
<div class="row mb-2">
  <div class="col-sm-6">
    <h1>{{ __('Create admin') }}</h1>
  </div>
  <div class="col-sm-6">
    <ol class="breadcrumb float-sm-right">
      <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
      <li class="breadcrumb-item"><a href="{{ route('admins.index') }}">{{ __('Admins')}}</a></li>
      <li class="breadcrumb-item">{{ __('Create user') }}</li>
    </ol>
  </div>
</div>
@endsection
@section('content')
<input type="hidden" id="active_nav" value="{{ Route::currentRouteName() }}">
<div class="row justify-content-md-center">
  <div class="col-sm-12">

    <!-- form start -->
    <form class="form-horizontal" action="{{ route('admins.store') }}" method="POST" id="create_admin_form">
      @csrf
      <div class="card">
        <div class="card-header">
          <span class="card-title">{{ __('Base Info') }}</span>
          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
            </button>
          </div>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
          <div class="form-group row">
            <label for="name" class="col-sm-2 col-form-label">{{ __('Name')}} <span class="text-danger">*</span></label>
            <div class="col-sm-10">
              <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{old('name')}}">
            </div>
            @error('name')
            <div class="offset-2 col-sm-10">
              <p class="text-danger">{{ $message }}</p>
            </div>
            @enderror
          </div>
          <div class="form-group row">
            <label for="email" class="col-sm-2 col-form-label">{{ __('Email')}} <span class="text-danger">*</span></label>
            <div class="col-sm-10">
              <input type="text" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{old('email')}}">
            </div>
            @error('email')
            <div class="offset-2 col-sm-10">
              <p class="text-danger">{{ $message }}</p>
            </div>
            @enderror
          </div>
          <div class="form-group row">
            <label for="role" class="col-sm-2 col-form-label">{{ __('Role')}} <span class="text-danger">*</span></label>
            <div class="col-sm-10">
              <select type="text" class="form-control @error('role') is-invalid @enderror" id="role" name="role">
                @foreach($roles as $role)
                <option value="{{$role->name}}" @if(old('role') == $role->name) selected @endif>{{$role->name}}</option>
                @endforeach
              </select>
            </div>
            @error('role')
            <div class="offset-2 col-sm-10">
              <p class="text-danger">{{ $message }}</p>
            </div>
            @enderror
          </div>
      </div>
        <!-- /.card-body -->
      </div>
      <div class="row">
        <div class="col-12">
          <button type="submit" class="btn btn-info float-right">{{__('Save')}}</button>
        </div>
      </div>
    </form>
  </div>
</div>
@endsection
@section('scripts')
<!-- OPTIONAL SCRIPTS -->
{{-- add --}}
<script src="/js/jquery-validation/jquery.validate.min.js"></script>
<script>
    $( "#create_admin_form" ).validate({
        "rules": {
            "name": {
                "required": true,
            },
            "email": {
                "required": true,
                "email": true,
            },
            "role": {
                "required": true,
            },
        },
        "messages": {
            "name": {
                "required": "{{__('Required')}}",
            },
            "email": {
                "required": "{{__('Required')}}",
                "email": "{{__('validation.email',['attribute'=>'Email'])}}",
            },
            "role": {
                "required": "{{__('Required')}}",
            },
        }
    });
</script>
@endsection