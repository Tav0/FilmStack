/**
	Adds a movie to the To Watch list
	movieID is the id of the movie to add
	userID is the id of the user for the movie to be related to
	deleteOld should be a string that is either true or false to delete it from
	the other table or not, true if you want to delete
	Disables the button used to call the function
*/
function addToWatch(movieID, movieName, userID, watchedID, deleteOld) {
	var button = document.getElementsByClassName("button" + movieID);
	var remove = document.getElementsByClassName("remove" + movieID);
	for (var a = button.length - 1; a >= 0; a--) {
		button[a].disabled = true;
		button[a].style.color = "grey";
		remove[a].disabled = true;
		remove[a].style.color = "grey";
		button[a].remove();
	}
	$.ajax({
		type: "POST",
		url: BASE_URL + '/watch',
		data: {
			movieID: movieID,
			movieName: movieName,
			userID: userID,
			deleteOld: deleteOld
		},
		success: function(data) {
			if (!data.success) {
				// display error message
				$('p.ajax-message').html(data.error);
			} else {
				//change the location of the button
				//for(var b = 0; b < button.length; b++)
				//	button[b].remove();
				for (var b = 0; b < remove.length; b++) {
					remove[b].disabled = false;
					remove[b].style.color = "white";
				}
				var div = document.getElementsByClassName('watch' + movieID);
				for (var c = 0; c < div.length; c++)
					div[c].innerHTML = '<input class="btn btn-info addToWatchedButton button' + movieID +
					'"' +
					'type="button" value="Add To Watched" onclick="addToWatched(' +
					movieID + ',\'' + movieName + '\',' + userID + ', ' + watchedID +
					', \'true\')" />';
				var watchedRow = document.getElementsByClassName("watchedRow" + movieID);
				var movieNameCol = watchedRow[0].getElementsByClassName('movieNameCol');
				var movieNameLink = movieNameCol[0].innerHTML;
				watchedRow[0].remove();
				var watchContainer = document.getElementById("watchContainer");
				watchContainer.innerHTML += '<div class="row watchRow' + movieID + '">' +
					'<div class="col-xs-3 movieNameCol"><a href="' + BASE_URL + '/movie/' +
					movieID + '">' + movieNameLink + '</a></div>' +
					'<div class="col-xs-3 watch' + movieID +
					'"><input class="btn btn-info addToWatchedButton button' + movieID + '" ' +
					'type="button" value="Add To Watched" onclick="addToWatched(' + movieID +
					', \'' + movieName + '\', ' + userID + ', ' + watchedID +
					', \'true\')" /></div>' +
					'<div class="col-xs-3 watched' + movieID + '"></div>' +
					'<div class="col-xs-3" id="removeItem"><input type="button" class="btn btn-info remove' +
					movieID + '"' +
					'value="Remove" onclick="removeFromWatch(' + watchedID + ',' + movieID +
					')" /></div>' +
					'</div>';
			}
		}
	}).fail(function(jqXHR, textStatus, errorThrown) {
		$('p.ajax-message').html(
			'Oops. Something went wrong. <strong>jqXHR</strong>: ' + jqXHR +
			'. <strong>textStatus</strong>: ' + textStatus +
			'. <strong>errorThrown</strong>: ' + errorThrown + '.');
	});

}

/**
	Adds a movie to the Watched list
	movieID is the id of the movie to add
	userID is the id of the user for the movie to be related to
	deleteOld should be a string that is either true or false to delete it from
	the other table or not, true if you want to delete
	Disables the button used to call the function
*/
function addToWatched(movieID, movieName, userID, watchedID, deleteOld) {
	var button = document.getElementsByClassName("button" + movieID);
	var remove = document.getElementsByClassName("remove" + movieID);
	for (var a = button.length - 1; a >= 0; a--) {
		button[a].disabled = true;
		button[a].style.color = "grey";
		remove[a].disabled = true;
		remove[a].style.color = "grey";
		button[a].remove();
	}
	$.ajax({
		type: "POST",
		url: BASE_URL + '/watched',
		data: {
			movieID: movieID,
			movieName: movieName,
			userID: userID,
			deleteOld: deleteOld
		},
		success: function(data) {
			if (!data.success) {
				// display error message
				$('p.ajax-message').html(data.error);
			} else {
				//change the location of the button
				//for(var b = 0; b < button.length; b++)
				//	button[b].remove();
				for (var b = 0; b < remove.length; b++) {
					remove[b].disabled = false;
					remove[b].style.color = "white";
				}
				var div = document.getElementsByClassName('watched' + movieID);
				for (var c = 0; c < div.length; c++)
					div[c].innerHTML = '<input class="btn btn-info addToWatchButton button' + movieID +
					'"' +
					'type="button" value="Add To Watch" onclick="addToWatch(' +
					movieID + ',\'' + movieName + '\',' + userID + ', ' + watchedID +
					', \'true\')" />';
				var watchRow = document.getElementsByClassName("watchRow" + movieID);
				var movieNameCol = watchRow[0].getElementsByClassName('movieNameCol');
				var movieNameLink = movieNameCol[0].innerHTML;
				watchRow[0].remove();
				var watchedContainer = document.getElementById("watchedContainer");
				watchedContainer.innerHTML += '<div class="row watchedRow' + movieID +
					'">' +
					'<div class="col-xs-3 movieNameCol"><a href="' + BASE_URL + '/movie/' +
					movieID + '">' + movieNameLink + '</a></div>' +
					'<div class="col-xs-3 watch' + movieID + '"></div>' +
					'<div class="col-xs-3 watched' + movieID +
					'"><input class="btn btn-info addToWatchButton button' + movieID + '" ' +
					'type="button" value="Add To Watch" onclick="addToWatch(' + movieID +
					', \'' + movieName + '\', ' + userID + ', ' + watchedID +
					', \'true\')" /></div>' +
					'<div class="col-xs-3" id="removeItem"><input type="button" class="btn btn-info remove' +
					movieID + '"' +
					'value="Remove" onclick="removeFromWatched(' + watchedID + ',' +
					movieID + ')" /></div>' +
					'</div>';
			}
		}
	}).fail(function(jqXHR, textStatus, errorThrown) {
		$('p.ajax-message').html(
			'Oops. Something went wrong. <strong>jqXHR</strong>: ' + jqXHR +
			'. <strong>textStatus</strong>: ' + textStatus +
			'. <strong>errorThrown</strong>: ' + errorThrown + '.');
	});
}

/**
	Removes the movie with the given movie id from the watched list with the given list id
	listID is the id of the watched list with the movie
	movieID is the id of the movie to remove
	Disables other buttons related to the specified movie when used
*/
function removeFromWatched(listID, movieID) {
	var button = document.getElementsByClassName("button" + movieID);
	var remove = document.getElementsByClassName("remove" + movieID);
	for (var a = button.length - 1; a >= 0; a--) {
		button[a].disabled = true;
		button[a].style.color = "grey";
		remove[a].disabled = true;
		remove[a].style.color = "grey";
	}
	$.ajax({
		type: "POST",
		url: BASE_URL + '/remove',
		data: {
			movieID: movieID,
			listID: listID,
			listName: "Watched"
		},
		success: function(data) {
			if (!data.success) {
				// display error message
				$('p.ajax-message').html(data.error);
			} else {
				//remove the rows with this movie
				var watchedRow = document.getElementsByClassName("watchedRow" + movieID);
				watchedRow[0].remove();
				var allRow = document.getElementsByClassName("allRow" + movieID);
				allRow[0].remove();
			}
		}
	}).fail(function(jqXHR, textStatus, errorThrown) {
		$('p.ajax-message').html(
			'Oops. Something went wrong. <strong>jqXHR</strong>: ' + jqXHR +
			'. <strong>textStatus</strong>: ' + textStatus +
			'. <strong>errorThrown</strong>: ' + errorThrown + '.');
	});
}

/**
	Removes the movie with the given movie id from the to watch list with the given list id
	listID is the id of the to watch list with the movie
	movieID is the id of the movie to remove
	Disables other buttons related to the specified movie when used
*/
function removeFromToWatch(listID, movieID) {
	var button = document.getElementsByClassName("button" + movieID);
	var remove = document.getElementsByClassName("remove" + movieID);
	for (var a = button.length - 1; a >= 0; a--) {
		button[a].disabled = true;
		button[a].style.color = "grey";
		remove[a].disabled = true;
		remove[a].style.color = "grey";
	}
	$.ajax({
		type: "POST",
		url: BASE_URL + '/remove',
		data: {
			movieID: movieID,
			listID: listID,
			listName: "ToWatch"
		},
		success: function(data) {
			if (!data.success) {
				// display error message
				$('p.ajax-message').html(data.error);
			} else {
				//change the location of the button
				var watchRow = document.getElementsByClassName("watchRow" + movieID);
				watchRow[0].remove();
				var allRow = document.getElementsByClassName("allRow" + movieID);
				allRow[0].remove();
			}
		}
	}).fail(function(jqXHR, textStatus, errorThrown) {
		$('p.ajax-message').html(
			'Oops. Something went wrong. <strong>jqXHR</strong>: ' + jqXHR +
			'. <strong>textStatus</strong>: ' + textStatus +
			'. <strong>errorThrown</strong>: ' + errorThrown + '.');
	});

}
