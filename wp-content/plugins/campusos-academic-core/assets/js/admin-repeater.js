(function($) {
    'use strict';
    $(document).on('click', '.unpatti-repeater-add', function(e) {
        e.preventDefault();
        var fieldId = $(this).data('field');
        var container = $(this).closest('.unpatti-repeater');
        var template = container.find('.unpatti-repeater-template').html();
        var index = container.find('.unpatti-repeater-rows .unpatti-repeater-row').length;
        template = template.replace(/__INDEX__/g, index);
        container.find('.unpatti-repeater-rows').append(template);
    });
    $(document).on('click', '.unpatti-repeater-remove', function(e) {
        e.preventDefault();
        $(this).closest('.unpatti-repeater-row').remove();
    });
})(jQuery);
