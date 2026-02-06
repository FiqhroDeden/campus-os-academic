(function($) {
    'use strict';

    $(document).on('click', '.unpatti-upload-btn', function(e) {
        e.preventDefault();
        var btn = $(this);
        var targetId = btn.data('target');
        var isFile = btn.data('type') === 'file';
        var frame = wp.media({
            title: isFile ? 'Pilih File' : 'Pilih Gambar',
            multiple: false,
            library: isFile ? {} : { type: 'image' }
        });
        frame.on('select', function() {
            var attachment = frame.state().get('selection').first().toJSON();
            $('#' + targetId).val(attachment.id);
            if (isFile) {
                $('#' + targetId + '_name').text(attachment.filename);
            } else {
                $('#' + targetId + '_preview').attr('src', attachment.url).show();
            }
            btn.siblings('.unpatti-remove-btn').show();
        });
        frame.open();
    });

    $(document).on('click', '.unpatti-remove-btn', function(e) {
        e.preventDefault();
        var targetId = $(this).data('target');
        $('#' + targetId).val('');
        $('#' + targetId + '_preview').attr('src', '').hide();
        $('#' + targetId + '_name').text('');
        $(this).hide();
    });
})(jQuery);
