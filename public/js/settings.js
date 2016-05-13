$(document).ready(function() {
    $('#settings-form').submit(function(event) {
        event.preventDefault();
        
        var firstName = $('#settings-form input[name="first-name"]').val();
        var lastName = $('#settings-form input[name="last-name"]').val();
        var email = $('#settings-form input[name="email"]').val();
        var password = $('#settings-form input[name="password"]').val();
        var feedLimit = $('#settings-form select[name="feedLimit"]').val();
		var bubbleLimit = $('#settings-form select[name="bubbleLimit"]').val();
        //console.log('limit: ' + limit);
        
        $.ajax({
            type: "POST",
            url: BASE_URL + '/settings',
            data: {
                firstName: firstName,
                lastName: lastName,
                email: email,
                password: password,
                feedLimit: feedLimit,
				bubbleLimit: bubbleLimit
            },
            success: function(data) {
                console.log('data: ');
                console.log(data);
                
                // display each message in messages array in p.ajax-message
                var ajaxMessages = '';
                
                var numMessages = data.messages.length;
                var i = 0;
                for (i = 0; i < numMessages; i++) {
                    ajaxMessages += '<p>' + data.messages[i] + '</p>';
                }
                
                $('div.ajax-messages').html(ajaxMessages);
            }
        }).fail(function( jqXHR, textStatus, errorThrown ) {
            $('#settings-form p.ajax-message').html('Oops. Something went wrong. <strong>jqXHR</strong>: ' + jqXHR + '. <strong>textStatus</strong>: ' + textStatus + '. <strong>errorThrown</strong>: ' + errorThrown + '.');
        });
    });
});
