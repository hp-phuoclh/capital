$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
var iniPreview = [];
var iniPreviewConfig = [];
var previews = [];
if(url_upload_review){
    $.ajax({
        type: 'POST',
        url: url_upload_review,
        async: false, 
        success: function (data) {
            previews = data;
            $.each(previews, function(index, image){
                var config = {
                    key: image.id,
                    url: '/file/delete',
                    extra: [image.path]
                }
                iniPreview.push(image.url);
                iniPreviewConfig.push(config);
            });
        }
    });
}

$("#input-res-2").on('change', function(event) {
    countCurrent = $(this)[0].files.length; 
});

$("#input-res-2").fileinput({
    allowedFileExtensions: ["jpg", "png", "gif", "jpeg"],
    language: document.documentElement.lang,
    allowedFileTypes: ['image'],
    showUpload: false, // hide upload button
    showUploadStats: false,
    showRemove: true,
    maxFileCount: 5,
    autoOrientImage: false,
    theme: 'fa',
    showCaption: false,
    initialPreview: iniPreview,
    initialPreviewFileType: 'image',
    overwriteInitial: false,
    initialPreviewAsData: true,
    initialPreviewConfig: iniPreviewConfig,
    uploadUrl: 'no-link',
    uploadAsync: false,
}).on('filebatchselected', function(event, files) {
    _files = files;
    if(previews.length + countCurrent > 5) {
        return false;
    }
    $('.kv-file-upload').remove();
}).on('filecleared', function(event) {
    _files = {};
}).on('filedeleted', function(event, key, jqXHR, data) {
    if(data){
        _delete_files.push(data[0]);
    }
});



