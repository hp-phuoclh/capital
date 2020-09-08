$(function () {
  // acctive menu
  var route = $("#active_nav").val();
  if (route) {
    route = route.replace(".edit", ".all");
    route = route.replace(".show", ".index");
    var link = $(".sidebar").find("a[data-link='" + route + "']");
    link.addClass("active");
    // link.attr("href", "javascrip:void(0);");
    var lis = link.parents('li');
    lis.each(function () {
      var li = $(this);
      li.addClass("menu-open");
      li.find("a:first").addClass('active');
    });
  }
  // select lang
  $('[data-language]').on('change', function () {
    location.assign('?lang=' + $(this).val());
    return false;
  });
  // notification
  $(".dropdown-menu .loader, .dropdown-menu .n-header, .dropdown-menu .n-read-all").click(function (e) {
    e.stopPropagation();
  });
  // load notify
  var page = 1;
  loadNotifications(page);
  $('[data-notifications-body]').scroll(function () {
    if ($('[data-notifications-body]').scrollTop() + $('[data-notifications-body]').height() >= $('[data-notifications]').height()) {
      if(flg_load) return;
      flg_load = true;
      page++;
      loadNotifications(page);
    }
  });
  $("#n-read-all").click(readAllNotification);
});

// confirm form submit
var hasConfirm = false;
$(".form_confirm_action").on('submit', function () {
  if (!hasConfirm) {
    hasConfirm = true;
    var form = $(this);
    var message = '<p>Are you sure you want to perform this action?</p>';
    $('#modal-confirm-common').modal('show');
    if (form.data("message") != undefined && form.data("message").length > 0) {
      message = form.data("message");
    }
    $('#modal-confirm-common .modal-body').html(message);
    $('#modal-confirm-common').find("button.ok").off('click').on("click", function () {
      form.submit();
      $("body").append($('<div class="modal-backdrop fade show backdrop-up-modal"></div>'));
    });
    return false;
  }
});

$('#modal-confirm-common').off('hidden.bs.modal').on('hidden.bs.modal', function (e) {
  hasConfirm = false;
});

//Initialize Select2 Elements
$('.select2bs4').select2({
  theme: 'bootstrap4'
});

$(function () {
  $('[data-toggle="tooltip"]').tooltip();
});

//Date picker
$('.input-datepicker').datepicker({
  autoclose: true,
  format: 'dd/mm/yyyy',
  language: '{{ app()->getLocale() }}'
});


function format_number(num) {
  num = num.replace(/[^0-9]/g, '');
  num = Math.round(num * 100) / 100;
  num = num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1.');
  return num == 0 ? '' : num;
}


function change_price(input) {
  var parent = $(input).parent();
  var value = format_number(input.value);
  var first_length = input.value.length;
  var lash_length = value.length;
  var position = input.selectionStart;

  input.value = value;
  parent.find("input[name='" + $(input).data('name') + "']").val(value.replace(/[^0-9]/g, ''));
  input.selectionEnd = first_length < lash_length ? position + 1 : position;
};

$('body').on('input', '.price-format', function(){
  change_price(this);
});
$('.price-format').trigger('input');
// for (i = 0; i < priceFormats.length; i++) {
//   priceFormats[i].addEventListener("input", change_price);
//   change_price(priceFormats[i]);
// }

function add_backdrop() {
  $("body").append($('<div class="modal-backdrop fade show backdrop-up-modal"></div>'));
}

function remove_backdrop() {
  $("body").find('.modal-backdrop').remove();
}

function myAjax(data, url, method) {
  var csrfToken = $("[name=csrf-token]").attr("content");
  var message;
  $.ajax({
    headers: {
      'X-CSRF-Token': csrfToken
    },
    url: url,
    method: method,
    data: data,
    async: false,
    success: function (result) {
      message = result;
    },
    error: function (error) {
      if (error.status === 422) {
        var errors = $.parseJSON(err.responseText);
        var errString = [];
        $.each(errors.errors, function (key, value) {
          if ($.isPlainObject(value)) {
            $.each(value, function (key, value) {
              errString.push(value);
            });
          } else {
            errString.push(value);
          }
          alert(errString.join(', '));
        });
      } else {
        alert(error.responseJSON.message);
      }
    }
  });
  return message;
}

var flg_load = false;
function loadNotifications(page) {
  var box = $("[data-notifications-menu]");
  var boxContent = $("[data-notifications]");
  var loader = box.find('.loader-box')
  if (loader.length == 0) return;
  $.ajax(
    {
      url: '/notifications?page=' + page,
      type: "get",
    })
    .done(function (data) {
      flg_load = false;
      if (data == "") {
        loader.remove();
        return;
      }
      boxContent.append(data);
    })
    .fail(function (jqXHR, ajaxOptions, thrownError) {
      // alert('server not responding...');
    });
}

function readAllNotification() {
  $("#count-n").text("0");
  $("[data-notifications]").find('a.n-unread').removeClass('n-unread');
  $.ajax(
    {
      headers: {
        'X-CSRF-Token': $("[name=csrf-token]").attr("content")
      },
      url: '/read_all_notification',
      type: "post",
    })
    .done(function (data) {
      //
    })
    .fail(function (jqXHR, ajaxOptions, thrownError) {
      // alert('server not responding...');
    });
}



// common-----------
// format date YYYY-MM-DD to DD/MM/YYYY
function format_date(str) {
  if (!str) {
      return '';
  }
  var array_date = str.split('-');
  var y = array_date[0];
  var m = array_date[1];
  var d = array_date[2];

  var year = y;
  var month = ('00' + m).slice(-2);
  var day = ('00' + d).slice(-2);

  var date = day +'/'+month+'/'+year;
  return(date);
}

// format date DD/MM/YYYY to YYYY-MM-DD
function format_date_2(str) {
  if (!str) {
      return '';
  }
  var array_date = str.split('/');
  var d = array_date[0];
  var y = array_date[2];
  var m = array_date[1];

  var year = y;
  var month = ('00' + m).slice(-2);
  var day = ('00' + d).slice(-2);

  var date = year +'-'+month+'-'+day;
  return(date);
}

// format currency
function add_dot_currency(str) {
  var num = new String(str).replace(/\./g, "");
  while(num != (num = num.replace(/^(-?\d+)(\d{3})/, "$1.$2")));

  if(num == 'null'){
  num='';
  }

  return num;
}

// del dot currency
function del_dot_currency(str) {
  var num = new String(str).replace(/\./g, "");
  return num;
}

/**
 * 
 * @param {string} type [success, info, warning, error]
 * @param {string} body 
 * @param {string} title 
 * @param {string} subtitle 
 */
function toasts(type, body, title, subtitle){
  type = type == 'error' ? 'danger' : type;
  type = 'bg-'+type;
  $(document).Toasts('create', {
    class: type ?? '', 
    title: title ?? '',
    subtitle: subtitle ?? '',
    body: body ?? '',
    autohide: true,
    delay: 5000,
  })
}