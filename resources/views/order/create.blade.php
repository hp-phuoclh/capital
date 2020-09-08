@extends('layouts.app')

@section('breadcrumb')
<div class="row mb-2">
  <div class="col-sm-6">
    <h1>{{__('Create Order')}}</h1>
  </div>
  <div class="col-sm-6">
    <ol class="breadcrumb float-sm-right">
      <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
      <li class="breadcrumb-item"><a href="{{ route('orders.index') }}">{{__('Orders')}}</a></li>
      <li class="breadcrumb-item">{{__('Create Order')}}</li>
    </ol>
  </div>
</div>
@endsection
@section('content')
<input type="hidden" id="active_nav" value="{{ Route::currentRouteName() }}">
<form action="{{route('orders.store')}}" method="POST" id="create_order_form">
  @csrf
  <div class="row justify-content-md-center">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">{{__('Info')}}</h3>
          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
            </button>
          </div>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            <div class="row">
              <div class="col-sm-12">
                <label>{{__('User')}} <span class="text-danger">*</span></label>
                <div class="form-group">
                    <div class="wrap_search_user">
                        <select name="search_user" id="search_user" class="form-control{{ $errors->has('search_user') ? ' is-invalid' : '' }}"></select>
                        @if ($errors->has('search_user'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('search_user') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>
              </div>
              <div class="col-sm-12">
                <label>{{__('Store')}} <span class="text-danger">*</span></label>
                <div class="form-group">
                    <div class="wrap_search_store">
                        <select name="search_store" id="search_store" class="form-control{{ $errors->has('search_store') ? ' is-invalid' : '' }}"></select>
                        @if ($errors->has('search_store'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('search_store') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>
              </div>
              <div class="col-sm-12">
                <label>{{__('Address')}} <span class="text-danger">*</span></label>
                <div class="form-group">
                    <div class="wrap_address">
                        <input type="text" name="address" id="address" class="form-control{{ $errors->has('address') ? ' is-invalid' : '' }}" placeholder="{{__('Input Address')}} "></input>
                        @if ($errors->has('address'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('address') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>
              </div>
            </div>
        </div>
        <!-- /.card-body -->
      </div>
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">{{__('Products')}} <span class="text-danger">*</span></h3>
          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
            </button>
          </div>
        </div>
        <div class="card-body">
          <div>
            <div class="row">
                <div class="col-md-12" id="wrap_list_product_order">
                    <div class="form-group">
                        <label for="search_product">{{__('Add products')}}</label><br>
                        <select name="search_product" id="search_product" class="form-control"></select>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped table-valign-middle table-default">
                            <thead>
                            <tr>
                            <th>#</th>
                            <th>{{__('Product code')}}</th>
                            <th>{{__('Image')}}</th>
                            <th>{{__('Product name')}}</th>
                            <th>{{__('Unit price')}}</th>
                            <th>{{__('Num of Products')}}</th>
                            <th>{{__('Action')}}</th>
                            </tr>
                            </thead>
                            <tbody id="list_product_order">
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td><b>{{__('Total:')}}</b></td>
                                    <td id="order_amount">0</td>
                                    <td id="order_total">0</td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <input type="hidden" id="order_total_check" name="order_total_check">
                    <input type="hidden" class="is-invalid">
                    @if ($errors->has('products.*'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('products.*') }}</strong>
                            </span>
                        @endif
                </div>
            </div>
        </div>
        </div>
      </div>
      <!-- /.card -->
      <div class="card">
        <div class="card-body">
                <div class="row">
                  <div class="col-md-12">
                      <div class="form-group">
                          <textarea name="note" class="form-control{{ $errors->has('note') ? ' is-invalid' : '' }}" id="note" placeholder="{{__('Note')}}" cols="30" rows="4" style="height: 125px">{{ old('note') }}</textarea>
                              @if ($errors->has('note'))
                                  <span class="invalid-feedback" role="alert">
                                      <strong>{{ $errors->first('note') }}</strong>
                                  </span>
                              @endif
                      </div>
                  </div>
              </div>
            </div>
            <!-- /.card-body -->
            <div class="card-footer text-center">
              <button type="submit" class="btn btn-md bg-primary">{{ __('Save') }}</button>
            </div>
        </div>
      </div>
      <!-- /.card -->
    </div>
  </div>
</form>
@endsection
@section('scripts')
<!-- OPTIONAL SCRIPTS -->
{{-- import lib --}}
<link rel="stylesheet" href="/css/select2/select2.min.css">
<link rel="stylesheet" href="/css/select2/select2-bootstrap4.min.css">
<script src="/js/jquery-validation/jquery.validate.min.js"></script>
<script src="/js/select2/select2.full.min.js"></script>
<script src="/js/bootstrap-input-spinner/bootstrap-input-spinner.js"></script>
{{-- custom --}}
<script>
  // search user
  $('#search_user').select2({
        ajax: {
            type: 'GET',
            url: '/orders/search/get_user',
            dataType: 'json',
            data: function (params) {
                var query = {
                    keyword: params.term,
                }
                // Query parameters will be ?search=[term]&type=public
                return query;
            },
            processResults: function (data) {
                    var res = data.items.map(function (item) {
                        return {id: item.id, text: item.text};
                    });
                return {
                    results: res
                };
            },
            cache: true
        },
        placeholder: "{{__('Enter your name, phone number or email')}}",
        minimumInputLength: 1,
        theme: 'bootstrap4',
        width: '100%',
    });

    // search store
  $('#search_store').select2({
        ajax: {
            type: 'GET',
            url: '/orders/search/get_store',
            dataType: 'json',
            data: function (params) {
                var query = {
                    keyword: params.term,
                }
                // Query parameters will be ?search=[term]&type=public
                return query;
            },
            processResults: function (data) {
                    var res = data.items.map(function (item) {
                        return {id: item.id, text: item.text};
                    });
                return {
                    results: res
                };
            },
            cache: true
        },
        placeholder: "{{__('Enter the store branch name, phone number or address')}}",
        minimumInputLength: 1,
        theme: 'bootstrap4',
        width: '100%',
    });

    //search product
    $('#search_product').select2({
        ajax: {
            type: 'GET',
            url: '/orders/search/get_product',
            dataType: 'json',
            data: function (params) {
                var query = {
                    keyword: params.term,
                }
                // Query parameters will be ?search=[term]&type=public
                return query;
            },
            processResults: function (data, params) {
                return {
                    results: data.items,
                };
            },
            cache: true
        },
        placeholder: "{{__('Enter the name, price or product code to search')}}",
        minimumInputLength: 1,
        templateResult: formatRepo,
        templateSelection: formatRepoSelection,
        theme: 'bootstrap4',
        width: '100%',
    });

    // foramt option select product
    function formatRepo (repo) {
        if (repo.loading) {
            return repo.text;
        }


        var $container = $(
            "<div class='select2-result-repository clearfix'>" +
            "<div class='select2-result-repository__avatar'><img class='image-product-search img-fit img-fit-sm' src='/" +repo.image+ "' /></div>" +
            "<div class='select2-result-repository__meta'>" +
                "<div class='select2-result-repository__title'><span class='name'></span><span class='code'></span></div>" +
                "<div class='select2-result-repository__price'></div>" +
                "<div class='select2-result-repository__price_sale'></div>" +
                "</div>" +
            "</div>" +
            "</div>"
        );

        $container.find(".select2-result-repository__title .name").text(repo.name);
        $container.find(".select2-result-repository__title .code").text(' #'+repo.code);
        if(repo.sale_off) {
          $container.find(".select2-result-repository__price").text(add_dot_currency(repo.price));
          $container.find(".select2-result-repository__price_sale").text(add_dot_currency(repo.sale_off));
        } else {
          $container.find(".select2-result-repository__price_sale").text(add_dot_currency(repo.price));
        }

        return $container;
    }

    function formatRepoSelection (repo) {
        return repo.name || repo.text;
    }

    // get onchange select product
    $('#search_product').on('change', function() {
       var id = $(this).val();
       if(!id) {
           return false;
       }
       $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'GET',
            url: '/orders/search/get_product_by_id',
            dataType: 'json',
            data: {
                id: id
            },
            success: function (result) {
                if(result.status == 'success') {
                    var $product_id = result.data.product.id;

                    var $pd_exist = $('#list_product_order').find('tr#pr_'+$product_id);
                    if ($pd_exist.length) {
                        $quantity = $pd_exist.find('.quantity_product');
                        $quantity.val(parseInt($quantity.val())+1);
                        count_total_order();
                    } else {
                        $html = '<tr data-id="'+$product_id+'" id="pr_'+$product_id+'">'+
                                    '<td>'+$product_id+'</td>'+
                                    '<td>'+result.data.product.code+'</td>'+
                                    '<td>'+
                                        '<div class="img">'+
                                            '<img class="img-fit img-fit-sm" src="/'+ (result.data.product.image)+'" alt="">'+
                                        '</div>'+
                                    '</td>'+
                                    '<td>'+result.data.product.name+'</td>'+
                                    '<td>'+
                                        '<div><s>'+
                                            add_dot_currency(result.data.product.sale_off ? result.data.product.price : '')+
                                        '</s></div>'+
                                        '<div>'+
                                            add_dot_currency(result.data.product.sale_off ? result.data.product.sale_off : result.data.product.price  )+
                                        '</div>'+
                                        '<input type="hidden" class="product_price" value="'+(result.data.product.sale_off ? result.data.product.sale_off : result.data.product.price)+'">'+
                                    '</td>'+
                                    '<td>'+
                                        '<input type="number" value="1" min="1" step="1" class="quantity_product" name="products['+$product_id+']">'+
                                    '</td>'+
                                    '<td><button type="button" class="btn btn-sm btn-danger bt_remove">Xoá</button></td>'+
                                '</tr>';
                        $('#list_product_order').append($html);
                        $('#pr_'+$product_id).find('.quantity_product').inputSpinner();
                        count_total_order();
                    }
                } else {
                    alert(result.message);
                }
            },
            error: function (jqXHR, exception) {
                toasts("danger","{{__('Error!')}}","{{__('Notifications')}}");
            }
        });

        // clear search
        $(this).val('');
        $(this).trigger('change');
    });

    // cal total quantity order when change
    $('#list_product_order').on('change','.quantity_product',function(){
        count_total_order();
    });

    // remove product order
    $('#list_product_order').on('click','.bt_remove',function(){
        $(this).closest('tr').remove();
        count_total_order();
    });

    // cal total price and quantity product order
    function count_total_order() {
        var $order_total = 0;
        var $order_amount = 0;
        $('#list_product_order tr').each(function(i,e){
            $order_total += parseInt($(this).find('.quantity_product').val());
            $order_amount += parseInt($(this).find('.product_price').val())*parseInt($(this).find('.quantity_product').val());
        });
        $('#order_total').text($order_total);
        $('#order_total_check').val($order_total);
        $('#order_amount').text(add_dot_currency($order_amount));
    }

    // validate form
    $( "#create_order_form" ).validate({
        ignore: ":disabled",
        "rules": {
            "search_user": {
                "required": true,
            },
            "search_store": {
                "required": true,
            },
            "address": {
                "required": true,
            },
            "order_total_check": {
                "required": true,
            }
        },
        "messages": {
            "search_user": {
                "required": "{{__('Required')}}",
            },
            "search_store": {
                "required": "{{__('Required')}}",
            },
            "address": {
                "required": "{{__('Required')}}",
            },
            "order_total_check": {
                "required": "{{__('No products have been selected!')}}",
            }
        },
        errorPlacement: function(error, element) {
            var $name = element.attr("name");
            if ($name == "search_user"){
                error.insertAfter(element.closest(".wrap_search_user"));
            } else if($name == "search_store") {
                error.insertAfter(element.closest(".wrap_search_store"));
            }
            else {
                error.insertAfter(element);
            }
        },
    });
</script>
@endsection