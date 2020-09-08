@extends('layouts.app')

@section('breadcrumb')
<div class="row mb-2">
  <div class="col-sm-6">
    <h1>{{ __('Order refund') }}</h1>
  </div>
  <div class="col-sm-6">
    <ol class="breadcrumb float-sm-right">
      <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
      <li class="breadcrumb-item"><a href="{{ route('orders.index') }}">{{ __('Order') }}</a></li>
      <li class="breadcrumb-item">{{ __('Order refund') }}</li>
    </ol>
  </div>
</div>
@endsection
@section('content')
<input type="hidden" id="active_nav" value="{{ Route::currentRouteName() }}">
<div class="row justify-content-md-center">
  <div class="col-md-12">
    <div class="card">
      <div class="card-header">
        <span class="card-title">{{__('ID')}}: {{ $order->id ?? '--'}}</span>
        <div class="card-tools">
          <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
          </button>
        </div>
      </div>
      <!-- /.card-header -->
      <div class="card-body">
        <div class="row">
          <div class="col-sm-4">
            <label class="col-form-label">{{__('User')}}</label>
            <div>
              <span>{{ $order->user->name }}</span>
            </div>
          </div>
          <div class="col-sm-4">
            <label class="col-form-label">{{__('Time Order')}}</label>
            <div>
              <span>{!! $order->created_at ? $order->created_at->format('G:i d/m/Y') : '' !!}</span>
            </div>
          </div>
          <div class="col-sm-4">
            <label class="col-form-label">{{__('Num of Products')}}</label>
            <div>
              <span>{{ $order->products->sum('pivot.quantity') }}</span>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-4">
            <label class="col-form-label">{{__('Address')}}</label>
            <div>
              <span>{{ $order->address }}</span>
            </div>
          </div>
          <div class="col-sm-4">
            <label class="col-form-label">{{__('Phone')}}</label>
            <div>
              <span>{{ $order->phone }}</span>
            </div>
          </div>
          <div class="col-sm-4">
            <label class="col-form-label">{{__('Status')}}</label>
            <div>
                {!!Helper::get_status_badge_order($order->status)!!}
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-4">
            <label class="col-form-label">{{__('Note')}}</label>
            <div>
              <span class="white-space">{!! $order->note !!}</span>
            </div>
          </div>
          <div class="col-sm-4">
            <label class="col-form-label">{{__('Total')}}</label>
            <div>
              <span class="text-danger"><h5>{{ number_format($order->total,0,',','.') }}₫</h5></span>
            </div>
          </div>
          <div class="col-sm-4">
            <label class="col-form-label">{{__('Refund amount')}}</label>
            <div>
              <span class="text-primary"><h5>{{ number_format($order->refund,0,',','.') }}₫</h5></span>
            </div>
          </div>
        </div>

      </div>
      <!-- /.card-body -->
    </div>
    <div class="card">
        <div class="card-header">
            {{ __('Refund') }}
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <form action="/orders/refund_update/{{$order->id}}" id="refund_form" method="POST">
                      @csrf
                      <div class="form-group">
                          <label class="col-form-label">{{__('Total')}}</label>
                          <input type="text" class="form-control" value="{{number_format($order->total,0,',','.')}}"  name="total" id="input_total" readonly>
                      </div>
                      <div class="form-group">
                          <label class="col-form-label">{{__('Refund amount')}}</label>
                          <input type="text" class="form-control" id="input_refund" value="{{ number_format($order->refund,0,',','.') }}">
                          <input type="hidden" class="form-control" name="refund" id="input_refund_hide" value="{{$order->refund}}">
                      </div>
                      <div class="form-group">
                          <label class="col-form-label">{{__('Remaining balance')}}</label>
                          <input type="text" class="form-control" name="balance" id="input_balance">
                      </div>
                      <button class="btn btn-primary">{{__('Save')}}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
  </div>
</div>
@endsection
@section('scripts')
<script src="/js/jquery-validation/jquery.validate.min.js"></script>
<script>
    //validate
    $( "#refund_form" ).validate({
        ignore: ":disabled",
        "rules": {
            "refund": {
                "required": true,
            }
        },
        "messages": {
            "refund": {
                "required": "{{__('Required')}}",
            }
        }
    });
    // input onchange
    changeRefund();
    $('#input_refund').on('change',function(){
      changeRefund();
    });

    $('#input_balance').on('change',function(){
      changeBalance();
    });

    function changeRefund() {
      $total = parseInt(del_dot_currency($('#input_total').val()));
        $refund = parseInt(del_dot_currency($('#input_refund').val()));
        if($refund > $total) {
            $('#input_refund').val(0);
            $refund = parseInt($('#input_refund').val());
            toasts("warning","{{__('Refund amount cannot be bigger than total amount')}}","{{__('Notifications')}}");
        }
        $balance = $total-$refund;
        $('#input_refund').val(add_dot_currency($refund));
        $('#input_refund_hide').val(del_dot_currency($refund));
        $('#input_balance').val(add_dot_currency($balance));
    }

    function changeBalance() {
      $total = parseInt(del_dot_currency($('#input_total').val()));
        $balance = parseInt(del_dot_currency($('#input_balance').val()));
        if($balance > $total) {
            $('#input_balance').val(0);
            $balance = parseInt($('#input_balance').val());
            toasts("warning","{{__('The remaining balance cannot be greater than the total amount')}}","{{__('Notifications')}}");
        }
        $refund = $total-$balance;
        $('#input_balance').val(add_dot_currency($balance));
        $('#input_refund_hide').val(del_dot_currency($refund));
        $('#input_refund').val(add_dot_currency($refund));
    }
</script>
@endsection