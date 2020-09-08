$(document).ready(function(){

    $('#size').select2({
        theme: 'bootstrap4',
        tags: true,
    });

    $("#frm_edit").on("submit", function(event){
        var form = $(this);

        event.preventDefault();
        $('.messageSave').remove();
        $(".pd-error").remove();
        var url = form.attr('action');

        var data = new FormData(this);
        $.each( _files, function( key, fileObj ) {
            data.append("files[]", (fileObj.file ? fileObj.file : fileObj));
        });
        $.each( _delete_files, function( key, name ) {
            data.append("file_deletes[]", name);
        });
        
        $.ajax({
            type: "POST",
            url: url,
            data: data,
            processData: false,
            contentType: false,
            success: function(product) {
                var message = "<p>Lưu thành công sản phẩm: "+ product.name+"</p>";
                $('#modal-confirm-common').modal('show');
                $('#modal-confirm-common .modal-body').html(message);
                $('#modal-confirm-common').find("button.ok").off('click').on("click", function(){
                    location.reload();
                });
            },
            error: function(err) {
                if( err.status === 422 ) {
                    var errors = $.parseJSON(err.responseText);
                    var firstErr = true;
                    $.each(errors.errors, function (key, value) {
                        if(firstErr){
                            $("[name='"+key+"']").focus();
                            firstErr = false;
                        }
                    
                        var div = $("[name='"+key+"']").parent().parent();
                        var err_elm = $("<div>").addClass("offset-2 col-sm-10");
                        if($.isPlainObject(value)) {
                            $.each(value, function (key, value) {    
                                var p = $("<div>").addClass("text-danger pd-error");         
                                err_elm.append(p.text(value));
                                return;
                            });
                        }else{
                            var p = $("<div>").addClass("text-danger pd-error");         
                            err_elm.append(p.text(value));
                        }
                        div.append(err_elm);
                        div.find(".form-control").addClass("is-invalid");
                    });

                }
                form.removeClass('isClick');
                }
        });
    });
});