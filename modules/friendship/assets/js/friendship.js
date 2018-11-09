(function ($) {

    $.request('onRefresh', {
        success: function(data) {
            this.success(data);
        }
    });

}) (jQuery);