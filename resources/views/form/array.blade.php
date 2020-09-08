@php
    $fieldData = $fieldData ?? '[]';
@endphp
@if ($fieldName)
    <input type="text" class="form-control @error($fieldName) is-invalid @enderror" name="{{$fieldName}}">
    <input type="hidden" class="array-type" name="{{$fieldName}}" value="{{ old($fieldName, $fieldData) }}">
@else
 {{ $fieldData }}
@endif


@push('scripts')
<script>
    $(function () {
        $(".array-type").each(function(){
            var input = $(this);
            var arrayValue = input.val();
            arrayValue = JSON.parse(arrayValue);
            input.prev().val(arrayValue.join(','));
            input.prev().off("change").on("change", function(){
                var arrayValue = $(this).val();
                arrayValue = arrayValue.split(",");
                input.val(JSON.stringify(arrayValue));
            });
        });
       
	});
</script>
@endpush