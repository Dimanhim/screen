$(document).ready(function() {

    $(document).on('click', '.cabinet-view-tooltip', function(e) {
        return false;
    });
    $('.cabinet-view-tooltip').tooltip({
        toggle: 'tooltip',
        placement: 'bottom',
        title: 'tooltip on right',
        trigger: 'click',
        html: true,
        //template: '<div class="tooltip" role="tooltip"><div class="arrow"></div><div class="tooltip-inner"><a href="https">Ссылка</a></div></div>'

    })
    $(document).on('click', '.clinic_row', function(e) {
        e.preventDefault();
        $('.clinic_row').removeClass('active')
        $(this).addClass('active');
        $('#appointment_list').html()
        let clinic_id = $(this).attr('data-clinic');
        let mis_id = $(this).attr('data-mis_id');
        let cabinet_id = $(this).attr('data-cabinet_id');
        showAppointmentList(clinic_id, mis_id, cabinet_id)
    });
    $(document).on('click', '.alert-modal', function(e) {
        e.preventDefault();
        let subject = $(this).attr('data-confirm-subject')
        let href = $(this).attr('href')
        displayAlertModal(subject, href)
    });
    $(document).on('click', '.get_ticket_js', function(e) {
        //if(!confirm('Вы действительно хотите добавить талон?')) return false;
        let href = $(this).attr('href');
        displayAlertModal('Вы действительно хотите добавить талон?', href, true)
        return false;
    });

    /*$(document).on('click', '#form-ticket button', function(e) {
        e.preventDefault();
        submitTicketForm();
    });*/

    $(document).on('click', '.ticket-action-print', function(e) {
        e.preventDefault();
        let room = $(this).attr('data-room');
        let ticket = $(this).attr('data-ticket');
        if(room.length && ticket.length) {
            $('.js_print_room').html(room)
            $('.js_print_ticket').html(ticket)
            CallPrint()
        }
    });

    setActiveCabinet()
})
