/**
 * Created by alex on 03.12.13.
 */
/**
 * Created with JetBrains PhpStorm.
 * User: artem
 * Date: 13.09.13
 * Time: 9:56
 * To change this template use File | Settings | File Templates.
 */

var j = jQuery.noConflict();

j(document).ready(function() {

    //j("#call_back_phone").mask("+7(999)9999999");

    j('#call_back_link').click(function(event) {
        j('body').css('overflow','hidden');
        event.preventDefault();
        j('#substratre_call_back').show();
        j('#loading_call_back').slideToggle("slow");
        //class="modal hide fade in" aria-hidden="false" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" style="display: block;"
        j('#loading_call_back').addClass('in');
        j('#loading_call_back').attr('aria-hidden',false);
        j('#loading_call_back').css('display','block');
        j('#loading_call_back').focus().trigger('shown');
    });

    j('.close').click(function(event){
        event.preventDefault();
        j('#loading_call_back').hide();
        j('#substratre_call_back').hide();
        j('#call_back_form').validationEngine('hideAll');
        j('body').css('overflow','auto');
    });

    if ( j('#call_back_form').length ){
        j("#call_back_form").validationEngine({
            ajaxFormValidation: true,
            onAjaxFormComplete: ajaxValidationCallback,
            scroll: false
        });
    }

    function ajaxValidationCallback(status, form, json, options){
        if (status === true) {
            j('.close').click();
            alert("Спасибо, наши менеджеры обязательно вам перезвонят!");
        }
    }

    j('#call_back_form_btn').click(function(event){
        j('body').css('overflow','hidden');
        j("#call_back_form").validationEngine();
        j('#call_back_form').submit();
        j("#call_back_form").validationEngine("updatePromptsPosition");
    });

    j('#substratre_call_back').css('overflow','hidden');
});