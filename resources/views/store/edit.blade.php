@extends('layouts.app')

@section('breadcrumb')
<div class="row mb-2">
  <div class="col-sm-6">
    <h1>{{ $store->id ?  __('Edit store branch') :  __('Create store branch') }}</h1>
  </div>
  <div class="col-sm-6">
    <ol class="breadcrumb float-sm-right">
      <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
      <li class="breadcrumb-item"><a href="{{ route('stores.index') }}">{{ __('Stores branch') }}</a></li>
      <li class="breadcrumb-item">{{ $store->id ?  __('Edit store branch') :  __('Create store branch') }}</li>
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
      action="{{ $store->id ? route('stores.update', ['store' => $store->id]) : route('stores.store') }}" method="POST">
      @csrf
      @if ($store->id)
      @method('PUT')
      @endif
      <div class="card">
        <div class="card-header">
          <span class="card-title">ID: {{ $store->id ?? '--'}}</span>
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
              <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name"
                value="{{old('name', $store->name)}}">
            </div>
            @error('name')
            <div class="offset-2 col-sm-10">
              <p class="text-danger">{{ $message }}</p>
            </div>
            @enderror
          </div>

          <div class="form-group row">
            <label for="email" class="col-sm-2 col-form-label">{{ __('Phone') }}</label>
            <div class="col-sm-10">
              <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone"
                value="{{old('phone', $store->phone)}}">
            </div>
            @error('phone')
            <div class="offset-2 col-sm-10">
              <p class="text-danger">{{ $message }}</p>
            </div>
            @enderror
          </div>

          <div class="form-group row">
            <label for="email" class="col-sm-2 col-form-label">{{ __('Email') }}</label>
            <div class="col-sm-10">
              <input type="text" class="form-control @error('email') is-invalid @enderror" id="email" name="email"
                value="{{old('email', $store->email)}}">
            </div>
            @error('email')
            <div class="offset-2 col-sm-10">
              <p class="text-danger">{{ $message }}</p>
            </div>
            @enderror
          </div>

          <div class="form-group row">
            <label for="address" class="col-sm-2 col-form-label">{{ __('Address') }}</label>
            <div class="col-sm-10">
              <input type="text" class="form-control col-sm-10 @error('address') is-invalid @enderror"
                id="searchLocationMap" name="address" value="{{old('address', $store->address)}}" placeholder="địa chỉ">
              @include('form.maps', ['lat' => old('lat', $store->lat), 'long' => old('long', $store->long)])
            </div>
            @error('address')
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
                value="{!! $store->created_at ? $store->created_at->format('d/m/Y G:i:s') : '' !!}">
            </div>
          </div>

          <div class="form-group row">
            <label for="date" class="col-sm-2 col-form-label">{{ __('Update at')}}</label>
            <div class="col-sm-10">
              <input type="text" class="form-control" id="date" disabled
                value="{!! $store->updated_at ? $store->updated_at->format('d/m/Y G:i:s') : '' !!}">
            </div>
          </div>
          @endif
        </div>
        <!-- /.card-body -->
      </div>
      <div class="card">
        <div class="card-header">
          <span class="card-title">{{ __('Products') }}</span>
          <div class="card-tools">
            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#modal-add-product"><i
                class="fas fa-plus"></i> {{ __('Add products') }}</button>
            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
            </button>
          </div>
        </div>
        <!-- /.card-header -->
        <div class="card-body product_list">
          <table id="store_add_product" class="table table-bordered table-hover"></table>
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
@include('shared.modal_product', ['model_id' => 'modal-add-product'])
@endsection
@section('scripts')
<!-- Ekko Lightbox -->
<script>
  var store_products = @json($store->id ? $store->store_products : $store->store_products_first());
  var store_old = @json(old('products'));
  $(function () {
    $('form input').keydown(function(event){
      if(event.keyCode == 13) {
        event.preventDefault();
        return false;
      }
    });
    var product_table = $('#store_add_product').DataTable({
        "paging": false,
        'order': [[2, 'asc']],
        "sDom": '<"top"lif<"clear">>rt<"bottom"ipl<"clear">>',
        'scrollY': '50vh',
        "sScrollX": '100%',
        "sScrollXInner": "100%",
        'scrollCollapse': true,
        "data": store_products,
        @if(app()->getLocale() == 'vi')
        language: {
            "url": "/js/datatables-bs4/Vietnamese-product.json"
        },
        @endif
        "columns": [
            {
                "data": "product_id",
                "title": "#",
                render: function (data, type, row, meta) {
                    return type === 'display' ?
                        `<input type="hidden" name="products[${meta.row}][id]" value="${data}">${data}` :
                        data;
                }
            },
            {
                "data": "image",
                "title": "{{__('Image')}}",
                render: function (data, type, row, meta) {
                    return type === 'display' ?
                        '<img src="/' + data + '" class="img-fit img-fit-sm">' :
                        data;
                }
            },
            { "data": "name", "title": "{{__('Name')}}" },
            { "data": "code", "title": "{{__('Product code')}}" },
            { 
              "data": "category.name",
              "title": "{{__('Category')}}",
              render: function (data, type, row, meta) {
                    return data == undefined ? '' : data;
                },
            },
            {
                "data": "price",
                "class": "price",
                "title": "{{__('Price')}}",
                render: function (data, type, row, meta) {
                    return type === 'display' ?
                        `<input type="text" class="form-control price-format" data-name="products[${meta.row}][price]" maxlength="15"
                              value="${data}">
                            <input type="hidden" name="products[${meta.row}][price]" value="${data}">` :
                        data;
                },
                createdCell: function (td, cellData, rowData, row, col) {
                    $(td).addClass('price-'+rowData.product_id);
                } 
            },
            {
                "data": "sale_off",
                "title": "{{__('Discount')}}",
                render: function (data, type, row, meta) {
                    return type === 'display' ?
                        `<input type="text" class="form-control price-format" data-name="products[${meta.row}][sale_off]" maxlength="15"
                              value="${data}">
                            <input type="hidden" name="products[${meta.row}][sale_off]" value="${data}">` :
                        data;
                }
            },
            {
                "title": "",
                render: function () {
                    return `<a class="btn btn-danger btn-sm confirm_product text-white w-100">
                              <i class="fas fa-trash"></i>
                            </a>
                            <a class="btn btn-warning btn-sm delete_product text-white w-100 d-none">
                              <i class="fas fa-exclamation"></i>
                            </a>`;
                }
            },
        ],
        "initComplete": function () {
            var api = this.api();
            if(store_old != undefined){
              $.each(store_old, function(index, old){
                if(!old.price){
                  $('.price-'+old.id).addClass('row-error');
                }
                $('.price-'+old.id).find('input').val(old.price);
                $('.sale_off-'+old.id).find('input').val(old.sale_off);
              })
              toasts('error', 'Giá sản phẩm là bắt buộc', 'Lỗi', '');
              $('.row-error:first input').focus();
            }
            api.$('td .price-format').trigger('input');

            if (/Mobi/.test(navigator.userAgent)) {
              product_table.columns( [0,1,3] ).visible( false );
            }
        }
    });
    $('#store_add_product tbody').on( 'click', 'a.confirm_product', function (event) {
        event.stopPropagation()
        $(this).toggleClass('d-none');
        $(this).next().toggleClass('d-none');
    });
    $('#store_add_product tbody').on( 'click', 'a.delete_product', function (event) {
        event.stopPropagation()
        product_table
            .row( $(this).parents('tr') )
            .remove()
            .draw();
    });
    $('#store_add_product tbody').on( 'click', 'tr', function () {
        if($('.confirm_product', $(this)).hasClass('d-none')){
          $('.confirm_product', $(this)).toggleClass('d-none');
          $('.delete_product', $(this)).toggleClass('d-none');
        }
    });
    $(window).resize(function(){
      if($(this).innerWidth() < 715){
        product_table.columns( [0,1,3] ).visible( false );
      } else {
        product_table.columns( [0,1,3] ).visible( true );
      }
    })
    $('#modal-add-product').on('choosed', function (e, data) {
        var toasts_flg = true, count_exists = product_table
                .columns( [0] )
                .data()
                .flatten()
                .filter( function ( value, index ) {
                    return data.ids.indexOf(value.toString()) > 0 ? true : false;
                } );
        if(count_exists.length > 10) {
          toasts_flg = false;
        }
        $.each(data.products, function(index, p){
            var checks = product_table
                .columns( [0] )
                .data()
                .flatten()
                .filter( function ( value, index ) {
                    return value == p.id ? true : false;
                } );
            if ( checks.any() ) {
              if(toasts_flg){
                toasts('error', 'sản phẩm <b>'+p.name+'</b> đã tồn tại', 'Đã tồn tại', '');
              }
            }else{
              product_table.row.add(p).draw();
            }
        });

        if(!toasts_flg) {
          toasts('error', 'có <b>'+count_exists.length+'</b> sản phẩm đã tồn tại', 'Đã tồn tại', '');
        }
    });
})


</script>
@endsection