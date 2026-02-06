/**
 * Pimpinan Settings - Media Upload Handler
 */
(function($) {
    'use strict';

    $(document).ready(function() {
        var mediaFrame;

        // Upload photo button
        $('#upload-foto-btn').on('click', function(e) {
            e.preventDefault();

            if (mediaFrame) {
                mediaFrame.open();
                return;
            }

            mediaFrame = wp.media({
                title: 'Pilih Foto Pimpinan',
                button: {
                    text: 'Gunakan Foto Ini'
                },
                multiple: false,
                library: {
                    type: 'image'
                }
            });

            mediaFrame.on('select', function() {
                var attachment = mediaFrame.state().get('selection').first().toJSON();

                $('#foto_id').val(attachment.id);

                var imgUrl = attachment.sizes && attachment.sizes.medium
                    ? attachment.sizes.medium.url
                    : attachment.url;

                $('#foto-preview-img').attr('src', imgUrl);
                $('#foto-preview').show();
                $('#foto-placeholder').hide();
                $('#remove-foto-btn').show();
            });

            mediaFrame.open();
        });

        // Remove photo button
        $('#remove-foto-btn').on('click', function(e) {
            e.preventDefault();

            $('#foto_id').val('');
            $('#foto-preview-img').attr('src', '');
            $('#foto-preview').hide();
            $('#foto-placeholder').show();
            $(this).hide();
        });
    });

})(jQuery);
