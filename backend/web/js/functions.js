
function showAppointmentList(mis_id, cabinet_id) {
    setCookie('cabinet_id', cabinet_id, 3);
    addPreloader();
    let container = $('#appointment_list');
    $.ajax({
        url: '/ajax/get-appointment-list',
        type: 'POST',
        data: { mis_id: mis_id, cabinet_id: cabinet_id},
        success: function (res) {
            if(res.result == 1 && res.html.length) {
                container.html(res.html)
                removePreloader()
            }
        },
        error: function () {
            console.log('Error!');
        }
    });
}

/*
function submitTicketForm() {
    addPreloader();
    let form = $('#form-ticket');
    let data = form.serialize();
    $.ajax({
        url: '/ajax/submit-ticket-form',
        type: 'POST',
        data: data,
        success: function (res) {
            if(res.result == 1 && res.data) {
                $('#ticketModal').modal('hide');
                displaySuccessMessage('Талон ' + res.message + ' успешно создан')
                showAppointmentList(res.data.clinic_id, res.data.room, res.data.cabinet)
            }
            else if(res.message != null) {
                displayErrorMessage(res.message)
            }
            removePreloader()
        },
        error: function () {
            console.log('Error!');
            removePreloader();
        }
    });
}
*/

function setActiveCabinet() {
    let cabinet_id = getCookie('cabinet_id');
    if(cabinet_id.length) {
        $('.clinic_row[data-cabinet_id="' + cabinet_id + '"]').trigger('click')
    }

}

function displayAlertModal(subject, href, replaceTitle = false) {
    if(!subject.length && !href.length) return false;
    if(replaceTitle) {
        $('#alert-subject-title').text('')
    }
    else {
        $('#alert-subject').text('')
    }
    $('#alert-confirm-btn').attr('href', '')

    if(replaceTitle) {
        $('#alert-subject-title').text(subject)
    }
    else {
        $('#alert-subject').text(subject)
    }
    $('#alert-confirm-btn').attr('href', href)
    $('#alertModal').modal('show')
}

function addPreloader() {
    $('.loader-block').addClass('loader');
    setTimeout(function() {
        removePreloader();
    }, 10000);
}
function removePreloader() {
    $('.loader-block').removeClass('loader');
}

function displaySuccessMessage(message) {
    toastr.success(message)
}
function displayErrorMessage(message) {
    toastr.error(message)
}

function setCookie(cname, cvalue, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000 * 1000));
    var expires = "expires=" + d.toGMTString();
    document.cookie = cname + "=" + cvalue + "; " + expires;
}

function getCookie(cname) {
    var name = cname + "=";
    var ca = document.cookie.split(';');
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i].trim();
        if (c.indexOf(name) == 0) return c.substring(name.length, c.length);
    }
    return "";
}


function initPlugins() {
    $(".select-time").inputmask({"mask": "99:99"});
    $(".phone-mask").inputmask({"mask": "+7 (999) 999-99-99"});
    $('.date-picker').datepicker({
        todayHighlight: true,
        clearBtn: true,
        format: 'dd.mm.yyyy',
        language: 'ru',
    })
}

function CallPrint() {
    var prtContent = document.getElementById('pos-receipt');
    var prtCSS = '<link rel="stylesheet" href="/css/ticket_style.css" type="text/css" />';
    var WinPrint = window.open('','','left=50,top=50,width=800,height=640,toolbar=0,scrollbars=1,status=0');
    WinPrint.document.write('<div id="print" class="contentpane">');
    WinPrint.document.write(prtCSS);
    WinPrint.document.write(prtContent.innerHTML);
    WinPrint.document.write('</div>');
    WinPrint.document.close();
    WinPrint.focus();
    WinPrint.print();
    //WinPrint.close();
}
