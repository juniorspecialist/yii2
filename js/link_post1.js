var jq = jQuery.noConflict();
jq(document).ready(function() {
    jq(".ditto_sort").click(function() {
        var link_id = jQuery(this).attr('id'); 		
        var href_ = jQuery("#"+link_id).attr("custom_src");
        jQuery('#ditto-container').load(href_+' #for-reload', function() {
            connect_events1();
        });
        return false;
    });
});
