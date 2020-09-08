@extends('layouts.app')

@section('breadcrumb')
<div class="row mb-2">
  <div class="col-sm-6">
    <h1>{{ __('Admins')}} </h1>
  </div>
  <div class="col-sm-6">
    <ol class="breadcrumb float-sm-right">
      <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
      <li class="breadcrumb-item">{{ __('Admins')}}</li>
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
                <th>{{ __('Name')}}</th>
                <th>{{ __('Email')}}</th>
                <th>{{ __('Role')}}</th>
                <th>{{ __('Action')}}</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($admins as $admin)
              @if(!$admin->hasRole('super-admin'))
                <tr>
                  <td>{{ $admin->id }}</td>
                  <td>{{ $admin->name }}</td>
                  <td>{{ $admin->email }}</td>
                  <td>{{ $admin->getRoleNames()->first() }}</td>
                  <td class="text-right text-nowrap">
                    @can('edit admins')
                    <a class="btn btn-info btn-sm text-white" href="{{ route('admins.edit', ['admin'=> $admin->id]) }}">
                      <i class="fas fa-pencil-alt"></i>
                    </a>
                    @endcan
                    @can('delete admins')
                    <form class="d-inline form_confirm_action" action="{{ route('admins.destroy', ['admin'=> $admin->id]) }}"
                      method="POST" data-message="{{__('Do you want to delete ')}}{{ $admin->name }}?">
                      @csrf
                      @method('DELETE')
                      <button class="btn btn-danger btn-sm">
                        <i class="fas fa-trash"></i>
                      </button>
                    </form>
                    @endcan
                  </td>
                </tr>
                @endif
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
      <!-- /.card-body -->
    </div>
    <!-- /.card -->
  </div>
  {{ $admins->appends(request()->input())->links() }}
</div>
@endsection