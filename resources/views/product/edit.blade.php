@extends('layouts.app')

@section('breadcrumb')
<div class="row mb-2">
  <div class="col-sm-6">
    <h1>{{ $product->id ?  __('Edit Product') :  __('Create Product') }}</h1>
  </div>
  <div class="col-sm-6">
    <ol class="breadcrumb float-sm-right">
      <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
      <li class="breadcrumb-item"><a href="{{ route('products.index') }}">{{ __('Products') }}</a></li>
      <li class="breadcrumb-item">{{ $product->id ?  __('Edit Product') :  __('Create Product') }}</li>
    </ol>
  </div>
</div>
@endsection
@section('content')
<input type="hidden" id="active_nav" value="{{ Route::currentRouteName() }}">
<div class="row justify-content-md-center">
  <div class="col-sm-12">

    <!-- form start -->
    <form class="form-horizontal"
      action="{{ $product->id ? route('products.update', ['product' => $product->id]) : route('products.store') }}"
      method="POST" id="frm_edit">
      @csrf
      @if ($product->id)
      @method('PUT')
      @endif
      <div class="card">
        <div class="card-header">
          <span class="card-title">ID: {{ $product->id ?? '--'}}</span>
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
              <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name"
                value="{{old('name', $product->name)}}">
            </div>
          </div>

          <div class="form-group row">
            <label for="code" class="col-sm-2 col-form-label">{{ __('Product code') }} <span class="text-danger">*</span></label>
            <div class="col-sm-10">
              <input type="text" class="form-control @error('code') is-invalid @enderror" id="code" name="code"
                value="{{old('code', $product->code)}}" {{ $product->id ? 'readonly' : '' }}>
            </div>
          </div>

          <div class="form-group row">
            <label for="category_id" class="col-sm-2 col-form-label">{{ __('Categories') }} <span class="text-danger">*</span></label>
            <div class="col-sm-10">
              <select class="form-control" name="category_id">
                <option selected value disabled>---</option>
                @foreach($categories as $id => $category)
                <option value="{{ $id }}" @if(old('category_id', $product->category_id) == $id) selected
                  @endif>{{ $category }}</option>
                @endforeach
              </select>
            </div>
          </div>

          <div class="form-group row">
            <label for="content" class="col-sm-2 col-form-label">{{ __('Description') }}</label>
            <div class="col-sm-10">
              {{-- @include('form.editor', ['fieldData' => $product->description, 'fieldName' => "description"]) --}}
              <textarea class="form-control" rows="3" id="description" name="description" placeholder="Enter Description">{{old('description', $product->description)}}</textarea>
            </div>
          </div>

          <div class="form-group row">
            <label for="price" class="col-sm-2 col-form-label">{{ __('Price') }} <span class="text-danger">*</span></label>
            <div class="col-sm-10">
              <input type="text" class="form-control price-format" id="price" data-name="price" maxlength="15"
                value="{{ old('price', $product->price)}}">
              <input type="hidden" name="price" value="{{ old('price', $product->price)}}">
            </div>
          </div>

          <div class="form-group row">
            <label for="discount" class="col-sm-2 col-form-label">{{ __('Discount') }} <span class="text-danger">*</span></label>
            <div class="col-sm-10">
              <input type="text" class="form-control price-format" id="sale_off" data-name="sale_off" maxlength="15"
                value="{{ old('sale_off', $product->sale_off)}}">
              <input type="hidden" name="sale_off" value="{{ old('sale_off', $product->sale_off)}}">
            </div>
          </div>

          <div class="form-group row">
            <label for="category_id" class="col-sm-2 col-form-label">{{ __('Size') }}</label>
            <div class="col-sm-10">
              <select class="form-control" id="size" multiple="multiple" name="sizes[]">
                <option disabled>{{__("Please enter size")}}</option>
                @foreach($product->sizes as $size)
                <option value="{{ $size }}" selected="selected">{{ $size }}</option>
                @endforeach
              </select>
            </div>
          </div>

          <div class="form-group row">
            <label for="published" class="col-sm-2 col-form-label">{{ __('Published') }}</label>
            <div class="col-sm-10">
              <div class="icheck-primary d-inline">
                <input type="checkbox" id="published" @if(old('published', $product->published) == 1) checked @endif name="published" value="1">
                <label for="published">
                </label>
              </div>
            </div>
          </div>

          <div class="form-group row">
            <label for="image" class="col-sm-2 col-form-label">{{ __('Image') }}</label>
            <div class="col-sm-10">
              @include('shared.upload', ['multiple' => 'multiple','url_upload_review' => ($product->id ?
              route('product.load_image', ['id'=> $product->id]) : '')])
            </div>
          </div>

          @if(Request::is('*edit'))
          <div class="form-group row">
            <label for="createdated" class="col-sm-2 col-form-label">{{ __('Created at')}}</label>
            <div class="col-sm-10">
              <input type="text" class="form-control" id="createdated" disabled
                value="{!! $product->created_at ? $product->created_at->format('d/m/Y G:i:s') : '' !!}">
            </div>
          </div>

          <div class="form-group row">
            <label for="date" class="col-sm-2 col-form-label">{{ __('Update at')}}</label>
            <div class="col-sm-10">
              <input type="text" class="form-control" id="date" disabled
                value="{!! $product->updated_at ? $product->updated_at->format('d/m/Y G:i:s') : '' !!}">
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
<script src="{{ asset('js/pages/product/edit.js') }}"></script>
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