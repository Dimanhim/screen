$(document).ready(function() {

    let interval = 10 * 1000;

    function updateMain() {
        setTimeout(function() {
            let section = $('#wrapper');
            let id = section.attr('data-id');
            $.ajax({
                url: '/cabinet/update-ajax',
                type: 'GET',
                data: {id: id},
                success: function (response) {
                    section.replaceWith(response);
                    console.log('updateAjax')
                    updateMain();
                },
                error: function (e) {
                    console.log('Error!', e);
                    window.location.reload()
                }
            });
            /*$.get('/cabinet/update-ajax', {id: id}, function(response) {
                section.replaceWith(response);
                console.log('updateAjax')
                updateMain();
            });*/
        }, interval);
    }
    updateMain();
})
