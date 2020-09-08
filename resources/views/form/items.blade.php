@if (count($items) > 0)
@php
  $thisTableItemId = Str::random(10);
@endphp
<table class="table table-bordered table-hover" id="{{$thisTableItemId}}">
  <thead>
    <tr>
      <th>
        @if ($fieldName)
            <input type="checkbox" class="items_all">
            <input type="hidden" name="{{$fieldName}}" id="value">
        @endif
      </th>
      @foreach ($items[0] as $key => $value)
        @if ($key !== 'id')
          <th>{{ $key }}</th>            
        @endif
      @endforeach
    </tr>
  </thead>
  <tbody>
    @foreach ($items as $item)
    <tr class="@if (in_array($item['id'], $data)) table-primary @endif">
        @foreach ($item as $key => $value)
        <td>
          @if ($key !== 'id')
            {{ $value }}
          @else
          <input type="checkbox" name="id[]" value="{{$value}}" @if (in_array($value, $data)) checked @endif @if(!$fieldName) disabled @endif>
          @endif
        </td>
        @endforeach
    </tr>
    @endforeach
  </tbody>
</table>
@push('scripts')
<script>
	$(function () {
    var table = $("#{{$thisTableItemId}}");
    // Handle click on "Select all" control
    table.find('.items_all').on('click', function(){
        // Check/uncheck checkboxes for all rows in the table
        $('input[type="checkbox"]', table).prop('checked', this.checked).trigger("change");
    });
    @if($fieldName)
    table.find('tbody td').on('click', function(e){
        if (e.target !== this)
          return;
        var row = $(this).closest( "tr" );
        var checkbox = $('input[type="checkbox"]', row);
        // Check/uncheck checkboxes for all rows in the table
        checkbox.prop('checked', !checkbox[0].checked).trigger("change");
    });
    @endif

   table.on("change", "tbody input[type='checkbox']", function(){
      var row = $(this).closest( "tr" );
      if (this.checked) {
        row.addClass("table-primary");
      } else {
        row.removeClass("table-primary");
        var el = $('.items_all', table).get(0);
        // If "Select all" control is checked and has 'indeterminate' property
        if(el && el.checked && ('indeterminate' in el)){
          // Set visual state of "Select all" control
          // as 'indeterminate'
          el.indeterminate = true;
        }
      }

      buildValue();
    });

    function buildValue(){
      var value = {};
      $("tbody input[type='checkbox']", table).each(function() {
        if(this.checked) {
          value[this.value] = this.value;
        }
      });
      if($.isEmptyObject(value)){
        value = [];
      }
      $("#value", table).val(JSON.stringify(value));
    }

    buildValue();
  });
</script>
@endpush
@else
No Item
@endif
