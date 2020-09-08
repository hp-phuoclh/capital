@extends('layouts.app')

@section('breadcrumb')
<div class="row mb-2">
  <div class="col-sm-6">
    <h1>{{ $category->id ?  __('Edit category') :  __('Create category') }}</h1>
  </div>
  <div class="col-sm-6">
    <ol class="breadcrumb float-sm-right">
      <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
      <li class="breadcrumb-item"><a href="{{ route('categories.index') }}">{{ __('Categories') }}</a></li>
      <li class="breadcrumb-item">{{ $category->id ?  __('Edit category') :  __('Create category') }}</li>
    </ol>
  </div>
</div>
@endsection
@section('content')
<input type="hidden" id="active_nav" value="{{ Route::currentRouteName() }}">
<div class="row justify-content-md-center">
  <div class="col-sm-12">

    <!-- form start -->
    <form class="form-horizontal" action="{{ $category->id ? route('categories.update', ['category' => $category->id]) : route('categories.store') }}" method="POST">
      @csrf
      @if ($category->id)
        @method('PUT')
      @endif
      <div class="card">
        <div class="card-header">
          <span class="card-title">ID: {{ $category->id ?? '--'}}</span>
          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
            </button>
          </div>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
          <div class="form-group row">
            <label for="name" class="col-sm-2 col-form-label">{{ __('Name') }} <span class="text-danger">*</span></label>
            <div class="col-sm-10">
              <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{old('name', $category->name)}}">
            </div>
            @error('name')
            <div class="offset-2 col-sm-10">
              <p class="text-danger">{{ $message }}</p>
            </div>
            @enderror
          </div>

          <div class="form-group row">
            <label for="order" class="col-sm-2 col-form-label">{{ __('Order ') }}</label>
            <div class="col-sm-10">
              <input type="text" class="form-control @error('order') is-invalid @enderror" id="order" name="order" value="{{old('order', $category->order)}}">
            </div>
            @error('order')
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
                value="{!! $category->created_at ? $category->created_at->format('d/m/Y G:i:s') : '' !!}">
            </div>
          </div>

          <div class="form-group row">
            <label for="date" class="col-sm-2 col-form-label">{{ __('Update at')}}</label>
            <div class="col-sm-10">
              <input type="text" class="form-control" id="date" disabled
                value="{!! $category->updated_at ? $category->updated_at->format('d/m/Y G:i:s') : '' !!}">
            </div>
          </div>
          @endif  
      </div>
        <!-- /.card-body -->
      </div>
      <div class="row">
        <div class="col-12">
          <button type="submit" class="btn btn-info float-right">{{ __('Save') }}</button>
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