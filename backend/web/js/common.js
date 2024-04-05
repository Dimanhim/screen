$(document).ready(function() {
    $('body').on('change', '#ad-type', function(e) {
        e.preventDefault();
        displayMediaContent();
    });

    $('body').on('click', '.btn-delete-file', function(e) {
        e.preventDefault();
        if(!confirm('Вы действительно хотите удалить файл?')) return false;
        let self = $(this);
        let url = self.attr('href');
        $.get(url, function(json) {
            if(json.result) {
                self.parents('.media-content-item').remove();
                removeMediaContentBlock();
            }
        })
    });

    if (!$('#page-alias').val()) {
        $(".page-name").keyup(function() {
            $('#page-alias').val(slugify($(this).val()));
        });
    }

    if (!$('#page-h1').val()) {
        $(".page-name").keyup(function() {
            $('#page-h1').val($(this).val());
        });
    }

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
        if($(this).hasClass('active')) {
            $(this).removeClass('active');
            toggleTicketRow();
            return false;
        }
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
        e.preventDefault();
        let self = $(this);
        $('#formticket-time_start').val(self.attr('data-time_start'))
        $('#formticket-time_end').val(self.attr('data-time_end'))
        $('#formticket-room').val(self.attr('data-room'))
        $('#formticket-clinic_id').val(self.attr('data-clinic_id'))
        $('#formticket-doctor_id').val(self.attr('data-doctor_id'))
        $('#room-name').html(' в кабинет <b>' + self.attr('data-room') + '</b> на время <b>' + self.attr('data-time') + '</b>')

        initPlugins();
        $('#ticketModal').modal('show')
    });

    $(document).on('click', '#form-ticket button', function(e) {
        e.preventDefault();
        submitTicketForm();
    });

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
})
