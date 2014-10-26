/**
 * The Modal jQuery plugin
 *
 * @author Alexander Makarov <sam@rmcreative.ru>
 * @link https://github.com/samdark/the-modal
 * @version 1.0
 */
;(function($, window, document, undefined) {
	"use strict";
	/*jshint smarttabs:true*/


	var pluginNamespace = 'the-modal',
		// global defaults
    	defaults = {
			overlayClass: 'themodal-overlay',

			closeOnEsc: true,
			closeOnOverlayClick: true,

			onClose: null,
			onOpen: null
        };

	function getContainer() {
		var container = 'body';

		// IE < 8
		if(document.all && !document.querySelector) {
			container = 'html';
		}

		return $(container);
	}

	function init(els, options) {
		var modalOptions = options;

		if(els.length) {
			els.each(function(){
				$(this).data(pluginNamespace+'.options', modalOptions);
			});
		}
		else {
			$.extend(defaults, modalOptions);
		}

		return {
			open: function(options) {
				var el = els.get(0);
				var localOptions = $.extend({}, defaults, $(el).data(pluginNamespace+'.options'), options);

				getContainer().addClass('lock');

				// close modal if opened
				if($('.'+localOptions.overlayClass).length) {
					$.modal().close();
				}

				var overlay = $('<div/>').addClass(localOptions.overlayClass).prependTo('body');
				overlay.data(pluginNamespace+'.options', options);

				if(el) {
					$(el).clone(true).appendTo(overlay).slideToggle("slow");
				}

				if(localOptions.closeOnEsc) {
					$(document).bind('keyup.'+pluginNamespace, function(e){
						if(e.keyCode === 27) {
							$.modal().close();
						}
					});
				}

				if(localOptions.closeOnOverlayClick) {
					overlay.children().on('click.' + pluginNamespace, function(e){
						e.stopPropagation();
					});
					$('.' + localOptions.overlayClass).on('click.' + pluginNamespace, function(e){
						$.modal().close();
					});
				}

				$(document).bind('touchmove.'+pluginNamespace,function(e){
					if(!$(e).parents('.' + localOptions.overlayClass)) {
						e.preventDefault();
					}
				});

				if(localOptions.onOpen) {
					localOptions.onOpen(overlay, localOptions);
				}
			},
			close: function() {
				var el = els.get(0);

				var localOptions = $.extend({}, defaults, options);
				var overlay = $('.' + localOptions.overlayClass);
				$.extend(localOptions, overlay.data(pluginNamespace+'.options'));

				overlay.remove();
				getContainer().removeClass('lock');

				if(localOptions.closeOnEsc) {
					$(document).unbind('keyup.'+pluginNamespace);
				}

				if(localOptions.onClose) {
					localOptions.onClose(overlay, localOptions);
				}
			}
		};
	}

	$.modal = function(options){
		return init($(), options);
	};

	$.fn.modal = function(options) {
		return init(this, options);
	};

})(jQuery, window, document);

$(function($){

        $('.themodal-overlay').css('overflow','hidden');
        $('.lock').css('overflow','hidden');


	// bind event handlers to modal triggers
    $('.trigger').bind('click', function(e){
		e.preventDefault();
        // получили ID ссылки, теперь вызываем ОКНО по ID ссылки
        var id = $(this).attr('id');
		$('#test-'+id).modal().open();
	});

	// attach modal close handler
	$('.modal .closemodal').on('click', function(e){
		e.preventDefault();
		$.modal().close();
		$('form.shopOrderForm').validationEngine('hideAll');
	});

	// below isn't important (demo-specific things)
	$('.modal .more-toggle').on('click', function(e){
		e.stopPropagation();
		$('.modal .more').toggle();
	});

    $('input[name="f_number"]').keyup(function() {
        var form = $(this).closest('div[id]');		
        var id_div = $(form).attr('id');

        var price = $('#'+id_div+' input[name="price"]').val();
        var number = $('#'+id_div+' input[name="f_number"]').val();

        $('#'+id_div+' .total').html(parseInt(price)*parseInt(number));
        $('#'+id_div+'  input[name="cost"]').val(parseInt(price)*parseInt(number));
    });
    $('input[name="f_number"]').keyup();
	
		if ( $('form.shopOrderForm').length ){
			$("form.shopOrderForm").validationEngine({
				ajaxFormValidation: true,
				onAjaxFormComplete: ajaxValidationCallback,
				scroll: false
			});
		}	
		
		function ajaxValidationCallback(status, form, json, options){
			if (status === true) {
				$('.closemodal').click();
				alert("Спасибо за заказ, наши менеджеры свяжутся с вами в кратчайшие сроки");
			}
		}		

});
function connect_events(){
    //alert('sdfsdf');


    jQuery.getScript("js/jquery.the-modal.js");
    jQuery.getScript("js/jquery.validationEngine.js");

    // bind event handlers to modal triggers
    jQuery('.trigger').click( function(e){
        e.preventDefault();
        // получили ID ссылки, теперь вызываем ОКНО по ID ссылки
        var id = jQuery(this).attr('id');
        jQuery('#test-'+id).modal().open();
    });

    jQuery('.modal .closemodal').click(function(e){
        e.preventDefault();
        jQuery.modal().close();
        //jQuery('form.shopOrderForm').validationEngine('hideAll');
    });

    // below isn't important (demo-specific things)
    jQuery('.modal .more-toggle').click(function(e){
        e.stopPropagation();
        jQuery('.modal .more').toggle();
    });

    jQuery('input[name="f_number"]').keyup(function() {
        var form = jQuery(this).closest('div[id]');
        var id_div = jQuery(form).attr('id');

        var price = jQuery('#'+id_div+' input[name="price"]').val();
        var number = jQuery('#'+id_div+' input[name="f_number"]').val();

        jQuery('#'+id_div+' .total').text(parseInt(price)*parseInt(number));
        jQuery('#'+id_div+'  input[name="cost"]').val(parseInt(price)*parseInt(number));
    });

    jQuery(".ditto_sort").click(function(e) {
        e.preventDefault();
        var link_id = $(this).id;
        //alert(link_id);
        var href_ = jQuery("#"+link_id).attr("href");
        jQuery('#ditto-container').load(href_+' #for-reload', function() {
            connect_events();
        });
        return false;
    });

    if ( jQuery('form.shopOrderForm').length ){
        jQuery("form.shopOrderForm").validationEngine({
            ajaxFormValidation: true,
            onAjaxFormComplete: ajaxValidationCallback,
   	    scroll: false
        });
    }

    function ajaxValidationCallback(status, form, json, options){
        if (status === true) {
            jQuery('.closemodal').click();
            alert("Спасибо за заказ, наши менеджеры свяжутся с вами в кратчайшие сроки");
        }
    }

}
function connect_events1(){
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

	jQuery('.trigger').click(function(e){
		e.preventDefault();
	        // получили ID ссылки, теперь вызываем ОКНО по ID ссылки
        	var id = jq(this).attr('id');
		jq ('#test-'+id).modal().open();
	});

	// attach modal close handler
	jQuery('.closemodal').click( function(e){
		e.preventDefault();
		//$.modal().close();
		jQuery.modal().close();
	});
    	// attach modal close handler
	jq('.modal .closemodal').click( function(e){
		e.preventDefault();
		jq.modal().close();
	});

	// below isn't important (demo-specific things)
	jq('.modal .more-toggle').click(function(e){
		e.stopPropagation();
		jq('.modal .more').toggle();
	});

	jq('input[name="f_number"]').keyup(function() {
		var form = jq(this).closest('div[id]');		
		var id_div = jq(form).attr('id');var jq = jQuery.noConflict();
		jq(document).ready(function() {
		    jq(document).click(".ditto_sort", function() {
			var link_id = jQuery(this).attr('id'); 		
			var href_ = jQuery("#"+link_id).attr("custom_src");
			jQuery('#ditto-container').load(href_+' #for-reload', function() {
			    connect_events1();
			});
			return false;
		    });
		});

		var price = jq('#'+id_div+' input[name="price"]').val();
		var number = jq('#'+id_div+' input[name="f_number"]').val();

		jq('#'+id_div+' .total').html(parseInt(price)*parseInt(number));
		jq('#'+id_div+'  input[name="cost"]').val(parseInt(price)*parseInt(number));
	});

	if ( jq('form.shopOrderForm').length ){
		jQuery("form.shopOrderForm").validationEngine({
		    ajaxFormValidation: true,
		    onAjaxFormComplete: ajaxValidationCallback,
                    scroll: false
		});
	}

	function ajaxValidationCallback(status, form, json, options){
		if (status === true) {
		    jQuery('.closemodal').click();
		    alert("Спасибо за заказ, наши менеджеры свяжутся с вами в кратчайшие сроки");
		}
	}

	});

/*
	// bind event handlers to modal triggers
    $('.trigger').bind('click', function(e){
		e.preventDefault();
        // получили ID ссылки, теперь вызываем ОКНО по ID ссылки
        var id = $(this).attr('id');
		$('#test-'+id).modal().open();
	});

	// attach modal close handler
	$('.modal .closemodal').on('click', function(e){
		e.preventDefault();
		$.modal().close();
	});

	// below isn't important (demo-specific things)
	$('.modal .more-toggle').on('click', function(e){
		e.stopPropagation();
		$('.modal .more').toggle();
	});

    $('input[name="f_number"]').keyup(function() {
        var form = $(this).closest('div[id]');		
        var id_div = $(form).attr('id');

        var price = $('#'+id_div+' input[name="price"]').val();
        var number = $('#'+id_div+' input[name="f_number"]').val();

        $('#'+id_div+' .total').html(parseInt(price)*parseInt(number));
        $('#'+id_div+'  input[name="cost"]').val(parseInt(price)*parseInt(number));
    });
	
		if ( $('form.shopOrderForm').length ){
			$("form.shopOrderForm").validationEngine({
				ajaxFormValidation: true,
				onAjaxFormComplete: ajaxValidationCallback
			});
		}	
		
		function ajaxValidationCallback(status, form, json, options){
			if (status === true) {
				$('.closemodal').click();
				alert("Спасибо за заказ, наши менеджеры свяжутся с вами в кратчайшие сроки");
			}
		}	

    //jQuery.getScript("js/jquery.the-modal.js");
    //jQuery.getScript("js/jquery.validationEngine.js");
/*
    // bind event handlers to modal triggers
    jQuery('.trigger').click( function(e){
        e.preventDefault();
        // получили ID ссылки, теперь вызываем ОКНО по ID ссылки
        var id = jQuery(this).attr('id');
        $('#test-'+id).modal().open();
    });

    // attach modal close handler
    jQuery('.modal .closemodal').click(function(e){
        e.preventDefault();
        $.modal().close();
    });

    // below isn't important (demo-specific things)
    jQuery('.modal .more-toggle').click(function(e){
        e.stopPropagation();
        $('.modal .more').toggle();
    });

    jQuery('input[name="f_number"]').keyup(function() {
        var form = $(this).closest('div[id]');
        var id_div = $(form).attr('id');

        var price = $('#'+id_div+' input[name="price"]').val();
        var number = $('#'+id_div+' input[name="f_number"]').val();

        $('#'+id_div+' .total').html(parseInt(price)*parseInt(number));
        $('#'+id_div+'  input[name="cost"]').val(parseInt(price)*parseInt(number));
    });

    jQuery(".ditto_sort").click(function(e) {
        e.preventDefault();
        var link_id = jQuery(this).attr('id');
        //alert(link_id);
        var href_ = jQuery("#"+link_id).attr("href");
        jQuery('#ditto-container').load(href_+' #for-reload', function() {
            connect_events1();
        });
        return false;
    });

    if ( jQuery('form.shopOrderForm').length ){
        $("form.shopOrderForm").validationEngine({
            ajaxFormValidation: true,
            onAjaxFormComplete: ajaxValidationCallback
        });
    }

    function ajaxValidationCallback(status, form, json, options){
        if (status === true) {
            $('.closemodal').click();
            alert("Спасибо за заказ, наши менеджеры свяжутся с вами в кратчайшие сроки");
        }
    }
*/

}


function checkIt(e)
{
 e = e || w.event;
 var key = (e.charCode) ? e.charCode : e.keyCode;
 var el = e.target || e.srcElement;
 if((key >=48 && key <=57) || (key >= 8 && key <= 32) || (key >= 37 && key <= 40)) return
 else if(key == 44)
 {
  var reg_1=/^\d+\,(\d+)?$/;
  var reg_2=/^\d+/;
  if(reg_1.test(el.value) || !reg_2.test(el.value)) return false
 }
 else return false
}
// генерация уникального ID для элементов в пределах одной формы., диалогового окна
function now_verbose(){
	return new Date().getTime();
}