// Enable pusher logging - don't include this in production
Pusher.logToConsole = true;

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: '66191fe071ce5457cc6d',
    cluster: 'ap1',
    encrypted: true,
    logToConsole: true
});

Echo.private('App.Models.Admin.'+ user_id)
.notification((notification) => {
    var count = parseInt($("#count-n").text().trim());
    $("#count-n").text(count+1);
    getTemplateNotify(notification.id);
});

function getTemplateNotify(id) {
  var boxContent = $("[data-notifications]");
  $.ajax(
    {
        url: '/notify/' + id + '?new=1',
        type: "get",
    })
    .done(function(data)
    {
        if(data == ""){
            return;
        }
        boxContent.prepend(data);
    })
    .fail(function(jqXHR, ajaxOptions, thrownError)
    {
        //   alert('server not responding...');
    });
}