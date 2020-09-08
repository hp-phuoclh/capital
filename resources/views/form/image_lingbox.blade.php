@if ($files && count($files->images) > 0)
<div class="card">
  <div class="card-header">
    <span>{{ $fieldTitle }}</span>
    <div class="card-tools">
      <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
      </button>
    </div>
  </div>
  <!-- /.card-header -->
  <div class="card-body">
        <div class="row">
            @foreach ($files->images as $image)
          <div class="col-sm-2 mt-2">
              <a href="{{ Helper::getUrlImage($image) }}" data-toggle="lightbox" data-gallery="gallery">
                  <img src="{{ Helper::getUrlImage($image) }}" class="img-fluid" alt="image">
                </a>
          </div>
    @endforeach
  </div>
</div>
  <!-- /.card-body -->
</div>
@endif
@section('css')
  <!-- Ekko Lightbox -->
  <link href="{{ asset('css/ekko-lightbox/ekko-lightbox.css') }}" rel="stylesheet">
@endsection
@push('scripts')
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
      })
    </script>
@endpush