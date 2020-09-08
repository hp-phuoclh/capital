<div class="card" id="json-type-{{ $fieldTitle }}">
	<div class="card-header">
		<span>{{ $fieldTitle }}</span>
		<div class="card-tools">
			@if ($fieldName)
			<button type="button" class="addRow btn btn btn-sm btn-primary">Add {{ $fieldTitle }}</button>
			<div class="d-none" id="json_action_template">
				<button class="btn btn-danger btn-sm destroy">
					<i class="fas fa-trash"></i>
				</button>
			</div>
			@endif
			<button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
			</button>
		</div>
	</div>
	<!-- /.card-header -->
	<div class="card-body">
		<table class="table table-bordered table-hover">
			<thead>
				<tr>
					<th>Title</th>
					<th>Value</th>
					<th width="40"></th>
				</tr>
			</thead>
		</table>
		@php
		$fieldData = $fieldData ?? '{}';
		@endphp
		<input type="hidden" value="{{old($fieldName, $fieldData)}}" name="{{ $fieldName }}" id="json_type_value{{ $fieldTitle }}">
		@if ($errors->has($fieldName))
				<div class="alert alert-danger pd-error">
				{{ $errors->first($fieldName) }}
				</div>
		@endif
	</div>
	<!-- /.card-body -->
</div>


@push('css')
<!-- DataTables -->
<link rel="stylesheet" href="{{ asset('css/datatables-bs4/dataTables.bootstrap4.min.css') }}">
@endpush
@push('scripts')
<!-- DataTables -->
<script src="{{ asset('js/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('js/datatables-bs4/dataTables.bootstrap4.min.js') }}"></script>
<script>
	$(function () {
			var objJson = $("#json_type_value{{ $fieldTitle }}").val();

			// build data
			var data = [];
			objJson = JSON.parse(objJson);

			$.each(objJson, function( key, value ) {
				value = null == value ? '' : value;
				var row = {'title' : key, 'value' : value}
				data.push(row);
			});

			var table{{ $fieldTitle }} = $('#json-type-{{ $fieldTitle }} table').DataTable({
					"data" : data,
					"columns" : [
							{ "data" : "title" },
							{ "data" : "value" }
					],
					"paging": false,
					"lengthChange": false,
					"searching": false,
					"autoWidth": false,
					"bInfo" : false,
					"ordering": false,
					"rowCallback": function( row, data ) {
						if($(row).find('td').length < 3) {
							if($.isArray(data.value)) {
								$(row).attr("value-array", "true")
							}
							var td = $("<td>").addClass("action");
							td.append($("#json_action_template").html());
							$(row).append(td);
						}
					}
			});

			$('#json-type-{{ $fieldTitle }} .addRow').on( 'click', function () {
        		table{{ $fieldTitle }}.row.add( {'title' : "", 'value' : ""} ).draw( false );
			});
		
			$('#json-type-{{ $fieldTitle }} tbody').on( 'click', '.destroy', function () {
					table{{ $fieldTitle }}
							.row( $(this).parents('tr') )
							.remove()
							.draw();
					buildValue();
			});
			$('#json-type-{{ $fieldTitle }} tbody').on( 'click', 'td:not(.action)', function (e) {
					if (e.target !== this || $(this).hasClass("editing"))
						return;
					$(this).addClass("editing");
					var input = $("<input>").addClass('form-control');
					var cell = this;
					input.val($(cell).text().trim());
					$(cell).text('');
					$(cell).append(input);
					input.focus();
					input.on("focusout", function() {
						$(cell).text($(this).val());
						input.remove();
						$(cell).removeClass("editing");
						buildValue();
					});
			});
		});

	function buildValue(){
		var json_value = {};
		$('#json-type-{{ $fieldTitle }} tbody tr').each(function() {
			var title = $(this).find("td:first-child").text().trim();
			var value = $(this).find("td:nth-child(2)").text().trim();
			if ($(this).attr("value-array") == "true"){
				value = value.split(",");
			}
			json_value[title] = value;
		});

		$("#json_type_value{{ $fieldTitle }}").val(JSON.stringify(json_value));
	}
</script>
@endpush