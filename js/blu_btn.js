/**
 * Created with JetBrains PhpStorm.
 * User: artem
 * Date: 13.09.13
 * Time: 9:56
 * To change this template use File | Settings | File Templates.
 */

var j = jQuery.noConflict();

j(document).ready(function() {

    j("#blu_btn_phone").mask("+7(999)9999999");

    j('#modal_link').click(function(event) {

        j('body').css('overflow','hidden');

        event.preventDefault();
        //j('#substratre').css('display','block');
        j('#substratre').show();
        //j('#loading').css({opacity: .5}); // кроссбраузерная прозрачност
        j('#loading').slideToggle("slow");

        //class="modal hide fade in" aria-hidden="false" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" style="display: block;"
        j('#loading').addClass('in');
        j('#loading').attr('aria-hidden',false);
        j('#loading').css('display','block');
        j('#loading').focus().trigger('shown');

    });

    j('.close').click(function(event){
        event.preventDefault();
        j('#loading').hide();
        j('#substratre').hide();
        //j('#loading').slideUp("slow");
        //j('.parentFormblu_btn_form').hide();
        j('#blu_btn_form').validationEngine('hideAll');
        j('body').css('overflow','auto');
    });

    if ( j('#blu_btn_form').length ){
        j("#blu_btn_form").validationEngine({
            ajaxFormValidation: true,
            onAjaxFormComplete: ajaxValidationCallback,
            scroll: false
        });
    }

    function ajaxValidationCallback(status, form, json, options){
        if (status === true) {
            j('.close').click();
            alert("Спасибо за заказ, наши менеджеры свяжутся с вами в кратчайшие сроки");
        }
    }

    function createUploader() {
        j('#bootstrapped-fine-uploader').fineUploader({
            //element: document.getElementById('bootstrapped-fine-uploader'),
            request: {
                endpoint: 'upload_file.php'
            },
            text: {
                uploadButton: '<div><i class="icon-upload icon-white"></i>Загрузить файл</div>'
            },
            template: '<div class="qq-uploader span12">' +
                '<pre class="qq-upload-drop-area span12"><span>{dragZoneText}</span></pre>' +
                '<div class="qq-upload-button btn btn-success" style="width: auto;">{uploadButtonText}</div>' +
                '<span class="qq-drop-processing"><span>{dropProcessingText}</span><span class="qq-drop-processing-spinner"></span></span>' +
                '<ul class="qq-upload-list" style="margin-top: 10px; text-align: center;"></ul>' +
                '</div>',
            classes: {
                success: 'alert alert-success',
                fail: 'alert alert-error'
            },
            multiple: false,
            validation: {
                //allowedExtensions: ['jpeg', 'jpg', 'txt'],
                sizeLimit: 20485760, // 20485760 bytes
                itemLimit: 1// загрузить можно лишь 1 файл
            }
        })
        .on('complete', function(event, id, fileName, responseJSON) {
                j('#attach_file').val(responseJSON.path);
                //j('.upload_file_idformError').hide();
                j('body').css('overflow','auto');
            }
        );


    }

    window.onload = createUploader;

    j('#blu_btn_form_btn').click(function(event){

        j('body').css('overflow','hidden');

        j('#url_page').val(document.location.href);

        j('#upload_file_id').removeClass();

        if(j('#attach_file').val().length==0){

            //j('#upload_file_id').addClass('validate[required] text-input');
        }
        if(j('#attach_file').val().length>0){

        }

        j("#blu_btn_form").validationEngine();

        //j('.upload_file_idformError').show();

        j('#blu_btn_form').submit();

        j("#blu_btn_form").validationEngine("updatePromptsPosition");

        //j('#blu_btn_form').validationEngine("updatePromptsPosition");
    });

    j('#substratre').css('overflow','hidden');

});
