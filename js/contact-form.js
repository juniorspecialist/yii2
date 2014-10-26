jQuery(function(){
	jQuery('.error').hide();
	
	// reset form and hide all errors
	jQuery("a#clear").click(function(){
		jQuery('.error').hide();
		jQuery('form#contact-form').clearForm();
	});
	
	// show message error if after editing
	// the name field contains improper value
	jQuery("input#name").blur(function(){
		if(validateInput('name')){
			if(!validateName()){
				jQuery("label#name_error").hide();
				jQuery("label#name_error2").show();
			}
		}else{
			jQuery("label#name_error2").hide();
		}
	});
	
	// show message error if after editing
	// the email field contains improper value
	jQuery("input#email").blur(function(){
		if(validateInput('email')){
			if(!validateEmail()){
				jQuery("label#email_error").hide();
				jQuery("label#email_error2").show();
			}
		}else{
			jQuery("label#email_error2").hide();
		}
	});
	
	// show message error if after editing
	// the phone field contains improper value
	jQuery("input#phone").blur(function(){
		if(validateInput('phone')){
			if(!validatePhone()){
				jQuery("label#phone_error").hide();
				jQuery("label#phone_error2").show();
			}
		}else{
			jQuery("label#phone_error2").hide();
		}
	});
	
	// show message error if after editing
	// the message field contains improper value
	jQuery("textarea#message").blur(function(){
		if(validateTextArea('message')){
			if(!validateMessage()){
				jQuery("label#message_error").hide();
				jQuery("label#message_error2").show();
			}
		}else{
			jQuery("label#message_error2").hide();
		}
	});
	
	jQuery("input#name").keydown(function(){
		if(validateInput('name')){
			jQuery("label#name_error").hide();
		}
		if(validateName()){
			jQuery("label#name_error2").hide();
		}
	});
	
	jQuery("input#email").keydown(function(){
		if(validateInput('email')){
			jQuery("label#email_error").hide();
		}
		if(validateEmail()){
			jQuery("label#email_error2").hide();
		}
	});
	
	jQuery("input#phone").keydown(function(){
		if(validateInput('phone')){
			jQuery("label#phone_error").hide();
		}
		if(validatePhone()){
			jQuery("label#phone_error2").hide();
		}
	});
	
	jQuery("textarea#message").keydown(function(){
		if(validateTextArea('message')){
			jQuery("label#message_error").hide();
		}
		if(validateMessage()){
			jQuery("label#message_error2").hide();
		}
	});
	
	var owner_email = jQuery("input#owner_email").val();
	if(!isValidEmailAddress(owner_email)){
		jQuery('#contact_form').html("<label class='error'>*Owner email is not valid</label>")
	}
		
	jQuery("a#submit").click(function(){
		// validate and process form
		var quit = false;
		if(validateName()){
			name = validateName();
			jQuery("label#name_error").hide();
			jQuery("label#name_error2").hide();
		}else if(validateInput('name')){
			jQuery("label#name_error").hide();
			jQuery("label#name_error2").show();
		}else{
			jQuery("label#name_error").show();
			jQuery("label#name_error2").hide();
			quit = true;
		}
		if(validateEmail()){
			email = validateEmail();
			jQuery("label#email_error").hide();
			jQuery("label#email_error2").hide();
		}else if(validateInput('email')){
			jQuery("label#email_error").hide();
			jQuery("label#email_error2").show();
		}else{
			jQuery("label#email_error").show();
			jQuery("label#email_error2").hide();
			quit = true;
		}
		if(validatePhone()){
			phone = validatePhone();
			jQuery("label#phone_error").hide();
			jQuery("label#phone_error2").hide();
		}else if(validateInput('phone')){
			jQuery("label#phone_error").hide();
			jQuery("label#phone_error2").show();
		}else{
			jQuery("label#phone_error").show();
			jQuery("label#phone_error2").hide();
			quit = true;
		}
		if(validateMessage()){
			message = validateMessage();
			jQuery("label#message_error").hide();
			jQuery("label#message_error2").hide();
		}else if(validateTextArea('message')){
			jQuery("label#message_error").hide();
			jQuery("label#message_error2").show();
		}else{
			jQuery("label#message_error").show();
			jQuery("label#message_error2").hide();
			quit = true;
		}
		if(quit){
			return false;
		}
		
		var stripHTML = jQuery("input#stripHTML").val();
		var smtpMailServer = jQuery("input#smtpMailServer").val();
		
		var dataString = 'name=' + name + '&email=' + email + '&phone=' + phone + '&message=' + message + '&owner_email=' + owner_email + '&stripHTML=' + stripHTML + '&smtpMailServer=' + smtpMailServer;
		
		var serverProcessorType = jQuery("input#serverProcessorType").val();
		if(serverProcessorType == 'asp'){
			fileExtension = 'ashx';
		}else{
			fileExtension = serverProcessorType;
		}
		var mailHandlerURL = "bin/MailHandler." + fileExtension;
		jQuery.ajax({
			type: "POST",
			url: mailHandlerURL,
			data: dataString,
			success: function(){
				jQuery('.error').hide();
				jQuery('form#contact-form').clearForm();
				jQuery('#contact_form').html("<div class='download-box'>Contact form submitted!</div>")
					.append("<br><label for='message'><b>We will be in touch soon.</b></label>")
					.hide()
					.fadeIn(1500, function(){
						jQuery('#contact_form').append("<br><br><a id='back' onclick='window.location.reload(); return false;' class='button'>back</a>");
					});
			}
		});
				
		return false;
	});
});
jQuery.fn.clearForm = function(){
	return this.each(function(){
		var type = this.type, tag = this.tagName.toLowerCase();
		if (tag == 'form'){
			return jQuery(':input',this).clearForm();
		}
		if (type == 'text' || type == 'password' || tag == 'textarea'){
			this.value = '';
		}else if (type == 'checkbox' || type == 'radio'){
			this.checked = false;
		}else if (tag == 'select'){
			this.selectedIndex = -1;
		}
	});
};
function isValidName(name){
	var pattern = new RegExp(/^[a-zA-Z'][a-zA-Z-' ]+[a-zA-Z']?jQuery/);
	
	return pattern.test(name);
}
function isValidEmailAddress(emailAddress){
	var pattern = new RegExp(/^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)jQuery)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?jQuery)/i);
	
	return pattern.test(emailAddress);
}

function isValidPhoneNumber(phoneNumber){
	var pattern = new RegExp(/^\+?(\d[\d\-\+\(\) ]{5,}\djQuery)/);
	
	return pattern.test(phoneNumber);
}

function validateName(){
	var name = jQuery("input#name").val();
	if(isValidName(name)){
		return name;
	}else{
		return false;
	}
}

function validateEmail(){
	var email = jQuery("input#email").val();
	if(!isValidEmailAddress(email)){
		return false;
	}else{
		return email;
	}
}

function validatePhone(){
	var phone = jQuery("input#phone").val();
	if(!isValidPhoneNumber(phone)){
		return false;
	}else{
		return phone;
	}
}

function validateMessage(){
	var message = jQuery("textarea#message").val();
	if(message.length < 10){
		return false;
	}else{
		return message;
	}
}

// make sure visitor does not input a blank field
function validateInput(field){
	var fieldObject = jQuery("input#" + field + "").val();
	if(fieldObject.length < 1){
		return false;
	}else{
		return true;
	}
}

function validateTextArea(field){
	var fieldObject = jQuery("textarea#" + field + "").val();
	if(fieldObject.length < 1){
		return false;
	}else{
		return true;
	}
}