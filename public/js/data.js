function preppingForD3_data(data) {
	//Formatting json data for D3


	var movieTotalCount = {
		"all": {},
		"mine": {
			"ToWatch": {},
			"Watched": {}
		}
	};

	for (listType in data) {
		if (listType != 'UserID') {
			for (movieID in data[listType]) {
				var usersMovieID = data[listType][movieID]['Users'];
				var movieName = data[listType][movieID]['MovieName'];
				var numUsers = usersMovieID.length;
				var checkUser = false;

				//iterate to check if current user exist
				usersMovieID.forEach(function(user) {
					if (user == data['UserID']) {
						checkUser = true;
					}
				});

				//if user not found then add to ALL list
				//else to MINE list
				if (checkUser == false) {
					if (movieTotalCount['all'].hasOwnProperty(movieID)) {
						movieTotalCount['all'][movieID] =
							numUsers + movieTotalCount['all'][movieID]['numUsers'];
					} else {
						movieTotalCount['all'][movieID] = {
							'movieName': movieName,
							'numUsers' : numUsers
						}
					}
				} else {
					if (listType == "ToWatch") {
						if (movieTotalCount['mine'][listType].hasOwnProperty(movieID)) {
							movieTotalCount['mine'][listType][movieID] =
								numUsers + movieTotalCount['mine'][listType][movieID]['numUsers'];
						} else {
							movieTotalCount['mine'][listType][movieID] = {
								'movieName': movieName,
								'numUsers' : numUsers
							}
						}
					} else {
						if (movieTotalCount['mine'][listType].hasOwnProperty(movieID)) {
							movieTotalCount['mine'][listType][movieID] =
								numUsers + movieTotalCount['mine'][listType][movieID]['numUsers'];
						} else {
							movieTotalCount['mine'][listType][movieID] = { 
								'movieName': movieName,
								'numUsers' : numUsers
							}
						}
					}
				}
			}
		}
	}

	//Delete movieID duplicates in ALL if MINE list has it
	for (dataMovie in movieTotalCount['mine']) {
		for (movieID in movieTotalCount['mine'][dataMovie]) {
			if (dataMovie == "ToWatch") {
				if (movieTotalCount['all'].hasOwnProperty(movieID)) {
					movieTotalCount['mine'][dataMovie][movieID]['numUsers'] += movieTotalCount['all'][movieID]['numUsers'];
					delete movieTotalCount['all'][movieID];
				}
			} else {
				if (movieTotalCount['all'].hasOwnProperty(movieID)) {
					movieTotalCount['mine'][dataMovie][movieID]['numUsers'] += movieTotalCount['all'][movieID]['numUsers'];
					delete movieTotalCount['all'][movieID];
				}
			}
		}
	}

	//Delete movieID if it has less than 2 users
	for (listType in movieTotalCount) {
		for (dataMovie in movieTotalCount[listType]) {
			if (listType == "all") {
				if (movieTotalCount[listType][dataMovie]['numUsers'] < 2) {
					delete movieTotalCount[listType][dataMovie];
				}
			} else {
				for (movieID in movieTotalCount[listType][dataMovie]) {
					if (dataMovie == "ToWatch") {
						if (movieTotalCount[listType][dataMovie][movieID]['numUsers'] < 2) {
							delete movieTotalCount[listType][dataMovie][movieID];
						}
					} else {
						if (movieTotalCount[listType][dataMovie][movieID]['numUsers'] < 2) {
							delete movieTotalCount[listType][dataMovie][movieID];
						}
					}
				}
			}
		}
	}

	for(listType in movieTotalCount) {
		if(Object.keys(movieTotalCount[listType]).length == 0) {
			return false;
		}
	}


	//D3 json format
	var dataSet = {
		"name": "movies",
		"children": [{
			"name": "allMoviesID",
			"children": []
		}, {
			"name": "myMoviesID",
			"children": [{
				"name": "ToWatch",
				"children": []
			}, {
				"name": "Watched",
				"children": []
			}]
		}]
	};

	//Arrays for movieID types
	var dataSetAll = [];
	var dataSetMineToWatch = [];
	var dataSetMineWatched = [];

	var bubbleLimit = $('#graph').attr('data-bubble-limit');

	var bubbleCounter = 0;

	//Placing movieIDs in their correct listType
	//and if user's has it then placing in ToWatch or Watched array
	for (listType in movieTotalCount) {
		for (dataMovie in movieTotalCount[listType]) {
			if (listType == 'all') {
				if (bubbleCounter == bubbleLimit) {
					break;
				}
				dataSetAll.push({
					name: dataMovie,
					movieName: movieTotalCount[listType][dataMovie]['movieName'],
					className: "Public",
					userID: data['UserID'],
					size: movieTotalCount[listType][dataMovie]['numUsers']
				});
				bubbleCounter++;
			} else {
				for (movieID in movieTotalCount[listType][dataMovie]) {
					if (dataMovie == "ToWatch") {
						if (bubbleCounter == bubbleLimit) {
							break;
						}
						dataSetMineToWatch.push({
							name: movieID,
							movieName: movieTotalCount[listType][dataMovie][movieID]['movieName'],
							className: dataMovie,
							userID: data['UserID'],
							size: movieTotalCount[listType][dataMovie][movieID]['numUsers']
						});
						bubbleCounter++;
					} else {
						if (bubbleCounter == bubbleLimit) {
							break;
						}
						dataSetMineWatched.push({
							name: movieID,
							movieName: movieTotalCount[listType][dataMovie][movieID]['movieName'],
							className: dataMovie,
							userID: data['UserID'],
							size: movieTotalCount[listType][dataMovie][movieID]['numUsers']
						});
						bubbleCounter++;
					}
				}
			}
		}
	}

	//Inserting arrays to json object for D3 to handle it
	dataSet['children'][0]['children'] = dataSetAll;
	dataSet['children'][1]['children'][0]['children'] = dataSetMineToWatch;
	dataSet['children'][1]['children'][1]['children'] = dataSetMineWatched;

	return dataSet;
}

//D3.js initialization
var drawData = function(movieData) {
	var diameter = 500;

	var svg = d3.select('#graph').append('svg')
		.attr('width', diameter)
		.attr('height', diameter)
		.attr('class', "bubble");

	var bubble = d3.layout.pack()
		.size([diameter, diameter])
		.value(function(d) {
			return d.size;
		})
	.padding(3);

	var vis = svg.selectAll('.node')
		.data(bubble.nodes(movieData)
				.filter(function(d) {
					return !d.children;
				}))
	.enter().append('g')
		.attr("class", "node")
		.attr("transform", function(d) {
			return "translate(" + d.x + "," + d.y + ")";
		});

	vis.append("title")
		.text(function(d) {
			return "User's" + d.className + ": " + d.size + " people";
		});

	// variables for distinguishing between single click and double click
	var DELAY = 700,
	clicks = 0,
	timer = null;

	vis.append('circle')
		.attr('r', function(d) {
			return d.r;
		})
	.attr('class', function(d) {
		return d.className;
	})
	.on("click", function(d) {
		clicks++; //count clicks
		if (clicks === 1) {

			timer = setTimeout(function() {
				// single click action

				if (d.className == "Watched" || d.className == "Public") {
					$.ajax({
						url: BASE_URL + '/watch',
						type: "POST",
						data: {
							userID: d.userID,
							movieID: d.name,
							movieName: d.movieName,
							deleteOld: (d.className == "Watched") ? true : false
						},
						dataType: "html",
						success: function(msg) {
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
						},
						error: function(err) {
							console.log(err);
						}
					});
				} else {
					$.ajax({
						url: BASE_URL + '/watched',
						type: "POST",
						data: {
							userID: d.userID,
							movieID: d.name,
							movieName: d.movieName,
							deleteOld: (d.className == "ToWatch") ? true : false
						},
						dataType: "html",
						success: function(msg) {
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
						},
						error: function(err) {
							console.log(err);
						}
					});
				}

				clicks = 0; //after action performed, reset counter
			}, DELAY);

		} else {
			//prevent single-click action
			clearTimeout(timer);

			// double click action:
			var listName = "";
			var listID;
			var movieID;

			movieID = d.name.substring(0, d.r / 3);
			$.ajax({
				type: "POST",
				url: BASE_URL + '/getuserdata/',
				data: {},
				success: function(data) {
					if (!data.success) {
						console.log('unsuccessful POST /getuserdata/');
					} else {
						if (d.className == "Watched") {
							listName = "Watched";
							listID = data.watchedID;
						} else if (d.className == "ToWatch") {
							listName = "ToWatch";
							listID = data.toWatchID;
						}

						$.ajax({
							type: "POST",
							url: BASE_URL + '/remove/',
							data: {
								listID: listID,
								movieID: movieID,
								listName: listName
							},
							success: function(data) {
								if (data.success) {
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
								} else {
									console.log('unsuccessful removing');
								}
							}
						});
					}
				}
			}).fail(function(jqXHR, textStatus, errorThrown) {
				console.log("ajax failed");
			});

			clicks = 0; //after action performed, reset counter
		}
	});

	vis.append('text')
		.attr("dy", ".3em")
		.attr('text-anchor', 'middle')
		.text(function(d) {
			if(typeof d.movieName != 'undefined'){
				return d.movieName.substring(0, d.r / 3);
			}
		})
		.style({
			"fill": "white",
			"font-family": "Helvetica Neue, Helvetica, Arial, san-serif",
			"font-size": "12px"
		})
		.on("click", function(d) { 
			window.open(BASE_URL + '/movie/' + d.name);
		});
};

$(document).ready(function() {

	$.ajax({
		url: BASE_URL + '/graphdata',
		dataType: "json",
		success: function(data) {
			var d3Json_data = preppingForD3_data(data);

			if (!d3Json_data) {
				$('#graph').append("<strong>There must be 2 or more users that have added a movie to their list</strong>");
			} else {
				//Run D3 to create a bubble chart
				drawData(d3Json_data);
			}
		},
		error: function(err) {
			console.log(err);
		}
	});
});
