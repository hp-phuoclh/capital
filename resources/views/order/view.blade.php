@extends('layouts.app')

@section('breadcrumb')
<div class="row mb-2">
  <div class="col-sm-6">
    <h1>{{ __('Order View') }}</h1>
  </div>
  <div class="col-sm-6">
    <ol class="breadcrumb float-sm-right">
      <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
      <li class="breadcrumb-item"><a href="{{ route('orders.index') }}">{{ __('Order') }}</a></li>
      <li class="breadcrumb-item">{{ __('Order View') }}</li>
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
          <div class="col-sm-6">
            <label class="col-form-label">{{__('User')}}</label>
            <div>
              <span>{{ $order->user->name }}</span>
            </div>
          </div>
          <div class="col-sm-6">
            <label class="col-form-label">{{__('Time Order')}}</label>
            <div>
              <span>{!! $order->created_at ? $order->created_at->format('G:i d/m/Y') : '' !!}</span>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-6">
            <label class="col-form-label">{{__('Address')}}</label>
            <div>
              <span>{{ $order->address }}</span>
            </div>
          </div>
          <div class="col-sm-6">
            <label class="col-form-label">{{__('Phone')}}</label>
            <div>
              <span>{{ $order->phone }}</span>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-6">
            <label class="col-form-label">{{__('Note')}}</label>
            <div>
              <span class="white-space">{!! $order->note !!}</span>
            </div>
          </div>
        </div>

      </div>
      <!-- /.card-body -->
    </div>
    <div class="card">
      <div class="card-header">
        <span class="card-title pr-3">{{__('Total')}}: </span><b class="h5"><span
            class="badge bg-danger">{{ number_format($order->total) }} ₫</span></b>
        <div class="card-tools">
          <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
          </button>
        </div>
      </div>
      <!-- /.card-header -->
      <div class="card-body">
        <div class="row">
          <div class="col-sm-6">
            <label class="col-form-label">{{__('Status')}}</label>
            <div>
              {!!Helper::get_status_badge_order($order->status)!!}
            </div>
          </div>
          <div class="col-sm-6">
            <label class="col-form-label">{{__('Num of Products')}}</label>
            <div>
              <span>{{ $order->products->sum('pivot.quantity') }}</span>
            </div>
          </div>
          <div class="col-sm-6">
            <label class="col-form-label">{{__('Action')}}</label>
            <div>
              @include('form.change_status_order')
            </div>
          </div>
        </div>
      </div>
      <!-- /.card-body -->
    </div>
    <div class="card">
      <div class="card-header">
        <span class="card-title pr-3">{{__('Products')}}</span>
        <div class="card-tools">
          <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
          </button>
        </div>
      </div>
      <!-- /.card-header -->
      <div class="card-body">
        
        <div class="table-responsive">
          <table class="table table-striped">
            <thead>
              <tr>
                <th>#</th>
                <th>{{__('Name')}}</th>
                {{-- <th>{{__('Size')}}</th> --}}
                <th>{{__('Quantity')}}</th>
                <th>{{__('Price')}}</th>
                <th>{{__('Total')}}</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($order->products as $product)
              <tr>
                <td>{{ $product->id }}</td>
                <td>{{ $product->name }}</td>
                {{-- <td>{{ $product->pivot->size }}</td> --}}
                <td>{{ $product->pivot->quantity }}</td>
                <td>{{ number_format($product->pivot->price) }} ₫</td>
                <td>{{ number_format(($product->pivot->quantity * $product->pivot->price)) }} ₫</td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
      <!-- /.card-body -->
    </div>
  </div>
</div>
@endsection