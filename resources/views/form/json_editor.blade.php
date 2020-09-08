
@if ($data) 
  <textarea class="form-control" rows="3" name="{{ $dataTitle }}" placeholder="Enter stores">{{ $data }}</textarea>
  <pre name="{{ $dataTitle }}"></pre>
  <div class="alert alert-danger" id="alert_err_{{ $dataTitle }}" style="display:none;" role="alert">
    This is a danger alert—check it out!
  </div>
<input type="hidden" name="format_{{ $dataTitle }}" value="{{ $data }}" />
  <button type="button" name="{{ $dataTitle }}" class="btn btn-info">
      <i class="fas fa-sync"></i>&nbsp;format
  </button>
@endif
@push('scripts')
<!-- date-range-picker -->
<script type="text/javascript">
    function getJson(data) {
      try {
          return JSON.parse(data);
      } catch (ex) {
          alert('Wrong JSON Format: ' + ex);
      }
    }

  $(function(){
      var json_data_display = $('textarea[name="'+'{{ $dataTitle }}'+'"]').val();
      var format_value_data = $('input[name="format_'+'{{ $dataTitle }}'+'"]').val();
      var editor_data = new JsonEditor('pre[name="'+'{{ $dataTitle }}'+'"]',getJson(json_data_display));
      // get json
        editor_data.load(getJson(json_data_display));
        $('pre[name="'+'{{ $dataTitle }}'+'"]').on('keyup',function(){
          try {
            $('#alert_err_'+'{{ $dataTitle }}'+'').css('display','none');
            $(':input[type="submit"]').prop('disabled', false);
            $('button[name="'+'{{ $dataTitle }}'+'"]').click(function(){
              editor_data.load(editor_data.get());
            });
              $('textarea[name="'+'{{ $dataTitle }}'+'"]').val(JSON.stringify(editor_data.get()));
          } catch (ex) {
            $(':input[type="submit"]').prop('disabled', true);
            $('#alert_err_'+'{{ $dataTitle }}'+'').css('display','inherit');
            $('#alert_err_'+'{{ $dataTitle }}'+'').text('Wrong JSON Format: ' + ex);
            console.log(ex);
          }
        })
    // });
  });
</script>
@endpush