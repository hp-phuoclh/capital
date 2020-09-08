
  @if ($items && $stores) 
  <button type="button" class="btn btn-danger mb-2" id="add_row">
    <i class="fas fa-plus"></i>
  </button>
  <button type="button" class="btn btn-warning mb-2" id="delete_row">
    <i class="fas fa-minus"></i>
  </button>
  <div class="table-responsive">
      <table class="table table-sm table-bordered" id="table_stores">
          <thead>
            <tr>
              <th scope="col">Stores</th>
              <th scope="col">items</th>
            </tr>
          </thead>
          <tbody>
            @if(Route::currentRouteName() == 'promotions.create')
            <tr>
                <th scope="row">
                    <select class="form-control select2bs4 col-6" name="store_id" onchange="changeItems(this)">
                        <option value="">----</option>
                        @foreach ($stores as $store)
                          <option value="{{ $store->id }}">{{ $store->name }}</option>
                        @endforeach
                    </select>
                </th>
                  <td> 
                      <select class="form-control items" multiple="multiple">
                          {{-- @foreach ($items as $item)
                              <option value="{{ $item->id }}">{{ $item->name }}</option>
                          @endforeach --}}
                      </select>
                  </td>
                </tr>
            @else 
            @if(!empty($stores_selected) )
              @foreach($stores_selected as $key => $value) 
              <tr>
              <th scope="row">
                  <select class="form-control select2bs4 col-6" name="store_id">
                      <option value="">----</option>
                      @foreach ($stores as $store)
                        <option value="{{ $store->id }}" {{ $key == $store->name ? 'selected' : '' }}>{{ $store->name }}</option>
                      @endforeach
                  </select>
                {{-- {{ $key }} --}}
              </th>
                <td>
                    <select class="form-control items" multiple="multiple">
                        @foreach ($items as $item)
                          @foreach($value as $keyItem => $valueItem) 
                            @if($valueItem == $item->name)
                              <option value="{{ $item->id }}" selected>{{ $valueItem}}</option>
                            @endif
                          @endforeach
                          {{-- <option value="{{ $item->id }}">{{ $item->name }}</option> --}}
                        @endforeach
                    </select>
                </td>
              </tr>
              @endforeach
            @endif
            @endif
          </tbody>
        </table>
        <input type="hidden" value="{{ route('promotions.items') }}" id="url_get_items" />
        <input type="hidden" name="stores" id="stores" />
    </div>
  @endif
@push('scripts')
<script type="text/javascript">
  $(function () {
      $('select').select2();
      
  });

      // Change option select 2
    function changeItems(data) {
          var tr = $(data).parent().parent();
          console.log(tr);
          // console.log($(data).find('option:selected').val());
          var url_get_items = $('#url_get_items').val();
          $.ajaxSetup({
              headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              }
          });
          $.ajax({
              type: 'POST',
              url: url_get_items,
              data: {
                store_id_selected: $(data).find('option:selected').val(),
              },
              success: function (result) {
                var html = "";
                $.each(result, function( key, value ) {
                  $.each(value,function(keySub, valueSub) {
                    html += `<option value='${valueSub.id}'>${valueSub.name}</option>`; 
                  });  
                });
                html += "";
                 tr.find(".items").append(html);
              },
              error: function(err) {
                  console.log(err);
              }
          });
    }

  $("#add_row").on("click", function () {
        var html_row = "<tr>"
                + "<th>"
                + "<select class='form-control select2bs4 col-6' name='store_id' onchange='changeItems(this)'>"
                + "<option value=''>----</option>"
                + "@foreach ($stores as $store)"
                + "<option value='{{ $store->id }}'>{{ $store->name }}</option>"
                + "@endforeach"
                + "</select>"
                + "</th>"
                + "<td>"
                + "<select class='form-control items' multiple='multiple'>"
                + "</select>"
                + "</td>"
                + "</tr>";
          // $('.form-control.items').attr('multiple','multiple');
          $("#table_stores > tbody").append(html_row);
          $('select').select2();
      });
      $("#delete_row").on("click", function () { 
        $("#table_stores tbody tr").last().remove();
      });
    $("#bt_submit").click(function(){
        var json_items = {};
        var json_stores = {};
        var arr_stores = {};
        var arr_items = [];
        $("#table_stores tbody tr").each(function(index,value){
          json_items = {};
          $(this).find("td select").each(function(index1,value1){
            $(this).select2("data").forEach(function(item){
              json_items[item.id] = item.id;
            });
          });
          if($(this).find("th select").val()) {
            json_stores[$(this).find("th select").val()] = json_items;
          }
        })
        // console.log(JSON.stringify(json_stores));
        $("#stores").val(JSON.stringify(json_stores));
      })
</script>
@endpush
