@extends('layouts.app')

@section('breadcrumb')
<div class="row mb-2">
  <div class="col-sm-6">
    <h1>{{__('Orders')}}</h1>
  </div>
  <div class="col-sm-6">
    <ol class="breadcrumb float-sm-right">
      <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
      <li class="breadcrumb-item">{{__('Orders')}}</li>
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
        <h3 class="card-title">{{__('Search')}}</h3>
        <div class="card-tools">
          <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
          </button>
        </div>
      </div>
      <!-- /.card-header -->
      <div class="card-body">
        <form action="" method="GET" id="search_form">
          <div class="row">
            <div class="col-sm-6">
              <label>{{__('User')}}</label>
              <div class="form-group">
                <select class="form-control select2bs4" name="user_id">
                  <option value="">--</option>
                  @foreach ($users as $id => $user)
                  <option value="{{ $id }}" @if ($id==old('user_id')) selected @endif>{{ $user }}</option>
                  @endforeach
                </select>
              </div>
            </div>
            <div class="col-sm-6">
              <label>{{__('Status')}}</label>
              <div class="form-group">
                <select name="_status" class="form-control select2bs4" style="min-width: 140px;">
                  <option value="">--</option>
                  @foreach ($status as $key => $st)
                  <option value="{{ $key }}" @if ($key==old('_status')) selected @endif>{{ $st }}</option>
                  @endforeach
                </select>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-6">
              <label>{{__('Search Keyword')}}</label>

              <div class="form-group">
                <input type="text" class="form-control" placeholder="{{__('Code or name product')}}" name="search_keyword"
                  value="{{ old("search_keyword") }}">
              </div>
            </div>
            <div class="col-sm-6">
              <label>{{__('Time Order')}}</label>
              <div class="row">
                <div class="col-6">
                  <input type="text" class="form-control input-datepicker-bd" name="start_date" id="start_date" placeholder="{{__('Start Date')}}" value="{{ old("start_date") }}">
                </div>
                <div class="col-6">
                  <input type="text" class="form-control input-datepicker-bd" name="end_date" id="end_date" placeholder="{{__('End Date')}}" value="{{ old("end_date") }}">
                </div>
              </div>
            </div>
          </div>
          <div class="row m-2">
            <!-- text input -->
            <div class="form-group">
              <button type="submit" class="btn btn-primary">
                <i class="fas fa-search"></i> {{__('Search')}}
              </button>
            </div>
          </div>
        </form>
      </div>
      <!-- /.card-body -->
    </div>
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">{{__('List')}}</h3>
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
                <th>{{__('User')}}</th>
                <th>{{__('Code')}}</th>
                <th>{{__('Num of Products')}}</th>
                <th>{{__('Store')}}</th>
                <th>{{__('Total')}}</th>
                <th>{{__('Status')}}</th>
                <th>{{__('Action')}}</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              @foreach ($orders as $order)
              <tr>
                <td>{{ $order->id }}</td>
                <td>{{ $order->user->name }}</td>
                <td>{{ $order->code }}</td>
                <td>{{ $order->products->sum('pivot.quantity') }}</td>
                <td>{{ $order->store ? $order->store->name : '' }}</td>
                <td>{{ number_format($order->total) }} ₫</td>
                <td>
                  {!!Helper::get_status_badge_order($order->status)!!}
                </td>
                <td>
                  @can('edit orders')
                  @include('form.change_status_order')
                  @endcan
                </td>
                <td class="text-right text-nowrap">
                  @if($order->paid_flg && auth()->user()->can('refund orders'))
                    <a href="orders/refund/{{ $order->id }}" class="btn btn-default btn-sm"><i class="fas fa-hand-holding-usd"></i> {{__('Refund')}}</a>
                  @endif
                  @if((!$order->is_completed && $order->status != \OrderStatus::Done) && auth()->user()->can('edit orders'))
                  <a class="btn btn-info btn-sm text-white" href="{{ route('orders.edit', ['order'=> $order->id]) }}">
                    <i class="fas fa-pencil-alt"></i>
                  </a>
                  @endif
                  <a class="btn btn-primary btn-sm text-white"
                    href="{{ route('orders.show', ['order'=> $order->id]) }}">
                    <i class="far fa-eye"></i>
                  </a>
                  {{-- <form class="d-inline form_confirm_action" action="{{ route('orders.destroy', ['order'=> $order->id]) }}"
                  method="POST" data-message="Do you want to delete order {{ $order->name }}?">
                  @csrf
                  @method('DELETE')
                  <button class="btn btn-danger btn-sm">
                    <i class="fas fa-trash"></i>
                  </button>
                  </form> --}}
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
  {{ $orders->appends(request()->input())->links() }}
</div>
@endsection
@section('scripts')
<!-- OPTIONAL SCRIPTS -->
<script src="/js/jquery-validation/jquery.validate.min.js"></script>
<script src="/js/moment/moment.min.js"></script>
<script>
  $(function () {
    $('.input-datepicker-bd').datepicker({
      autoclose: true,
      format: 'dd/mm/yyyy',
      language: '{{ app()->getLocale() }}',
    })
  });

  // onchange start_date
  $('#start_date').on('change',function(){
    $start_date = $('#start_date').val();
    $end_date = $('#end_date').val();
    var time1 = moment(format_date_2($start_date)).format('YYYY-MM-DD');
    var time2 = moment(format_date_2($end_date)).format('YYYY-MM-DD');
    if(!$('#end_date').val() || time1 > time2) {
      $('#end_date').val($start_date);
    }
  });

  // validation
    // validate form
    $.validator.addMethod("required_date", function(value, element) {
        return this.optional(element) || moment(value,"DD/MM/YYYY").isValid();
    }, "{{__('validate format date')}}");

    // check date
    $.validator.addMethod("greaterThan",function(value, element, params) {
        value = format_date_2(value);
        if (!/Invalid|NaN/.test(new Date(value))) {
            return new Date(value) >= new Date(format_date_2($(params).val()));
        }
        return isNaN(value) && isNaN(format_date_2($(params).val()))
            || (Number(value) >= Number(format_date_2($(params).val())));
    },"{{__('validate greater than date')}}");
    // validate
    $( "#search_form" ).validate({
        "rules": {
            "start_date": {
                "required_date": true,
            },
            "end_date": {
                "required_date": true,
                "greaterThan": "#start_date",
            },
        }
    });
</script>
@endsection