$(document).ready(function() {
    // focus on signup username input
    $('#signupForm input[name="username"]').focus();

    var removePreviousErrorMessages = function() {
        // check if there are error notices from previous errors and remove them
        var errorCount = $('#signupForm p.ajax-message').html().length;
        if (errorCount > 0) {
            $('#signupForm p.ajax-message').html('');
        }
    };
    
    // callback for username input validation on blur
    var userNameBlurValidation = function(data) {
        removePreviousErrorMessages();
        
        if (data.available) {
            // set the background color for available
            var color = '#66ff99';
        }
        else {
            // set the background color unavailable
            var color = 'rgb(255, 138, 138)';
            // focus on input again
            $('#signupForm input[name="username"]').focus();
            // clear username input
            $('#signupForm input[name="username"]').val('');
        }
        
        // set background-color
        $('#signupForm input[name="username"]').css('background-color', color);
        // display message
        $('#signupForm p.ajax-message').html(data.message);
    };
    
    // username input validation on blur
    $('#signupForm input[name="username"]').blur(function() {
        removePreviousErrorMessages();

        var username = $(this).val();
        if (username.trim() != '') {
            $.ajax({
                type: "GET",
                url: BASE_URL + '/username/' + username,
                success: userNameBlurValidation
            }).fail(function( jqXHR, textStatus, errorThrown ) {
                $('#signupForm p.ajax-message').html('Oops. Could not reach the server.');
            });
        }
        else {
            $('#signupForm p.ajax-message').html('must enter <strong>username</strong>');
            $('#signupForm input[name="username"]').css('background-color', 'rgb(255, 138, 138)');
        }
    });
    
    // callback for email input validation on blur
    var emailBlurValidation = function(data) {
        removePreviousErrorMessages();

        if (data.available) {
            // set the background color for available
            var color = '#66ff99';
        }
        else {
            // set the background color unavailble
            var color = 'rgb(255, 138, 138)';
            // focus on input again
            $('#signupForm input[name="email"]').focus();
            // clear email input
            $('#signupForm input[name="email"]').val('');
        }
        
        // set background-color
        $('#signupForm input[name="email"]').css('background-color', color);
        // display message
        $('#signupForm p.ajax-message').html(data.message);
    };
    
    // helper function used in email input validation on blue
    // validate email address format before doing AJAX to check if it exists
    var preAjaxEmailValidation = function(email) {
        removePreviousErrorMessages();

        var isValid = false;
        
        // helper for validating email (emailValidator.js)
        var validator = new emailValidator();
        
        // display error if email is empty
        if (email.length == 0) {
            $('#signupForm p.ajax-message').html('must enter <strong>email</strong>');
            $('#signupForm input[name="email"]').css('background-color', 'rgb(255, 138, 138)');
        }

        // check if email contains a space
        else if (!validator.hasNoSpaces(email)) {
            $('#signupForm p.ajax-message').html('<strong>email</strong> must not have any spaces');
            $('#signupForm input[name="email"]').css('background-color', 'rgb(255, 138, 138)');
        }

        // check if email contains '@' symbol
        else if (!validator.containsAtSymbol(email)) {
            $('#signupForm p.ajax-message').html(email + ' must contain an <strong>&#64;</strong> symbol');
            $('#signupForm input[name="email"]').css('background-color', 'rgb(255, 138, 138)');

        }

        // check if email contains string before '@' symbol
        else if (!validator.containsPrefix(email)) {
            $('#signupForm p.ajax-message').html(email + ' must have something before the <strong>&#64;</strong> symbol');
            $('#signupForm input[name="email"]').css('background-color', 'rgb(255, 138, 138)');
        }

        // check if email contains string after '@' symbol
        else if (!validator.containsSuffix(email)) {
            $('#signupForm p.ajax-message').html(email + ' must have something after the <strong>&#64;</strong> symbol');
            $('#signupForm input[name="email"]').css('background-color', 'rgb(255, 138, 138)');
        }
        
        // email format is valid
        else {
            isValid = true;
            $('#signupForm p.ajax-message').html(email + ' is a valid format');
            $('#signupForm input[name="email"]').css('background-color', '#66ff99');
        }
        
        return isValid;
    };
    
    // email input validation on blur
    $('#signupForm input[name="email"]').blur(function() {
        removePreviousErrorMessages();

        var email = $(this).val();
        
        var emailFormatIsValid = preAjaxEmailValidation(email);
        
        if (emailFormatIsValid && email.trim() != '') {            
            $.ajax({
                type: "GET",
                url: BASE_URL + '/email/' + email,
                success: emailBlurValidation
            }).fail(function( jqXHR, textStatus, errorThrown ) {
                $('#signupForm p.ajax-message').html('Oops. Could not reach the server.');
            });
        }
    });
    
	//callback for password validation on blur
	var passwordBlurValidation = function(data) {
		removePreviousErrorMessages();
		
		if(data.available) {
			// set the background color for not empty
            var color = '#66ff99';
		}
        else {
            // set the background color empty
            var color = 'rgb(255, 138, 138)';
            // focus on input again
            $('#signupForm input[name="password"]').focus();
            // clear username input
            $('#signupForm input[name="password"]').val('');
        }
        //alert(data.available);
        // set background-color
        $('#signupForm input[name="password"]').css('background-color', color);
        // display message
        $('#signupForm p.ajax-message').html(data.message);
		
	}
	
    // password not empty validation on blur
    $('#signupForm input[name="password"]').blur(function() {
        removePreviousErrorMessages();

        var password = $(this).val();
		
        if (password.trim() === '') {            
            $('#signupForm p.ajax-message').html('must enter <strong>password</strong>');
            $('#signupForm input[name="password"]').css('background-color', 'rgb(255, 138, 138)');
        } else {
			$.ajax({
                type: "GET",
                url: BASE_URL + '/password/' + password,
                success: passwordBlurValidation
            }).fail(function( jqXHR, textStatus, errorThrown ) {
                $('#signupForm p.ajax-message').html('Oops. Could not reach the server.');
            });
        }
    });
	
	// callback for first name input validation on blur
    var firstNameBlurValidation = function(data) {
        removePreviousErrorMessages();
        
        if (data.available) {
            // set the background color for available
            var color = '#66ff99';
        }
        else {
            // set the background color unavailable
            var color = 'rgb(255, 138, 138)';
            // focus on input again
            $('#signupForm input[name="firstName"]').focus();
            // clear username input
            $('#signupForm input[name="firstName"]').val('');
        }
        
        // set background-color
        $('#signupForm input[name="firstName"]').css('background-color', color);
        // display message
        $('#signupForm p.ajax-message').html(data.message);
    };
    
    // firstName input validation on blur
    $('#signupForm input[name="firstName"]').blur(function() {
        removePreviousErrorMessages();

        var firstName = $(this).val();
        if (firstName.trim() != '') {
            $.ajax({
                type: "GET",
                url: BASE_URL + '/firstname/' + firstName,
                success: firstNameBlurValidation
            }).fail(function( jqXHR, textStatus, errorThrown ) {
                $('#signupForm p.ajax-message').html('Oops. Could not reach the server.');
            });
        }
        else {
            $('#signupForm p.ajax-message').html('must enter <strong>first name</strong>');
            $('#signupForm input[name="firstName"]').css('background-color', 'rgb(255, 138, 138)');
        }
    });
	
	// callback for last name input validation on blur
    var lastNameBlurValidation = function(data) {
        removePreviousErrorMessages();
        
        if (data.available) {
            // set the background color for available
            var color = '#66ff99';
        }
        else {
            // set the background color unavailable
            var color = 'rgb(255, 138, 138)';
            // focus on input again
            $('#signupForm input[name="lastName"]').focus();
            // clear username input
            $('#signupForm input[name="lastName"]').val('');
        }
        
        // set background-color
        $('#signupForm input[name="lastName"]').css('background-color', color);
        // display message
        $('#signupForm p.ajax-message').html(data.message);
    };
    
    // lastName input validation on blur
    $('#signupForm input[name="lastName"]').blur(function() {
        removePreviousErrorMessages();

        var lastName = $(this).val();
        if (lastName.trim() != '') {
            $.ajax({
                type: "GET",
                url: BASE_URL + '/lastname/' + lastName,
                success: lastNameBlurValidation
            }).fail(function( jqXHR, textStatus, errorThrown ) {
                $('#signupForm p.ajax-message').html('Oops. Could not reach the server.');
            });
        }
        else {
            $('#signupForm p.ajax-message').html('must enter <strong>last name</strong>');
            $('#signupForm input[name="lastName"]').css('background-color', 'rgb(255, 138, 138)');
        }
    });
	
    $('#signupForm').submit(function(event) {        
        // don't submit the form
        event.preventDefault();

        removePreviousErrorMessages();

        // check if username and password are filled out
        var username = $('#signupForm input[name="username"]').val(); 
        var email = $('#signupForm input[name="email"]').val(); 
        var password = $('#signupForm input[name="password"]').val();
		var firstName = $('#signupForm input[name="firstName"]').val();
		var lastName = $('#signupForm input[name="lastName"]').val();

		var message = "";
		var focusOn = "";
		
        // display error if any fields are empty
        if (username.length == 0) {
			message += "<strong>Username</strong> cannot be empty<br />";
			if(focusOn.length == 0)
				focusOn = "username";
		}
		if(firstName.length == 0) {
			message += "<strong>First Name</strong> cannot be empty <br />";
			if(focusOn.length == 0)
				focusOn = "firstName";
		}
		if(lastName.length == 0) {
			message += "<strong>Last Name</strong> cannot be empty <br />";
			if(focusOn.length == 0)
				focusOn = "lastName";
		}
		if(email.length == 0) {
			message += "<strong>Email</strong> cannot be empty <br />";
			if(focusOn.length == 0)
				focusOn = "email";
		}
		if(password.length == 0) {
			message += "<strong>Password</strong> cannot be empty <br />";
			if(focusOn.length == 0)
				focusOn = "password";
		}
        $('#signupForm p.ajax-message').html(message);
		//focus on the first field that was empty
        $('#signupForm input[name="' + focusOn + '"]').focus();
		if(focusOn.length == 0)
		{
			// username, email, password are filled out
			$.ajax({
                type: "POST",
                url: BASE_URL + '/signup',
                data: {
                    username: username,
                    email: email,
                    password: password,
					firstname: firstName,
					lastname: lastName
                },
                success: function(data) {
                    if (!data.success) {
                        // display message
                        $('#signupForm p.ajax-message').html(data.message);
                        // focus on first input
                        $('#signupForm input[name="' + data.inputToFocus + '"]').focus();
                    }
                    else {
                        // send user to new url
                        window.location.href = data.url;
                    }
                }
            }).fail(function( jqXHR, textStatus, errorThrown ) {
                $('#signupForm p.ajax-message').html('Oops. Could not reach the server.');
            });
		}
    });
});
