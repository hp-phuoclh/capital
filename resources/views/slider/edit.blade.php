<form class="form-horizontal" action="{{ route('sliders.update', ['slider' => $slider->id]) }}" method="POST" enctype="multipart/form-data">
  <div class="modal-content" id="content_slider_edit">
    <div class="modal-header">
      <h4 class="modal-title">{{ __('Update image') }}</h4>
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">×</span>
      </button>
    </div>
    <div class="modal-body">
      @csrf
      @method('PUT')
      <div class="form-group">
        <label for="title" class="col-sm-2 col-form-label">{{ __('Title') }}</label>
        <div class="col-sm-10">
          <input type="text" class="form-control" id="title" name="title_edit" value="{{old('title_edit', $slider->title)}}">
        </div>
      </div>
      <div class="form-group">
        <label for="title" class="col-sm-2 col-form-label">{{ __('Image') }}</label>
        <div class="col-sm-10">
          @include('shared.upload', ['url_upload_review' => route('slider.load_image', ['id'=> $slider->id]),
          'inputname' => 'image_edit' ])
          <input type="hidden" name="file_delete" value="{{ old('file_delete') }}">
          <div class="offset-2 col-sm-10">
            <p class="text-danger" id="error_image"></p>
          </div>
        </div>
      </div>
      <div class="form-group">
        <label for="order" class="col-sm-2 col-form-label">{{ __('Order ') }}</label>
        <div class="col-sm-10">
          <input type="text" class="form-control" id="order" name="order_edit" value="{{old('order_edit', $slider->order)}}">
        </div>
      </div>
      <div class="form-group">
        <label for="url" class="col-sm-2 col-form-label">{{ __('Link') }}</label>
        <div class="col-sm-10">
          <input type="text" class="form-control" id="url" name="url_edit" value="{{old('url_edit', $slider->id)}}">
        </div>
      </div>
    </div>
    <div class="modal-footer justify-content-between">
      <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('Close') }}</button>
      <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>
    </div>
  </div>
</form>