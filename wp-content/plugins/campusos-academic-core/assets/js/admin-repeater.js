(function($) {
    'use strict';
    $(document).on('click', '.campusos-repeater-add', function(e) {
        e.preventDefault();
        var fieldId = $(this).data('field');
        var container = $(this).closest('.campusos-repeater');
        var template = container.find('.campusos-repeater-template').html();
        var index = container.find('.campusos-repeater-rows .campusos-repeater-row').length;
        template = template.replace(/__INDEX__/g, index);
        container.find('.campusos-repeater-rows').append(template);
    });
    $(document).on('click', '.campusos-repeater-remove', function(e) {
        e.preventDefault();
        $(this).closest('.campusos-repeater-row').remove();
    });
})(jQuery);
