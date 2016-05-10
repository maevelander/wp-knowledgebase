jQuery(document).on( 'click', '.asset-enqueueing-notice .notice-dismiss', function() {

    jQuery.ajax({
        url: ajaxurl,
        data: {
            action: 'kbe_dismiss_asset_equeueing_notice'
        }
    })

})
