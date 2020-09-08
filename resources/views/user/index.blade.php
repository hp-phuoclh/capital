@extends('layouts.app')

@section('breadcrumb')
<div class="row mb-2">
  <div class="col-sm-6">
    <h1>{{ __('Users')}} </h1>
  </div>
  <div class="col-sm-6">
    <ol class="breadcrumb float-sm-right">
      <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
      <li class="breadcrumb-item">{{ __('Users')}}</li>
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
                <th>{{ __('Phone')}}</th>
                <th>{{ __('Action')}}</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($users as $user)
              <tr>
                <td>{{ $user->id }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->phone }}</td>
                <td class="text-right text-nowrap">
                  <a class="btn btn-primary btn-sm text-white" href="{{ route('users.show', ['user'=> $user->id]) }}">
                    <i class="far fa-eye"></i>
                  </a>
                  @can('edit users')
                  <a class="btn btn-info btn-sm text-white" href="{{ route('users.edit', ['user'=> $user->id]) }}">
                    <i class="fas fa-pencil-alt"></i>
                  </a>
                  @endcan
                  {{-- <form class="d-inline form_confirm_action" action="{{ route('users.destroy', ['user'=> $user->id]) }}"
                    method="POST" data-message="{{__('Do you want to delete ')}}{{ $user->name }}?">
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
  {{ $users->appends(request()->input())->links() }}
</div>
@endsection