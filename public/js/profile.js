$(document).ready(function() {

    $('#profile-follow-list-placeholder').hide();
    $('#profile-leader-list-placeholder').hide();
	if($("#followersContainer").text().trim() === "")
	{
		$('#profile-follow-list-placeholder').show();
	}
	if($("#leadersContainer").text().trim() === "")
	{
		$("#profile-leader-list-placeholder").show();
	}
	$("#profile-select").change(function(){});
	
	//Function that displays the error messages
    var displayMessage = function(message) {
        // remove any previous messages
        var numMessages = $('p.ajax-message').length;
        if (numMessages > 0) {
            $('p.ajax-message').remove();
        }
        $('#buttonWrapper').append('<p class="ajax-message">' + message+ '</p>');
    };

	//Callback on following a user
    var followUserCallback = function(data) {
        
        // remove placeholder
        $('#profile-follow-list-placeholder').hide();
        
		var followersContainer = document.getElementById("followersContainer");
		followersContainer.innerHTML += '<p id="removeMe"><a href="' + BASE_URL + '/profile/' + data.userId + '">' + data.username + '</a></p>';
	
        var newButtonId = 'unfollowButton';
        var newButtonClass = 'btn btn-lg btn-warning btn-block active';
        var profileId = data.profileId;
        var newButtonHtml = '<button id="' + newButtonId + '" data-profile-id="' + profileId + '" class="' + newButtonClass + '">Unfollow</button>';

        $('#followButton').remove();
        $('#buttonWrapper').append(newButtonHtml);
        displayMessage(data.message);
        runButtonHandlers();
    };
    
	//Callback on unfollowing a user
    var unfollowUserCallback = function(data) {
		if($('#followersContainer').innerHTML == null)
			//unhide placeholder
			$('#profile-follow-list-placeholder').show();
	
		var removeMe = document.getElementById('removeMe');
		removeMe.remove();
        var newButtonId = 'followButton';
        var newButtonClass = 'btn btn-lg btn-primary btn-block';
        var profileId = data.profileId;
        var newButtonHtml = '<button id="' + newButtonId + '" data-profile-id="' + profileId + '" class="' + newButtonClass + '">Follow</button>';

        $('#unfollowButton').remove();
        $('#buttonWrapper').append(newButtonHtml);
        displayMessage(data.message);
        runButtonHandlers();
    };
    
	//Binds events to action
    var runButtonHandlers = function() {
        $('#unfollowButton').one('click', function() {
            // get user id of person to unfollow, for ajax
            var userId = $(this).attr('data-profile-id');
            var url = BASE_URL + '/profile/' + userId + '/unfollow';

            $.ajax({
                type: "POST",
                url: url,
                success: unfollowUserCallback
            }).fail(function( jqXHR, textStatus, errorThrown ) {
                $('#unfollowButton').html('Oops. Could not reach the server.');
            });
        });
       
       $('#followButton').one('click', function() {
            // get user id of person to follow, for ajax
            var userId = $(this).attr('data-profile-id');
            var url = BASE_URL + '/profile/' + userId + '/follow';

            $.ajax({
                type: "POST",
                url: url,
                success: followUserCallback
            }).fail(function( jqXHR, textStatus, errorThrown ) {
                $('#followButton').html('Oops. Could not reach the server.');
            });
        });
    };

    runButtonHandlers();
});