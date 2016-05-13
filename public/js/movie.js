/**
	Adds a movie to the To Watch list
	movieID is the id of the movie to add
	userID is the id of the user for the movie to be related to
	Disables the button used to call the function
*/
function addToWatchList(movieID, movieName, userID)
{
	var button = document.getElementById("button" + movieID);
	button.disabled = true;
	button.style.color = "grey";
	$.ajax({
                type: "POST",
                url: BASE_URL + '/watch',
                data: {
                    movieID: movieID,
					movieName: movieName,
                    userID: userID,
					deleteOld: "false"
                },
                success: function(data) {
                    console.log('data: ');
                    console.log(data);
                    if (!data.success) {
                        // display error message
                        $('p.ajax-message').html(data.error);
                    }
                }
            }).fail(function( jqXHR, textStatus, errorThrown ) {
                $('p.ajax-message').html('Oops. Something went wrong. <strong>jqXHR</strong>: ' + jqXHR + '. <strong>textStatus</strong>: ' + textStatus + '. <strong>errorThrown</strong>: ' + errorThrown + '.');
            });
}

/**
    Adds a comments to the Comment database.
*/
function addComment() 
{
    $("#submitComment").click(function(event) {

        var comment = $("#enterComments").val();
        var rating = $("#dropdownMenu2").text();
        var endorse = $("#endorseButton").css('background-color');
        var username = $(document).find("title").text();
        var movieID = window.location.href;
		var movieName = $("#movieName").text();

        var arr = movieID.split('/');
        movieID = arr[arr.length - 1];
        
        arr = username.split('·');
        username = arr[arr.length - 1];

        arr = rating.split('▲');
        rating = arr[0];

        var pattern = /Rating/;

        if (endorse == 'rgb(255, 255, 255)')
        {
            endorse = '';
        }
        else
        {
            endorse = 'Endorsed';
        }

        if (comment.length > 0 && !pattern.test(rating))
        {
            $.ajax({
                type: "POST",
                url: BASE_URL + '/entercomment/',
                data: {
                    Comment: comment,
                    Rating: rating,
                    MovieID: movieID,
					MovieName: movieName,
                    Username: username,
                    Endorse: endorse
                },
                success: function(data) {
                    console.log('data: ');
                    console.log(data);
                    if (!data.success) {
                        alert("Error");
                    }
                    else {
                        // Adds the comment to the html page visible to the user for a quick update.
                        var div = '<div>';
                        div = div + '<div id="commentHeader">';
                        div = div + '<label>'+username+'</label>';
                        div = div + ' <label>'+rating+'</label>';
                        div = div + ' <label>'+endorse+'</label>';
                        if (data.moderator == "Moderator")
                        {
                            div = div + ' <button class="banButton commentButton" username="'+username+'" comment="'+comment+'"> Delete</button>';
                        }
                        div = div + '</div>';
                        div = div + '<p id="comment">' + comment;
                        div = div + '</p>';
                        div = div + '</div>';

                        if (comment.length > 0 && !pattern.test(rating))
                        {   
                            $('#commentList').prepend(div);
							banButton();
							$('#reviews_placeholder').hide();
                        }

                        if ($("#endorseButton").css('background-color') != 'rgb(255, 255, 255)')
                        {
                            $("#endorseButton").toggleClass('green');
                        }
                        $("#enterComments").val('');
                        $("#dropdownMenu2").text('Rating ▲');
                    }
                }
            }).fail(function( jqXHR, textStatus, errorThrown ) {
                $('#loginForm p.ajax-message').html('Oops. Something went wrong. <strong>jqXHR</strong>: ' + jqXHR + '. <strong>textStatus</strong>: ' + textStatus + '. <strong>errorThrown</strong>: ' + errorThrown + '.');
            });
        }
        else
        {
            alert("Please Input all of the fields");
        }
    });
}

/**
Deletes a comment from the Comments Database
*/
function banButton()
{
    $(".banButton").click(function() 
    {
        var element = this;
        $.ajax({
                type: "POST",
                url: BASE_URL + '/bancomment/',
                data: {
                    Comment: $(this).attr("comment"),
                    Username: $(this).attr("username"),
                },
                success: function(data) {
                    console.log('data: ');
                    console.log(data);
                    if (!data.success) {
                        alert("Error");
                    }
                    else if (data.success) {
                        $(element).parent().parent().remove();
						
						if($('#commentList').text().trim() === "")
							$('#reviews_placeholder').show();
                    }
                }
            }).fail(function( jqXHR, textStatus, errorThrown ) {
                $('#loginForm p.ajax-message').html('Oops. Something went wrong. <strong>jqXHR</strong>: ' + jqXHR + '. <strong>textStatus</strong>: ' + textStatus + '. <strong>errorThrown</strong>: ' + errorThrown + '.');
            });
    });
}

$(document).ready(function() {

    $(".dropup").on('click','li',function ()
    {
        $("#dropdownMenu2").text(($(this).text()) + " ▲ ");
    });

    $("#endorseButton").click(function() {
        $(this).toggleClass('green');
    });

    addComment();

    banButton();
	$('#reviews_placeholder').hide();
	if($('#commentList').text().trim() === "")
		$('#reviews_placeholder').show();
})
