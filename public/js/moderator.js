$(document).ready(function() {

	//removes the previous error messages
	var removePreviousErrorMessages = function() {
        // check if there are error notices from previous errors and remove them
        var errorCount = $('#promoteForm p.ajax-message').html().length;
        if (errorCount > 0) {
            $('#promoteForm p.ajax-message').html('');
        }
    };
    
    // callback for username input validation on blur
    var userNameBlurValidation = function(data) {
        removePreviousErrorMessages();
        
        if (data.okay) {
            // set the background color for available
            var color = '#66ff99';
        }
        else {
            // set the background color unavailable
            var color = 'rgb(255, 138, 138)';
            // focus on input again
            $('#promoteForm input[name="username"]').focus();
			
        }
        
        // set background-color
        $('#promoteForm input[name="username"]').css('background-color', color);
		// display message
		$('#promoteForm p.ajax-message').html(data.message);
        
    };
    
    // username input validation on blur
    $('#promoteForm input[name="username"]').blur(function() {
        removePreviousErrorMessages();

        var username = $(this).val();
        if (username.trim() != '') {
            $.ajax({
                type: "GET",
                url: BASE_URL + '/ismoderator/' + username,
                success: userNameBlurValidation
            }).fail(function( jqXHR, textStatus, errorThrown ) {
                $('#signupForm p.ajax-message').html('Oops. Could not reach the server.');
            });
        }
        else {
            $('#promoteForm p.ajax-message').html('must enter <strong>username</strong>');
            $('#promoteForm input[name="username"]').css('background-color', 'rgb(255, 138, 138)');
        }
    });

	//uses ajax to call a function to promote a user
	$('#promoteForm').submit(function(event) {   
		// don't submit the form
        event.preventDefault();

        removePreviousErrorMessages();
		var username = $('#promoteForm input[name="username"]').val();
		if(username.trim() == "")
		{
			$('#promoteForm p.ajax-message').html("Username cannot be empty");
			$('#promoteForm input[name="username"]').focus();
		}
		else
		{
			$.ajax({
                type: "POST",
                url: BASE_URL + '/promote',
                data: {
                    username: username
                },
                success: function(data) {
                    if (!data.success) {
                        // display message
                        $('#promoteForm p.ajax-message').html(data.message);
                        // focus on first input
                        $('#promoteForm input[name="' + data.inputToFocus + '"]').focus();
                    }
                    else {
                        //Update Moderator list
						var prependData = '<p><a href="' + BASE_URL + '/profile/' + data.userID + '">' + username + '</a></p>';
						$('#addUsersHere').prepend(prependData);
						$('#promoteForm input[name="username"]').val('');
						$('#promoteForm input[name="username"]').css('background-color', '#ffffff');
                    }
                }
            }).fail(function( jqXHR, textStatus, errorThrown ) {
                $('#signupForm p.ajax-message').html('Oops. Could not reach the server.');
            });
		}
	});

});