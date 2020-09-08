<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>{{ config('app.name', 'Super Admin') }}</title>

  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ asset('css/fontawesome-free/css/all.min.css') }}">
  <!-- Ionicons -->
  <link rel="stylesheet" href="{{ asset('css/ionicons/ionicons.min.css') }}">
  <!-- Select2 -->
  <link rel="stylesheet" href="{{ asset('css/select2/select2.min.css') }}">
  <link rel="stylesheet" href="{{ asset('css/select2/select2-bootstrap4.min.css') }}">
  <!-- Theme style -->
  <link href="{{ asset('css/adminlte.min.css') }}" rel="stylesheet">
  {{-- date picker --}}
  <link href="{{ asset('bootstrap-datepicker/bootstrap-datepicker.min.css') }}" rel="stylesheet">
  <!-- DataTables -->
  <link rel="stylesheet" href="{{ asset('jquery.json-editor/jquery.json-viewer.css') }}">
  <link rel="stylesheet" href="{{ asset('css/icheck-bootstrap.min.css') }}">
  <!-- Toastr -->
  <link rel="stylesheet" href="{{ asset('toastr/toastr.min.css') }}">
  <!-- style -->
  <link href="{{ asset('css/style.css') }}" rel="stylesheet">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">

  @yield('css')
  @stack('css')
</head>

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed @guest layout-top-nav @endguest accent-olive">
  <!-- Site wrapper -->
  <div class="wrapper">
    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
      <!-- Left navbar links -->
      <ul class="navbar-nav">
        <li class="nav-item">
          @auth
          <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
          @endauth
        </li>
        <li class="nav-item d-none d-sm-inline-block">
          <a class="navbar-brand" href="{{ url('/') }}">
            {{ config('app.name') }}
          </a>
        </li>
      </ul>

      @auth
      <!-- SEARCH FORM -->
      {{-- <form class="form-inline ml-3">
        <div class="input-group input-group-sm">
          <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
          <div class="input-group-append">
            <button class="btn btn-navbar" type="submit">
              <i class="fas fa-search"></i>
            </button>
          </div>
        </div>
      </form> --}}
      @endauth

      <!-- Right navbar links -->
      <ul class="navbar-nav ml-auto">
        @guest
        <li class="nav-item">
          <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
        </li>
        @else

        <!-- Notifications Dropdown Menu -->
        <li class="nav-item dropdown" data-notifications-menu>
          <a class="nav-link" data-toggle="dropdown" href="#">
            <i class="far fa-bell"></i>
          <span class="badge badge-warning navbar-badge" id="count-n">{{Auth::user()->unreadNotifications->count()}}</span>
          </a>
          <div class="dropdown-menu dropdown-menu-xl dropdown-menu-right notification-menu">
            <span class="dropdown-item dropdown-header n-header">{{ __("Notifications") }}</span>
            <span class="n-read-all">
              <a href="javascrip:void(0);" id="n-read-all">{{ __("Read all") }}</a>
            </span>
            <div data-notifications-body>
              <div data-notifications></div>
              <div class="loader-box">
                <div class="dropdown-divider"></div>
                <div class="dropdown-item loader">
                  <div class="overlay d-flex justify-content-center align-items-center">
                    <i class="fas fa-sync-alt fa-spin"></i>
                  </div>
                </div>
              </div>
            </div>
            <div class="dropdown-divider"></div>
            {{-- <a href="#" class="dropdown-item dropdown-footer">{{__('See All Notifications')}}</a> --}}
          </div>
        </li>
        <li class="nav-item">
          <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#">
            <i class="fas fa-th-large"></i>
          </a>
        </li>
        <li class="nav-item">
        </li>
        @endguest
      </ul>
    </nav>
    <!-- Main Sidebar Container -->
    
    @auth
    <aside class="main-sidebar elevation-4 sidebar-light-olive">
  
      <!-- Sidebar -->
      <div class="sidebar">
        <!-- Sidebar user (optional) -->
        <div class="user-panel pt-2 pb-3 mb-3 d-flex">
          <div class="image">
            <img src="{{ asset('images/user.svg') }}" class="img-circle" alt="User Image">
          </div>
          <div class="info">
            <a href="#" class="d-block">{{ Auth::user()->name }}</a>
          </div>
        </div>
  
        <!-- Sidebar Menu -->
        <nav class="mt-2">
          <ul class="nav nav-pills nav-sidebar flex-column nav-child-indent" data-widget="treeview" role="menu" data-accordion="false">
            <!-- Add icons to the links using the .nav-icon class
                 with font-awesome or any other icon font library -->
            <li class="nav-item has-treeview">
              <a href="{{ route('__') }}" class="nav-link" data-link="__">
                <i class="nav-icon fas fa-tachometer-alt"></i>
                <p>
                  Dashboard
                </p>
              </a>
            </li>
            @can('show users')
            <li class="nav-item">
              <a href="#" class="nav-link" data-link="users.all">
                <i class="nav-icon fas fa-users"></i>
                <p>
                  {{__('Manage users')}}
                  <i class="fas fa-angle-left right"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="{{ route('users.index') }}" class="nav-link" data-link="users.index">
                    <i class="far fa-circle nav-icon"></i>
                    <p>{{__('List')}}</p>
                  </a>
                </li>
                @can('add users')
                <li class="nav-item">
                    <a href="{{ route('users.create') }}" class="nav-link" data-link="users.create">
                      <i class="far fa-circle nav-icon"></i>
                      <p>{{__('Create')}}</p>
                    </a>
                  </li>
                  @endcan
              </ul>
            </li>
            @endcan
            @can('show categories')
            <li class="nav-item">
              <a href="#" class="nav-link" data-link="categories.all">
                <i class="nav-icon fas fa-stream"></i>
                <p>
                  {{__('Manage Categories')}}
                  <i class="fas fa-angle-left right"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="{{ route('categories.index') }}" class="nav-link" data-link="categories.index">
                    <i class="far fa-circle nav-icon"></i>
                    <p>{{__('List')}}</p>
                  </a>
                </li>
                @can('add categories')
                <li class="nav-item">
                    <a href="{{ route('categories.create') }}" class="nav-link" data-link="categories.create">
                      <i class="far fa-circle nav-icon"></i>
                      <p>{{__('Create')}}</p>
                    </a>
                  </li>
                @endcan
              </ul>
            </li>
            @endcan
            @can('show products')
            <li class="nav-item">
              <a href="#" class="nav-link" data-link="products.all">
                <i class="nav-icon fas fa-coffee"></i>
                <p>
                  {{__('Manage Products')}}
                  <i class="fas fa-angle-left right"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="{{ route('products.index') }}" class="nav-link" data-link="products.index">
                    <i class="far fa-circle nav-icon"></i>
                    <p>{{__('List')}}</p>
                  </a>
                </li>
                @can('add products')
                <li class="nav-item">
                    <a href="{{ route('products.create') }}" class="nav-link" data-link="products.create">
                      <i class="far fa-circle nav-icon"></i>
                      <p>{{__('Create')}}</p>
                    </a>
                  </li>
                @endcan
              </ul>
            </li>
            @endcan
            @can('show stores')
            <li class="nav-item">
              <a href="#" class="nav-link" data-link="stores.all">
                <i class="nav-icon fas fa-store"></i>
                <p>
                  {{__('Manage Stores branch')}}
                  <i class="fas fa-angle-left right"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="{{ route('stores.index') }}" class="nav-link" data-link="stores.index">
                    <i class="far fa-circle nav-icon"></i>
                    <p>{{__('List')}}</p>
                  </a>
                </li>
                @can('add stores')
                <li class="nav-item">
                    <a href="{{ route('stores.create') }}" class="nav-link" data-link="stores.create">
                      <i class="far fa-circle nav-icon"></i>
                      <p>{{__('Create')}}</p>
                    </a>
                  </li>
                @endcan
              </ul>
            </li>
            @endcan
            @can('show orders')
            <li class="nav-item has-treeview">
                <a href="{{ route('orders.index') }}" class="nav-link" data-link="orders.index">
                  <i class="nav-icon fas fa-edit"></i>
                  <p>
                    {{__('Orders')}}
                  </p>
                  <i class="fas fa-angle-left right"></i>
                </a>
                <ul class="nav nav-treeview">
                  <li class="nav-item">
                    <a href="{{ route('orders.index') }}" class="nav-link" data-link="orders.index">
                      <i class="far fa-circle nav-icon"></i>
                      <p>{{__('List')}}</p>
                    </a>
                  </li>
                  @can('add orders')
                  <li class="nav-item">
                      <a href="{{ route('orders.create') }}" class="nav-link" data-link="orders.create">
                        <i class="far fa-circle nav-icon"></i>
                        <p>{{__('Create')}}</p>
                      </a>
                    </li>
                  @endcan
                </ul>
            </li>
            @endcan
            @can('show sliders')
            <li class="nav-item has-treeview">
                <a href="{{ route('sliders.index') }}" class="nav-link" data-link="sliders.index">
                  <i class="nav-icon fas fa-images"></i>
                  <p>
                    {{__('Slider')}}
                  </p>
                </a>
            </li>
            @endcan
            @can('show admins')
            <li class="nav-item has-treeview">
                <a href="{{ route('admins.index') }}" class="nav-link" data-link="admins.index">
                  <i class="nav-icon fas fa-users-cog"></i>
                  <p>
                    {{__('Manage admins')}}
                  </p>
                  <i class="fas fa-angle-left right"></i>
                </a>
                <ul class="nav nav-treeview">
                  <li class="nav-item">
                    <a href="{{ route('admins.index') }}" class="nav-link" data-link="admins.index">
                      <i class="far fa-circle nav-icon"></i>
                      <p>{{__('List')}}</p>
                    </a>
                  </li>
                  @can('add admins')
                  <li class="nav-item">
                      <a href="{{ route('admins.create') }}" class="nav-link" data-link="admins.create">
                        <i class="far fa-circle nav-icon"></i>
                        <p>{{__('Create')}}</p>
                      </a>
                    </li>
                  @endcan
                </ul>
            </li>
            @endcan
            @can('show roles')
            <li class="nav-item has-treeview">
              <a href="{{ route('roles.index') }}" class="nav-link" data-link="roles.index">
                <i class="nav-icon fas fa-sitemap"></i>
                <p>
                  {{__('Manage roles')}}
                </p>
                <i class="fas fa-angle-left right"></i>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="{{ route('roles.index') }}" class="nav-link" data-link="roles.index">
                    <i class="far fa-circle nav-icon"></i>
                    <p>{{__('List')}}</p>
                  </a>
                </li>
                @can('add roles')
                <li class="nav-item">
                    <a href="{{ route('roles.create') }}" class="nav-link" data-link="roles.create">
                      <i class="far fa-circle nav-icon"></i>
                      <p>{{__('Create')}}</p>
                    </a>
                  </li>
                @endcan
              </ul>
          </li>
          @endcan
            {{-- <li class="nav-header">MULTI LEVEL EXAMPLE</li>
            <li class="nav-item">
              <a href="#" class="nav-link">
                <i class="fas fa-circle nav-icon"></i>
                <p>Level 1</p>
              </a>
            </li>
            <li class="nav-item has-treeview">
              <a href="#" class="nav-link">
                <i class="nav-icon fas fa-circle"></i>
                <p>
                  Level 1
                  <i class="right fas fa-angle-left"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="#" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Level 2</p>
                  </a>
                </li>
                <li class="nav-item has-treeview">
                  <a href="#" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>
                      Level 2
                      <i class="right fas fa-angle-left"></i>
                    </p>
                  </a>
                  <ul class="nav nav-treeview">
                    <li class="nav-item">
                      <a href="#" class="nav-link">
                        <i class="far fa-dot-circle nav-icon"></i>
                        <p>Level 3</p>
                      </a>
                    </li>
                    <li class="nav-item">
                      <a href="#" class="nav-link">
                        <i class="far fa-dot-circle nav-icon"></i>
                        <p>Level 3</p>
                      </a>
                    </li>
                    <li class="nav-item">
                      <a href="#" class="nav-link">
                        <i class="far fa-dot-circle nav-icon"></i>
                        <p>Level 3</p>
                      </a>
                    </li>
                  </ul>
                </li>
                <li class="nav-item">
                  <a href="#" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Level 2</p>
                  </a>
                </li>
              </ul>
            </li>
            <li class="nav-item">
              <a href="#" class="nav-link">
                <i class="fas fa-circle nav-icon"></i>
                <p>Level 1</p>
              </a>
            </li> --}}
          </ul>
        </nav>
        <!-- /.sidebar-menu -->
      </div>
      <!-- /.sidebar -->
    </aside>
    @endauth
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      @auth
      <!-- Content Header (Page header) -->
      <section class="content-header">
        <div class="container-fluid">
            @yield('breadcrumb')
        </div><!-- /.container-fluid -->
      </section>
      @endauth

      <!-- Main content -->
      <section class="content">
        <div class="container-fluid">
          @yield('content')
        </div>
      </section>
       <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <footer class="main-footer">
      <div class="float-right d-none d-sm-block">
        <b>Version</b> 0.0.1
      </div>
      <strong>Copyright &copy; 2020 <a href="#">Capital Coffee</a>.</strong> All rights
      reserved.
    </footer>
    {{-- confirm modal --}}
    <div class="modal fade" id="modal-confirm-common" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Xác nhận!</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              
            </div>
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
              <button type="button" class="btn btn-primary ok">Đồng ý</button>
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>

    {{-- err modal --}}
    <div class="modal fade" id="modal-error-common">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Lỗi</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            
          </div>
          <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-danger ok">Đóng</button>
          </div>
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>
      <!-- /.modal -->
    @auth
    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-light card" style="overflow: auto">
      <ul class="nav flex-column">
        <li class="nav-item p-3">
          <select class="form-control" data-language="1">
            <option value="vi" @if(app()->getLocale() == 'vi') selected @endif>Vi</option>
            <option value="en" @if(app()->getLocale() == 'en') selected @endif>En</option>
          </select>
        </li>
        <li class="nav-item p-3">
          <form class="form-inline form_confirm_action" action="{{ route('logout') }}" method="POST" data-message="{{__('Would you like to end this login session?')}}">
            @csrf
            <button type="submit" class="btn btn-block btn-warning">
              {{ __('Logout') }}
              <i class="fas fa-sign-out-alt"></i>
            </button>
          </form>
        </li>
      </ul>
    </aside>
    <!-- /.control-sidebar -->
    @endauth
  </div>
  <script>
    var user_id = '{{ Auth::id() }}';
  </script>
  <!-- ./wrapper -->
  <!-- jQuery -->
  <script src="{{ asset('js/jquery/jquery.min.js') }}"></script>
  <!-- Bootstrap 4 -->
  <script src="{{ asset('js/bootstrap/bootstrap.bundle.min.js') }}"></script>
  <!-- Select2 -->
  <script src="{{ asset('js/select2/select2.full.min.js') }}"></script>
  <!-- AdminLTE App -->
  <script src="{{ asset('js/adminlte.min.js') }}"></script>
  {{-- pusher --}}
  <script src="https://js.pusher.com/6.0/pusher.min.js"></script>
  {{-- datepicker --}}
  <script type="text/javascript" src="/moment/moment_vn.min.js"></script>
  <script src="{{ asset('bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
  <script src="{{ asset('bootstrap-datepicker/bootstrap-datepicker.vi.min.js') }}" charset="UTF-8"></script>
  <!-- JSON Editor -->
  <script type="text/javascript" src="/jquery.json-editor/jquery.json-editor.min.js"></script>
  <script type="text/javascript" src="/jquery.json-editor/jquery.json-viewer.js"></script>
  <!-- Toastr -->
  <script src="{{ asset('toastr/toastr.min.js') }}"></script>
  <!-- commont js -->
  <script src="{{ asset('js/echo.js') }}"></script>
  <script src="{{ asset('js/common.js') }}"></script>
  <script src="{{ asset('js/notification.js') }}"></script>
  {{-- notification --}}
  @if (session('success'))
    <script>
      toasts("success","{{ session('success') }}","{{__('Notifications')}}");
    </script>
  @endif
  @if (session('error'))
    <script>
      toasts("danger","{{ session('error') }}","{{__('Notifications')}}");
    </script>
  @endif
  {{-- import script option --}}
  @stack('scripts')
  @yield('scripts')

<!-- AdminLTE for demo purposes -->
{{-- <script src="{{ asset('js/demo.js') }}"></script> --}}
</body>
</html>