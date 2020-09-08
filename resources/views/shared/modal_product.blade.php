<div class="modal fade" id="{{$model_id ?? "modal_product_search"}}" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-xl modal-dialog-centered">
    <div class="modal-content">
      <div class="overlay d-none justify-content-center align-items-center" id="modal_product_loader">
        <i class="fas fa-2x fa-sync fa-spin"></i>
      </div>
      <div class="modal-header">
        <h4 class="modal-title">{{__('Products')}}</h4>
        <a href="{{route('products.create')}}" target="_blank" class="mt-2 ml-4">
          <span>{{__('Create product')}}</span>
        </a>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="modal_product_body">

      </div>
      <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal">{{__('Close')}}</button>
        <button type="button" class="btn bg-gradient-warning" id="reload_products">{{__('Reload')}}</button>
        <button type="button" class="btn btn-primary" id="choose_products">{{__('Choose')}}</button>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
@push('css')
<!-- DataTables -->
<link rel="stylesheet" href="{{ asset('css/datatables-bs4/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('css/datatables-bs4/dataTables.checkboxes.css') }}">
<link rel="stylesheet" href="{{ asset('css/datatables-bs4/select.bootstrap4.min.css') }}">
@endpush
@push('scripts')
<!-- DataTables -->
<script src="{{ asset('js/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('js/datatables-bs4/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('js/datatables/dataTables.select.min.js') }}"></script>
<script src="{{ asset('js/datatables-bs4/dataTables.checkboxes.min.js') }}"></script>
<script>
  var __products = [], // data choose 
   __ids = [], // data choose 
   __table; // datatable
  $(function () {
    var modal_product = $("#{{$model_id ?? 'modal_product_search'}}");
    modal_product.on('show.bs.modal', function (e) {
      get_all_product();
    })
    $(modal_product).on('choosed', function(e, products, ids) {});
    $('#reload_products').on('click', function() {
      get_all_product();
    })

    $('#choose_products').on('click', function() {
        var rows_selected = __table.column(0).checkboxes.selected();
        if(json_products && rows_selected.length > 0){
          $.each(rows_selected, function(index, rowId){
            __products.push(json_products[rowId]);
            __ids.push(rowId);
          });
        }
        $("#modal_product_body").html('');
        modal_product.modal('hide');
        $(modal_product).trigger('choosed', {products:__products, ids:__ids});
    })
  });

  function get_all_product(callBack){
    var modal_body = $("#modal_product_body"),
        modal_product_loader = $("#modal_product_loader");
    modal_product_loader.toggleClass(['d-none', 'd-flex']);
    $.ajax(
    {
      url: '/products/templates/all',
      type: "get",
    })
    .done(function (data) {
      modal_body.html(data);
      // clear data choose 
      __products = [];
        __ids = [];
        __table = $("#modal_product_body table").DataTable({
            'columnDefs': [
              {
                  'targets': 0,
                  'checkboxes': {
                    'selectRow': true
                  }
              }
            ],
            select: {
                style: true
            },
            @if(app()->getLocale() == 'vi')
            language: {
                "url": "/js/datatables-bs4/Vietnamese-product.json"
            },
            @endif
            'order': [[2, 'asc']],
            scrollY: '52vh',
            "sScrollX": '100%',
            "sScrollXInner": "100%",
            scrollCollapse: true,
        });
        setTimeout(function(){
          $(window).trigger('resize');
         }, 150);
      if (typeof callBack == 'function'){
        callBack();
      }
      modal_product_loader.toggleClass(['d-none', 'd-flex']);
    })
    .fail(function (jqXHR, ajaxOptions, thrownError) {
      // alert('server not responding...');
      modal_product_loader.toggleClass(['d-none', 'd-flex']);
    });
  }
</script>
@endpush