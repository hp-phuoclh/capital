@extends('layouts.app')

@section('breadcrumb')
<div class="row mb-2">
  <div class="col-sm-6">
    <h1>{{ __('Create role') }}</h1>
  </div>
  <div class="col-sm-6">
    <ol class="breadcrumb float-sm-right">
      <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
      <li class="breadcrumb-item"><a href="{{ route('roles.index') }}">{{ __('Roles')}}</a></li>
      <li class="breadcrumb-item">{{ __('Create role') }}</li>
    </ol>
  </div>
</div>
@endsection
@section('content')
<input type="hidden" id="active_nav" value="{{ Route::currentRouteName() }}">
<div class="row justify-content-md-center">
  <div class="col-sm-12">

    <!-- form start -->
    <form class="form-horizontal" action="{{ route('roles.store') }}" method="POST" id="create_role_form">
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
            <label for="permissions" class="col-sm-2 col-form-label">{{ __('Permissions')}}</label>
            <div class="col-sm-10">
              <select class="form-control permission-multiple" name="permissions[]" multiple="multiple" id="permissions">
                  @foreach($permissions as $permission)
                      <option value="{{$permission->name}}">{{$permission->name}}</option>
                  @endforeach
              </select>
            </div>
            @error('permissions')
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
{{-- import lib --}}
<link rel="stylesheet" href="/css/select2/select2.min.css">
<link rel="stylesheet" href="/css/select2/select2-bootstrap4.min.css">
<script src="/js/select2/select2.full.min.js"></script>
<script src="/js/jquery-validation/jquery.validate.min.js"></script>
<script>
  // validate form
    $( "#create_role_form" ).validate({
        "rules": {
            "name": {
                "required": true,
            }
        },
        "messages": {
            "name": {
                "required": "{{__('Required')}}",
            }
        }
    });

    // select 2 permission
    $(document).ready(function() {
        $('.permission-multiple').select2({
            theme: 'bootstrap4',
            width: '100%',
        });
    });
</script>
@endsection