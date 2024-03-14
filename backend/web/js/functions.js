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
