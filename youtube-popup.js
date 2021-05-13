(function ($, root, undefined) {
    $('blockquote.youtube-link, a.youtube-link, p.youtube-link').on('click', function() {
        let classnames = $(this).attr('class');
        let exploded_classes = classnames.split(' ');
        //console.log(exploded_classes);
        $.each(exploded_classes, function(i, val) {
            if( val.search('youtube-popup-') ) {
                // Not found in this val's.
            } else {
                $('#' + val).show();
                let complete_width = $('.youtube-popup-layer').height() / 2;
                $('.youtube-popup-layer').css('margin-top', -complete_width);
                $('body').click(function(e) {
                    console.log(e.target);
                    if ($(e.target).parents('blockquote.youtube-link').hasClass('youtube-link') || $(e.target).hasClass('youtube-link')) {
                        return false;
                    } else {
                        let get_popup_content = $('#' + val).html();
                        $('#' + val).remove();
                        $('.entry-content').append('<div style="display: none;" id="' + val + '" class="youtube-popup-layer">' + get_popup_content + '</div>');
                    }
                })
            }
        });
    });

})(jQuery, this);