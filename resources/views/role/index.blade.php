@extends('layouts.app')

@section('breadcrumb')
<div class="row mb-2">
  <div class="col-sm-6">
    <h1>{{ __('Roles')}} </h1>
  </div>
  <div class="col-sm-6">
    <ol class="breadcrumb float-sm-right">
      <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
      <li class="breadcrumb-item">{{ __('Roles')}}</li>
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
                <th>{{ __('Permissions')}}</th>
                <th>{{ __('Action')}}</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($roles as $role)
              <tr>
                <td>{{ $role->id }}</td>
                <td>{{ $role->name }}</td>
                <td>
                    <select class="form-control permission-multiple" name="permissions[]" multiple="multiple">
                        @foreach($permissions as $permission)
                            <option value="{{$permission->name}}" @if($role->hasPermissionTo($permission->name)) selected @endif>{{$permission->name}}</option>
                        @endforeach
                    </select>
                </td>
                <td class="text-right text-nowrap">
                  @can('edit roles')
                  <a class="btn btn-info btn-sm text-white" href="javascript:void(0)" data-id="{{ $role->id }}" onclick="updatePermission(this)">
                    <i class="fas fa-save"></i>
                  </a>
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
  {{ $roles->appends(request()->input())->links() }}
</div>
@endsection
@section('scripts')
<!-- OPTIONAL SCRIPTS -->
{{-- import lib --}}
<link rel="stylesheet" href="/css/select2/select2.min.css">
<link rel="stylesheet" href="/css/select2/select2-bootstrap4.min.css">
<script src="/js/select2/select2.full.min.js"></script>
<script>
    $(document).ready(function() {
        $('.permission-multiple').select2({
            theme: 'bootstrap4',
            width: '100%',
        });
    });

    // update permission
    function updatePermission(target) {
        var $role_id = $(target).data('id');
        // var $permission = $(target).closest('tr').find('select').serialize();

        // var $permission = $(target).closest('tr').find('select').map(function(i, el) {
        //     return el.value;
        // });

        var $permissions = [];
        var $obj = $(target).closest('tr').find('select').serializeArray();
        $.each( $obj, function() {
            $permissions.push(this.value);
        });

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'PUT',
            url: '/roles/update',
            dataType: 'json',
            data: {
                role_id : $role_id,
                permissions : $permissions,
            },
            success: function (result) {
                toasts((result.status == "success" ? "success" : "danger"),result.message,"{{__('Notifications')}}");
            },
            error: function (jqXHR, exception) {
                toasts("danger","{{__('Error!')}}","{{__('Notifications')}}");
            }
        });
    }
</script>
@endsection