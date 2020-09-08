<span class="btn btn-default btn-file" url_upload_review="{{ $url_upload_review ?? "" }}">
<input id="input-res-2" name="{{ $inputname ?? 'file_data' }}" type="file" {{ $multiple ?? '' }} accept="image/*"/>
</span>
@push('css')
<link href="{{ asset('libs/js/bootstrap-fileinput/css/all.min.css') }}" rel="stylesheet">
<link href="{{ asset('libs/js/bootstrap-fileinput/css/fileinput.min.css') }}" rel="stylesheet">
@endpush
@push('scripts')
<script>
    var error_message = "@lang('label.image_limit')";
    var _files = {};
    var _delete_files = [];
    var maxUpload = {{ $maxUpload ?? 5 }};
    var url_upload_review = '{{ $url_upload_review ?? "" }}';
    var countCurrent = {};
</script>
<script src="{{ asset('libs/js/bootstrap-fileinput/js/fileinput.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/5.0.3/js/plugins/sortable.min.js" type="text/javascript"></script>
@if(app()->getLocale() != 'en')
<script src="{{ asset('libs/js/bootstrap-fileinput/js/locales/'.app()->getLocale().'.js') }}"></script>
@endif
<script src="{{ asset('libs/js/bootstrap-fileinput/themes/fa/theme.min.js') }}"></script>
<script src="{{ asset('js/pages/shared/upload.js') }}"></script>
@endpush
