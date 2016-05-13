$(document).ready(function() {

    // workaround for focusing on login username input from dropdown
    function focusOnInput() {
        $('#loginForm input[name="username"]').focus();
    }
    $('#loginDropdown').click(function() {
        setTimeout(focusOnInput, 100);
    });

    $('#loginForm').submit(function(event) {
        // don't submit the form
        event.preventDefault();

        // check if username and password are filled out
        var username = $('#loginForm input[name="username"]').val();
        var password = $('#loginForm input[name="password"]').val();
        if (username.length != 0 && password.length != 0) {
            $.ajax({
                type: "POST",
                url: BASE_URL + '/login',
                data: {
                    username: username,
                    password: password
                },
                success: function(data) {
                    //console.log('data: ');
                    //console.log(data);
                    if (!data.success) {
                        // display error message
                        $('#loginForm p.ajax-message').html(data.error);
                        if (data.error.indexOf('username') > -1) {
                            // focus on username input
                            $('#loginForm input[name="username"]').focus();
                            $('#loginForm input[name="username"]').css('border', '2px solid rgb(255, 138, 138)');
                        }
                        else if (data.error.indexOf('password') > -1) {
                            // focus on password input
                            $('#loginForm input[name="password"]').focus();
                            $('#loginForm input[name="password"]').css('border', '2px solid rgb(255, 138, 138)');
                        }
                    } else {
                        // send user to new url
                        window.location.href = data.url;
                    }
                }
            }).fail(function( jqXHR, textStatus, errorThrown ) {
                $('#loginForm p.ajax-message').html('Oops. Something went wrong. <strong>jqXHR</strong>: ' + jqXHR + '. <strong>textStatus</strong>: ' + textStatus + '. <strong>errorThrown</strong>: ' + errorThrown + '.');
            });
        }

        // check if there are error notices from previous errors and remove them
        var errorCount = $('#loginForm p.ajax-message').html().length;
        if (errorCount > 0) {
            $('#loginForm p.ajax-message').html('');
            $('#loginForm input').removeAttr('style');
        }

        // display error if neither username or password was entered
        if (username.length == 0 && password.length == 0) {
            $('#loginForm input[name="username"], #loginForm input[name="password"]').css('border', '2px solid rgb(255, 138, 138)');
            $('#loginForm p.ajax-message').html('Must enter username and password');
            $('#loginForm input[name="username"]').focus();
        }

        // display error if username was not entered
        else if (username.length == 0) {
            $('#loginForm input[name="username"]').css('border', '2px solid rgb(255, 138, 138)');
            $('#loginForm p.ajax-message').html('Must enter username');
            $('#loginForm input[name="username"]').focus();
        }

        // display error if password was not entered
        else if (password.length == 0) {
            $('#loginForm input[name="password"]').css('border', '2px solid rgb(255, 138, 138)');
            $('#loginForm p.ajax-message').html('Must enter password');
            $('#loginForm input[name="password"]').focus();
        }
    });
});
