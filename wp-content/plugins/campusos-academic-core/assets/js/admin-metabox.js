(function($) {
    'use strict';
    // Show/hide meta boxes based on page template
    function toggleMetaBoxes() {
        var template = $('#page_template').val() || '';
        $('.campusos-mb').each(function() {
            var expected = $(this).data('template');
            if (template === expected) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    }
    $(document).ready(function() {
        toggleMetaBoxes();
        $(document).on('change', '#page_template', toggleMetaBoxes);
    });
})(jQuery);
