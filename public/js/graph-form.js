$(document).ready(function() {
    $('#graph-form').submit(function(event) {
        event.preventDefault();
        
		var bubbleLimit = $('#graph-form select[name="bubbleLimit"]').val();
        
        $.ajax({
            type: "POST",
            url: BASE_URL + '/settings',
            data: {
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
                
                // set data-bubble-limit attr on #graph
                $('#graph').attr('data-bubble-limit', bubbleLimit);
                
                
                // redraw
                $('#graph svg').remove();
                $.ajax({
                    url: BASE_URL + '/graphdata',
                    dataType: "json",
                    success: function(data) {

                        var d3Json_data = preppingForD3_data(data);
                        //Run D3 to create a bubble chart
                        drawData(d3Json_data);
                    },
                    error: function(err) {
                        console.log(err);
                    }
                });
            }
        }).fail(function( jqXHR, textStatus, errorThrown ) {
            $('#graph-form div.ajax-messages').html('Oops. Something went wrong. <strong>jqXHR</strong>: ' + jqXHR + '. <strong>textStatus</strong>: ' + textStatus + '. <strong>errorThrown</strong>: ' + errorThrown + '.');
        });
    });
});
