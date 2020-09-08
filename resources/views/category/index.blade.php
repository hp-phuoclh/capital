@extends('layouts.app')

@section('breadcrumb')
<div class="row mb-2">
  <div class="col-sm-6">
    <h1>{{ __('Categories') }}</h1>
  </div>
  <div class="col-sm-6">
    <ol class="breadcrumb float-sm-right">
      <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
      <li class="breadcrumb-item">{{ __('Categories') }}</li>
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
                <th>{{ __('Name') }}</th>
                <th>{{ __('Order ') }}</th>
                <th>{{ __('Action') }}</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($categories as $category)
              <tr>
                <td>{{ $category->id }}</td>
                <td>{{ $category->name }}</td>
                <td>{{ $category->order }}</td>
                <td class="text-right text-nowrap">
                  {{-- <a class="btn btn-primary btn-sm text-white" href="{{ route('categories.show', ['category'=> $category->id]) }}">
                    <i class="far fa-eye"></i>
                  </a> --}}
                  @can('edit categories')
                  <a class="btn btn-info btn-sm text-white" href="{{ route('categories.edit', ['category'=> $category->id]) }}">
                    <i class="fas fa-pencil-alt"></i>
                  </a>
                  @endcan
                  @can('delete categories')
                  <form class="d-inline form_confirm_action" action="{{ route('categories.destroy', ['category'=> $category->id]) }}"
                    method="POST" data-message="{{__('Do you want to delete ')}}{{ $category->name }}?">
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
  {{ $categories->appends(request()->input())->links() }}
</div>
@endsection