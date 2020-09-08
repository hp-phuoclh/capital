@extends('layouts.app')

@section('breadcrumb')
<div class="row mb-2">
  <div class="col-sm-6">
    <h1>{{ __('Products') }}</h1>
  </div>
  <div class="col-sm-6">
    <ol class="breadcrumb float-sm-right">
      <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
      <li class="breadcrumb-item">{{ __('Products') }}</li>
    </ol>
  </div>
</div>
@endsection
@section('content')
<input type="hidden" id="active_nav" value="{{ Route::currentRouteName() }}">
<div class="row justify-content-md-center">
  <div class="col-12">
    <div class="card">
      <div class="card-header">
        <form class="form-inline float-left" action="" method="GET">
          <div class="form-group m-2">
            <select class="form-control" name="category_id">
              <option value="">{{__('Search by category')}}</option>
              @foreach ($categories as $id => $category)
                <option value="{{ $id }}" @if ($id == old('category_id')) selected @endif>{{ $category }}</option>
              @endforeach
            </select>
          </div>
          <div class="input-group">
            <input type="text" class="form-control" placeholder="{{__('Name or description')}}" name="search_keyword"
              value="{{ old("search_keyword") }}">
            <div class="input-group-append">
              <button type="submit" class="btn btn-primary">
                <i class="fas fa-search"></i>
              </button>
            </div>
          </div>
        </form>
        <div class="card-tools">
         
        </div>
      </div>
      <!-- /.card-header -->
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-striped">
            <thead>
              <tr>
                <th>#</th>
                <th>{{ __('Image') }}</th>
                <th>{{ __('Name') }}</th>
                <th>{{ __('Category') }}</th>
                <th>{{ __('Product code') }}</th>
                <th>{{ __('Price') }}</th>
                <th>{{ __('Discount') }}</th>
                <th>{{ __('Action') }}</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($products as $product)
              <tr>
                <td>{{ $product->id }}</td>
                <td>
                @foreach ($product->images as $image)
                  <a href="{{ asset($image) }}" data-toggle="lightbox" data-title="{{ $product->name }}" data-gallery="gallery{{$product->name}}" class="{{$loop->first ? '' : 'd-none'}} ">
                    <img src="{{ asset($image) }}" class="img-fit img-fit-sm" alt="Image" width="40">
                  </a>
                @endforeach
                </td>
                <td>{{ $product->name }}</td>
                <td>{{ $product->category ? $product->category->name : '' }}</td>
                <td>{{ $product->code }}</td>
                <td>{{ number_format($product->price) }} ₫</td>
                <td>{{ number_format($product->sale_off) }} ₫</td>
                <td class="text-right text-nowrap">
                  {{-- <a class="btn btn-primary btn-sm text-white" href="{{ route('products.show', ['product'=> $product->id]) }}">
                    <i class="far fa-eye"></i>
                  </a> --}}
                  @can('edit products')
                  <a class="btn btn-info btn-sm text-white" href="{{ route('products.edit', ['product'=> $product->id]) }}">
                    <i class="fas fa-pencil-alt"></i>
                  </a>
                  @endcan
                  @can('delete products')
                  <form class="d-inline form_confirm_action" action="{{ route('products.destroy', ['product'=> $product->id]) }}"
                    method="POST" data-message="{{__('Do you want to delete ')}}{{ $product->name }}?">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger btn-sm">
                      <i class="fas fa-trash"></i>
                    </button>
                  </form>
                  @endcan
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
      <!-- /.card-body -->
    </div>
    <!-- /.card -->
  </div>
  {{ $products->appends(request()->input())->links() }}
</div>
@endsection
@section('css')
  <!-- Ekko Lightbox -->
  <link href="{{ asset('css/ekko-lightbox/ekko-lightbox.css') }}" rel="stylesheet">
@endsection
@section('scripts')
<!-- Ekko Lightbox -->
<script src="{{ asset('js/ekko-lightbox/ekko-lightbox.min.js') }}"></script>
<script>
  $(function () {
    $(document).on('click', '[data-toggle="lightbox"]', function(event) {
      event.preventDefault();
      $(this).ekkoLightbox({
        alwaysShowClose: true,
      });
    });
  })
</script>
@endsection