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
})
