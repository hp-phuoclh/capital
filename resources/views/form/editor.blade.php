<div class="mb-3">
<textarea class="textarea" id="editor{{ $fieldName }}" name="{{$fieldName}}" style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;">{{ old($fieldName, $fieldData) }}</textarea>
</div>
@push('css')
<!-- summernote -->
<link rel="stylesheet" href="{{ asset('css/summernote/summernote-bs4.css') }}">
@endpush
@push('scripts')
<!-- Summernote -->
<script src="{{ asset('js/summernote/summernote-bs4.min.js') }}"></script>
<script>
	$(function () {
        $('#editor{{ $fieldName }}').summernote({
            placeholder: '{{ $placeholder ?? "" }}'
        })
    });
</script>
@endpush