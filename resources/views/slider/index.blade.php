@extends('layouts.app')

@section('breadcrumb')
<div class="row mb-2">
  <div class="col-sm-6">
    <h1> {{ __('Slider') }}</h1>
  </div>
  <div class="col-sm-6">
    <ol class="breadcrumb float-sm-right">
      <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
      <li class="breadcrumb-item">{{ __('Slider') }}</li>
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
        <div class="card-tools">
          @can('add sliders')
          <button type="button" class="btn btn-success float-right" data-toggle="modal" data-target="#modal-create"><i
              class="fas fa-plus"></i> {{ __('Add Image') }}</button>
          @endcan
          <button type="button" class="btn btn-info mr-4" data-toggle="modal" data-target="#modal-review"><i
              class="fas fa-eye"></i> {{ __('Review') }}</button>
        </div>
      </div>
      <!-- /.card-header -->
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-striped">
            <thead>
              <tr>
                <th>#</th>
                <th>{{ __('Image') }}</th>
                <th>{{ __('Title') }}</th>
                <th>{{ __('Order ') }}</th>
                <th>{{ __('Link') }}</th>
                <th>{{ __('Action') }}</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($sliders as $slider)
              <tr>
                <td>{{ $slider->id }}</td>
                <td>
                  <a href="{{ asset($slider->image_url) }}" data-toggle="lightbox" data-title="{{ $slider->title }}">
                    <img src="{{ asset($slider->image_url) }}" class="img-fit img-fit-sm" alt="slider" width="40">
                  </a>
                </td>
                <td>{{ $slider->title }}</td>
                <td>{{ $slider->order }}</td>
                <td>{{ $slider->url }}</td>
                <td class="text-right text-nowrap">
                  @can('edit sliders')
                  <button class="btn btn-info btn-sm text-white " data-toggle="modal" data-target="#modal-edit"
                    onclick="detail('{{$slider->id}}')">
                    <i class="fas fa-pencil-alt"></i>
                  </button>
                  @endcan
                  @can('delete sliders')
                  <form class="d-inline form_confirm_action"
                    action="{{ route('sliders.destroy', ['slider'=> $slider->id]) }}" method="POST"
                    data-message=""{{__('Do you want to delete ')}}{{ $slider->title }}?">
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
  <div class="modal fade" data-backdrop="static" data-keyboard="false" id="modal-create" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
      <form class="form-horizontal" action="{{ route('sliders.store') }}" method="POST" enctype="multipart/form-data" id="frm_create">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">{{ __('Create image') }}</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body">
            @csrf
            <div class="form-group">
              <label for="title" class="col-sm-2 col-form-label">{{ __('Title') }}</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" id="title" name="title" value="{{old('title')}}">
              </div>
            </div>
            <div class="form-group">
              <label for="title" class="col-sm-2 col-form-label">{{ __('Image') }}</label>
              <div class="col-sm-10">
                @include('shared.upload', ['url_upload_review' => '', 'inputname' => 'image' ])
              </div>
              <div class="offset-2 col-sm-10">
                <p class="text-danger" id="error_image_create"></p>
              </div>
            </div>
            <div class="form-group">
              <label for="order" class="col-sm-2 col-form-label">{{ __('Order ') }}</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" id="order" name="order" value="{{old('order')}}">
              </div>
            </div>
            <div class="form-group">
              <label for="url" class="col-sm-2 col-form-label">{{ __('Link') }}</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" id="url" name="url" value="{{old('url')}}">
              </div>
            </div>
          </div>
          <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('Close') }}</button>
            <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>
          </div>
        </div>
      </form>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <div class="modal fade" data-backdrop="static" data-keyboard="false" id="modal-edit" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg" id="frm_edit">
      @error('image_edit')
      @include('slider.edit')
      @enderror
    </div>
    <!-- /.modal-dialog -->
  </div>
  <div class="modal fade" id="modal-review" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">{{ __('Privew Slider') }}</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">

          <div class="col-sm-12">
            <div id="review_slider" class="carousel slide" data-ride="carousel">
              <ol class="carousel-indicators">
                @foreach ($sliders as $key => $slider)
                <li data-target="#review_slider" data-slide-to="{{$key}}" @if($loop->first)class="active"@endif></li>
                @endforeach
              </ol>
              <div class="carousel-inner">
                @foreach ($sliders as $key => $slider)
                <div class="carousel-item @if($loop->first) active @endif">
                  <a href="{{$slider->url ?? '#'}}">
                    <img class="d-block w-100 img-fit img-fit-lg" src="{{$slider->image_url}}"
                      alt="{{$slider->title}}">
                  </a>
                </div>
                @endforeach
              </div>
              <a class="carousel-control-prev" href="#review_slider" role="button" data-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
              </a>
              <a class="carousel-control-next" href="#review_slider" role="button" data-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
              </a>
            </div>
          </div>
        </div>
        {{-- <div class="modal-footer justify-content-between">
          <span></span>
            <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('Close') }}</button>
        </div> --}}
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
</div>
@endsection
@section('scripts')
<!-- Ekko Lightbox -->
<script src="{{ asset('js/ekko-lightbox/ekko-lightbox.min.js') }}"></script>
<script>
  $(function () {
    $(document).on('click', '[data-toggle="lightbox"]', function(event) {
      event.preventDefault();
      $(this).ekkoLightbox({
        alwaysShowClose: true
      });
    });

    @error('image_edit')
    $("#modal-edit").modal('show');
    initModalEdit(false, '{{ $message }}');
    @enderror

    @error('image')
    $("#modal-create").modal('show');
    $('#error_image_create').text('{{ $message }}');
    @enderror
  })
  function detail(id) {
    var data = {};
    var result = myAjax(data,"sliders/"+id+"/edit","get");
    $("#frm_edit").html(result);
    initModalEdit(true, '');

  }

  function initModalEdit(loadinitImage, message){
    $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    var iniPreview = [];
    var iniPreviewConfig = [];
    var previews = [];
    if($("#frm_edit").find("[url_upload_review]").length > 0 && loadinitImage){
        var url_upload_review = $("#frm_edit").find("[url_upload_review]").attr('url_upload_review');
        $.ajax({
            type: 'POST',
            url: url_upload_review,
            async: false, 
            success: function (data) {
                previews = data;
                $.each(previews, function(index, image){
                    var config = {
                        key: image.id,
                        url: '/file/delete',
                        extra: [image.path]
                    }
                    iniPreview.push(image.url);
                    iniPreviewConfig.push(config);
                });
            }
        });
    }

    $("#frm_edit").find("#input-res-2").on('change', function(event) {
        countCurrent = $(this)[0].files.length; 
    });

    $("#frm_edit").find("#input-res-2").fileinput({
        allowedFileExtensions: ["jpg", "png", "gif", "jpeg"],
        language: document.documentElement.lang,
        allowedFileTypes: ['image'],
        showUpload: false, // hide upload button
        showUploadStats: false,
        showRemove: true,
        maxFileCount: 1,
        autoReplace: true,
        theme: 'fa',
        showCaption: false,
        initialPreview: iniPreview,
        initialPreviewFileType: 'image',
        maxFileSize: 2048,
        initialPreviewAsData: true,
        initialPreviewConfig: iniPreviewConfig,
        uploadUrl: 'no-link',
        uploadAsync: false,
    }).on('filebatchselected', function(event, files) {
        _files = files;
        $('.kv-file-upload').remove();
    }).on('filecleared', function(event) {
        _files = {};
    }).on('filedeleted', function(event, key, jqXHR, data) {
        if(data){
          $("input[name=file_delete]").val(data[0]);
        }
    });

   if(message) {
     $('#error_image').text(message);
   }
  }
</script>
@endsection