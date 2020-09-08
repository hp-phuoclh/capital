@extends('layouts.app')

@section('breadcrumb')
<div class="row mb-2">
  <div class="col-sm-6">
    <h1> {{__('Stores')}}</h1>
  </div>
  <div class="col-sm-6">
    <ol class="breadcrumb float-sm-right">
      <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
      <li class="breadcrumb-item">{{__('Stores')}}</li>
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
          <div class="input-group">
            <input type="text" class="form-control" placeholder="Search" name="search_keyword"
              value="{{ old("search_keyword") }}">
            <div class="input-group-append">
              <button type="submit" class="btn btn-primary">
                <i class="fas fa-search"></i>
              </button>
            </div>
          </div>
        </form>
      </div>
      <!-- /.card-header -->
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-striped">
            <thead>
              <tr>
                <th>#</th>
                <th>{{__('Name')}}</th>
                <th>{{__('Phone')}}</th>
                <th>{{__('Email')}}</th>
                <th>{{__('Address')}}</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              @foreach ($stores as $store)
              <tr>
                <td>{{ $store->id }}</td>
                <td>{{ $store->name }}</td>
                <td>{{ $store->phone }}</td>
                <td>{{ $store->email }}</td>
                <td>{{ $store->address }}</td>
                <td class="text-right text-nowrap">
                  @can('edit stores')
                  <a class="btn btn-info btn-sm text-white" href="{{ route('stores.edit', ['store'=> $store->id]) }}">
                    <i class="fas fa-pencil-alt"></i>
                  </a>
                  @endcan
                  @can('delete stores')
                  <form class="d-inline form_confirm_action" action="{{ route('stores.destroy', ['store'=> $store->id]) }}"
                    method="POST" data-message="{{__('Do you want to delete ')}}{{ $store->name }}?">
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
  {{ $stores->appends(request()->input())->links() }}
</div>
@endsection