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

    addPreloader();
    let container = $('#appointment_list');
    $.ajax({
        url: '/admin/ajax/get-appointment-list',
        type: 'POST',
        data: {clinic_id: clinic_id, mis_id: mis_id},
        success: function (res) {
            console.log('res', res);
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
    $('.loader-block').removeClass('loader');
}
