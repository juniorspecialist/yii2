jQuery(document).ready(function() {

    jQuery.getScript("js/jquery.the-modal.js");
    jQuery.getScript("js/jquery.validationEngine.js");

    jQuery(".ditto_sort").click(function() {
        var link_id = $(this).id; 
		//alert(link_id);
        var href_ = jQuery("#"+link_id).attr("href");
        jQuery('#ditto-container').load(href_+' #for-reload', function(response, status, xhr) {
		//alert("response="+response+"|status="+status);
            connect_events();
        });
        return false;
    });
});
