/**
 * Created with JetBrains PhpStorm.
 * User: artem
 * Date: 09.10.13
 * Time: 11:04
 * To change this template use File | Settings | File Templates.
 */
var j = jQuery.noConflict();

j(document).ready(function() {

    j('div.clickme').click(function(event) {

        j('body').css('overflow','hidden');

        event.preventDefault();
        j('#substratre_soc_btn').show();
        j('#loading_soc_btn').slideToggle("slow");
        j('#loading_soc_btn').addClass('in');
        j('#loading_soc_btn').attr('aria-hidden',false);
        j('#loading_soc_btn').css('display','block');
        j('#loading_soc_btn').focus().trigger('shown');
    });

    j('.close').click(function(event){
        event.preventDefault();
        j('#loading_soc_btn').hide();
        j('#substratre_soc_btn').hide();
        j('body').css('overflow','auto');
    });
});
