@extends('layouts.app')

@section('breadcrumb')
<div class="row mb-2">
  <div class="col-sm-6">
    <h1>{{ $user->id ?  __('Edit user') :  __('Create user') }}</h1>
  </div>
  <div class="col-sm-6">
    <ol class="breadcrumb float-sm-right">
      <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
      <li class="breadcrumb-item"><a href="{{ route('users.index') }}">{{ __('Users')}}</a></li>
      <li class="breadcrumb-item">{{ $user->id ?  __('Edit user') :  __('Create user') }}</li>
    </ol>
  </div>
</div>
@endsection
@section('content')
<input type="hidden" id="active_nav" value="{{ Route::currentRouteName() }}">
<div class="row justify-content-md-center">
  <div class="col-sm-12">

    <!-- form start -->
    <form class="form-horizontal" action="{{ $user->id ? route('users.update', ['user' => $user->id]) : route('users.store') }}" method="POST">
      @csrf
      @if ($user->id)
        @method('PUT')
      @endif
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
            <label for="name" class="col-sm-2 col-form-label">{{ __('Name')}} <span class="text-danger">*</span></label>
            <div class="col-sm-10">
              <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{old('name', $user->name)}}">
            </div>
            @error('name')
            <div class="offset-2 col-sm-10">
              <p class="text-danger">{{ $message }}</p>
            </div>
            @enderror
          </div>
          <div class="form-group row">
            <label for="phone" class="col-sm-2 col-form-label">{{ __('Phone')}} <span class="text-danger">*</span></label>
            <div class="col-sm-10">
              <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{old('phone', $user->phone)}}" @if ($user->id) disabled @endif>
            </div>
            @error('phone')
            <div class="offset-2 col-sm-10">
              <p class="text-danger">{{ $message }}</p>
            </div>
            @enderror
          </div>
          <div class="form-group row">
            <label for="email" class="col-sm-2 col-form-label">{{ __('Email')}} <span class="text-danger">*</span></label>
            <div class="col-sm-10">
              <input type="text" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{old('email', $user->email)}}">
            </div>
            @error('email')
            <div class="offset-2 col-sm-10">
              <p class="text-danger">{{ $message }}</p>
            </div>
            @enderror
          </div>
          <div class="form-group row">
            <label for="Password" class="col-sm-2 col-form-label">{{ __('Password')}} <span class="text-danger">*</span></label>
            <div class="col-sm-10">
              <input type="password" class="form-control @error('password') is-invalid @enderror" id="Password" name="password">
            </div>
            @error('password')
            <div class="offset-2 col-sm-10">
              <p class="text-danger">{{ $message }}</p>
            </div>
            @enderror
          </div>
          <div class="form-group row">
            <label for="password_confirmation" class="col-sm-2 col-form-label">{{ __('Confirm Password')}}</label>
            <div class="col-sm-10">
              <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
            </div>
          </div>
          <div class="form-group row">
            <label for="birthday" class="col-sm-2 col-form-label">{{ __('Birthday')}}</label>
            <div class="col-sm-10">
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                </div>
                <input type="text" class="form-control input-datepicker-bd @error('birthday') is-invalid @enderror" id="birthday"
                  name="birthday" value="{{old('birthday', $user->birthday ? $user->birthday->format('d/m/Y') : '')}}">
              </div>
            </div>
            @error('birthday')
            <div class="offset-2 col-sm-10">
              <p class="text-danger">{{ $message }}</p>
            </div>
            @enderror
          </div>
          <div class="form-group row">
            <label for="email" class="col-sm-2 col-form-label">{{ __('Gender')}}</label>
            <div class="col-sm-10">
              <div class="icheck-primary d-inline pr-4">
                <input type="radio" id="female" name="gender" value="1" @if(old('gender', $user->gender) == 1) checked @endif>
                <label for="female">{{ __('Female')}}</label>
              </div>
              <div class="icheck-primary d-inline">
                <input type="radio" id="male" name="gender" value="2"  @if(old('gender', $user->gender) == 2) checked @endif>
                <label for="male">{{ __('Male')}}</label>
              </div>
            </div>
            @error('female')
            <div class="offset-2 col-sm-10">
              <p class="text-danger">{{ $message }}</p>
            </div>
            @enderror
          </div>

          @if(Request::is('*edit'))
          <div class="form-group row">
            <label for="createdated" class="col-sm-2 col-form-label">{{ __('Created at')}}</label>
            <div class="col-sm-10">
              <input type="text" class="form-control" id="createdated" disabled
                value="{!! $user->created_at ? $user->created_at->format('d/m/Y G:i:s') : '' !!}">
            </div>
          </div>

          <div class="form-group row">
            <label for="date" class="col-sm-2 col-form-label">{{ __('Update at')}}</label>
            <div class="col-sm-10">
              <input type="text" class="form-control" id="date" disabled
                value="{!! $user->updated_at ? $user->updated_at->format('d/m/Y G:i:s') : '' !!}">
            </div>
          </div>
          @endif  
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
<script>
  $(function () {
    $('.input-datepicker-bd').datepicker({
      autoclose: true,
      format: 'dd/mm/yyyy',
      language: '{{ app()->getLocale() }}',
      defaultViewDate: { year: 1994 },
      startView: 2,
    })
  })
</script>
@endsection