displayMediaContent();
function displayMediaContent() {
    $('.media-content').css('display', 'none');
    $('#ad-type input').each(function (index, element) {
        let el = $(element);
        if(el.is(':checked')) {
            if(el.val() == 1) {
                $('.video-content').css('display', 'block');
            }
            else if(el.val() == 2) {
                $('.audio-content').css('display', 'block');
            }
        }
    });
}
function removeMediaContentBlock() {
    if(!$('.media-content-block .media-content-item').length) {
        $('.media-content-block').remove();
    }
}

function showAppointmentList(clinic_id, mis_id) {
    console.log('list', clinic_id + ' ' + mis_id)
    toggleTicketRow()
    addPreloader();
    let container = $('#appointment_list');
    $.ajax({
        url: '/admin/ajax/get-appointment-list',
        type: 'POST',
        data: {clinic_id: clinic_id, mis_id: mis_id},
        success: function (res) {
            console.log('res list', res)
            if(res.result == 1 && res.html.length) {
                container.html(res.html)
            }
            removePreloader()
        },
        error: function () {
            console.log('Error!');
            removePreloader();
        }
    });
    removePreloader()
}

function submitTicketForm() {
    addPreloader();
    let form = $('#form-ticket');
    let data = form.serialize();
    $.ajax({
        url: '/admin/ajax/submit-ticket-form',
        type: 'POST',
        data: data,
        success: function (res) {
            console.log('res', res)
            console.log('res data', res.data)
            if(res.result == 1 && res.data) {
                console.log('change', res.data)
                $('#ticketModal .modal-body').html('<h4 style="text-align: center;">Визит успешно добавлен</h4>')
                showAppointmentList(res.data.clinic_id, res.data.room)
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

function toggleTicketRow() {
    if($('.clinic_row.active').length) {
        $('.cabinet-list-row').removeClass('col-md-12').addClass('col-md-4');
        $('.ticket-list-row').addClass('active')
    }
    else {
        $('.cabinet-list-row').removeClass('col-md-4').addClass('col-md-12');
        $('.ticket-list-row').removeClass('active')
    }

}


function displayAlertModal(subject, href) {
    if(!subject.length && !href.length) return false;
    $('#alert-subject').text('')
    $('#alert-confirm-btn').attr('href', '')

    $('#alert-subject').text(subject)
    $('#alert-confirm-btn').attr('href', href)
    $('#alertModal').modal('show')
}

function addPreloader() {
    setTimeout(function() {
        $('.loader-block').addClass('loader');
    }, 500);
}
function removePreloader() {
    setTimeout(function() {
        $('.loader-block').removeClass('loader');
    }, 500)
}

function displaySuccessMessage(message) {
    $('.info-message').text(message).fadeIn();
    setTimeout(function() {
        $('.info-message').text('').fadeOut();
    }, 5000)
}
function displayErrorMessage(message) {
    $('.info-message').addClass('error').text(message).fadeIn();
    setTimeout(function() {
        $('.info-message').text('').fadeOut();
    }, 5000)
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
