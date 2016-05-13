<?php
include_once '../global.php';

class MovieController {

	// route us to the appropriate class method for this page
	public function route($action) {
		switch($action) {
			case 'movie':
				$id = $_GET['id'];
				if (isset($id) && $id !== '') {
					$this->movie($id);
				} else {
					// redirect to home page
					header('Location: '.BASE_URL);
				}
				break;
			case 'genres':
				$this->genres();
				break;
			case 'genre':
				// specific genre by id
				if (isset($_GET['id']) && $_GET['id'] !== '') {
					$id = $_GET['id'];
					$this->genre($id);
				}
				break;
			case 'genrepage':
				// specific genre by id
				if (isset($_GET['id']) && isset($_GET['page']) && $_GET['id'] !== '') {
					$id = $_GET['id'];
					$page = $_GET['page'];
					$this->genrepage($id, $page);
				}
				break;
		}
	}

    
	/*
		Gets and displays information about a specific movie
	 */
	public function movie($id) {
		$movie = new Movie($id);
		if($movie->error()){
			header("Location: " . BASE_URL);
		}
		//variable holding the add to watch list html
		$button = "";
		
		$db = Db::instance();
		$usersTable = new UserTable($db);
		$listsTable = new ListsTable($db);
		$watchedTable = new WatchedTable($db);
		$toWatchTable = new ToWatchTable($db);
		
		//Check to see if the user is logged in, if so, have the button variable
		//hold html
		if ((isset($_SESSION['username']) && $_SESSION['username'] != '')) {
			$userID = $_SESSION['userid'];
			$toWatchRow = $listsTable->getListByUserID($userID, 'ToWatch');
			$toWatchID = $toWatchRow['ListID'];
			$watchedRow = $listsTable->getListByUserID($userID, 'Watched');
			$watchedID = $watchedRow['ListID'];
			$disabled = 'disabled style="color: grey"';
			if(!($watchedTable->movieInListExist($watchedID, $id)) && !($toWatchTable->movieInListExist($toWatchID, $id)))
				$disabled = "";
			$button = '<input class="btn btn-info" id="button' . $id . '" type="button" value="Add To Watch List"
				onclick="addToWatchList(' . $id. ', \''. $movie->getTitle() . '\',' .$_SESSION['userid']. ')" ' . $disabled . '/>';
		}
		
		//Get the lists of people who want to watch and watched this movie
		$toWatch = "";
		$watched = "";
		$watchedResults = $watchedTable->getListIDsWithMovie($id);
		while($result = $watchedResults->fetch(PDO::FETCH_ASSOC)) {
			$listID = $result['ListID'];
			$listsRow = $listsTable->getUserByListID($listID, "Watched");
			$userID = $listsRow['UserID'];
			$user = $usersTable->getUserByUserID($userID);
			$username = $user['Username'];
			$watched = $watched . '<p><a href="' . BASE_URL . '/profile/' . $userID . '">' . $username . '</a></p>';
		}
		$toWatchResults = $toWatchTable->getListIDsWithMovie($id);
		while($result = $toWatchResults->fetch(PDO::FETCH_ASSOC)) {
			$listID = $result['ListID'];
			$listsRow = $listsTable->getUserByListID($listID, "ToWatch");
			$userID = $listsRow['UserID'];
			$user = $usersTable->getUserByUserID($userID);
			$username = $user['Username'];
			$toWatch = $toWatch . '<p><a href="' . BASE_URL . '/profile/' . $userID . '">' . $username . '</a></p>';
		}
		
		//Get the movie information
		$commentsTable = new CommentsTable($db);

		// title
		$movieTitle = $movie->getTitle();
		// genres
		$genres = $movie->getGenres();
		// overview
		$overview = $movie->getOverview();
		// poster
		$posterUrl = $movie->getPosterUrl();
		// popularity
		$popularity = $movie->getPopularity();
		// backdrop
		$backdropUrl = $movie->getBackdropUrl();;
		// production companies
		$productionCompanies = $movie->getProductionCompanies();
		// production countries
		$productionCountries = $movie->getProductionCountries();
		// release date
		$releaseDate = $movie->getReleaseDate();
		// revenue
		$revenue = $movie->getRevenue();
		// runtime
		$runtime = $movie->getRuntime();
		// tagline
		$tagline = $movie->getTagline();

		// Pulls the comments form the Comments databse
		$comments = $commentsTable->numComments($id);

        $commentDetails = NULL;
        while ($comment = $comments->fetch(PDO::FETCH_ASSOC)) 
        {
        	$commentDetails .= '<div>';
        	$commentDetails .= '<div id="commentHeader">';
        	$commentDetails .= '<label>'.$comment['Username'].'</label>';
        	$commentDetails .= ' <label>'.$comment['Rating'].'</label>';
        	$commentDetails .= ' <label>'.$comment['Endorse'].'</label>';
        	if (isset($_SESSION['username']) && $_SESSION['type'] == "Moderator")
        	{
        		$commentDetails .= ' <button class="banButton commentButton" username="'.$comment['Username'].'" comment="'.$comment['Comment'].'" > Delete</button>';
        	}
        	$commentDetails .= '</div>';
        	$commentDetails .= '<p id="comment">';
        	$commentDetails .=$comment['Comment'];
        	$commentDetails .= '</p>';
        	$commentDetails .= '</div>';
        }
        
		$title = "Movie &middot; {$movieTitle}";
		include_once SYSTEM_PATH.'/view/movie.tpl';
	}
    

	/*
		Gets the genres
	 */
	public function genres() {        
		$apiHandler = new APIHandler();

		// get genres
		$genresArray = $apiHandler->getGenres();

		//$html = '<pre>';
		$html = '';
		$genres = $genresArray['genres'];
		foreach($genres as $genre) {
			$url = BASE_URL.'/genre/'.$genre['id'];
			$html .= '<h3><a href="'.$url.'">'.$genre['name'].'</a></h3>';
		}
		//$html .= '</pre>';

		$title = 'Genres';
		include_once SYSTEM_PATH.'/view/genres.tpl';
	}
    
	/*
		Searches for movies in a genre and displays them

		$id is the genre id
	 */
	public function genre($id) {
		$apiHandler = new APIHandler();

		// specific genre
		if (!empty($id)) {

			// get genre
			$genreResult = $apiHandler->searchGenre($id);
			if($genreResult == null)
				header("Location: " . BASE_URL);
			$genres = $apiHandler->getGenres();
			$genreName = "";

			for ($count = 0; $count < count($genres["genres"]); $count++)
			{
				if ($genreResult["id"] == $genres["genres"][$count]["id"])
				{
					$genreName = $genres["genres"][$count]["name"];
				}
			}

			// Build the hyper links per each movie
			$html = '<div class="container">';
			$html .= '<div class="row">';
			$html .= $this->rowBuilder($genreResult, 0, 4);
			$html .= '</div>';

			// Build the hyper links per each movie
			$html .= '<div class="row">';
			$html .= $this->rowBuilder($genreResult, 4, 8);
			$html .= '</div>';

			// Build the hyper links per each movie
			$html .= '<div class="row">';
			$html .= $this->rowBuilder($genreResult, 8, 12);
			$html .= '</div>';

			// Build the hyper links per each movie
			$html .= '<div class="row">';
			$html .= $this->rowBuilder($genreResult, 12, 16);
			$html .= '</div>';

			// Build the hyper links per each movie
			$html .= '<div class="row">';
			$html .= $this->rowBuilder($genreResult, 16, 20);
			$html .= '</div>';
			$html .= '</div>';
			$title = 'Genre ID: '.$id;
			include_once SYSTEM_PATH.'/view/genre_page.tpl';
		}
	}

	/*
		Searches for movies in a genre and displays them

		$id is the genre id
	 */
	public function genrepage($id, $page) {
		$apiHandler = new APIHandler();

		// specific genre
		if (!empty($id)) {

			// If page number is lower than acceptable reset it to page 1
			if ($page < 1)
			{
				$page = 1;
			}

			// get genre
			$genreResult = $apiHandler->searchByGenre($id, $page);

			// Make sure that the page number is lower than the greatest possible page number
			// If it is greater then reset the page number to the greatest possible
			if ($page >= $genreResult["total_pages"])
			{
				$page = $genreResult["total_pages"];
				$genreResult = $apiHandler->searchByGenre($id, $page);	
			}

			$genres = $apiHandler->getGenres();
			$genreName = "";
			
			for ($count = 0; $count < count($genres["genres"]); $count++)
			{
				if ($genreResult["id"] == $genres["genres"][$count]["id"])
				{
					$genreName = $genres["genres"][$count]["name"];
				}
			}

			// Build the hyper links per each movie
			//print_r($genreResult);
			$html = '<div class="container">';
			$html .= '<div class="row">';
			$html .= $this->rowBuilder($genreResult, 0, 4);
			$html .= '</div>';

			// Build the hyper links per each movie
			$html .= '<div class="row">';
			$html .= $this->rowBuilder($genreResult, 4, 8);
			$html .= '</div>';

			// Build the hyper links per each movie
			$html .= '<div class="row">';
			$html .= $this->rowBuilder($genreResult, 8, 12);
			$html .= '</div>';

			// Build the hyper links per each movie
			$html .= '<div class="row">';
			$html .= $this->rowBuilder($genreResult, 12, 16);
			$html .= '</div>';

			// Build the hyper links per each movie
			$html .= '<div class="row">';
			$html .= $this->rowBuilder($genreResult, 16, 20);
			$html .= '</div>';
			$html .= '</div>';

			// Build the pagification at the bottom
			$nav = '<ul class="pagination">
                    <li id="left"><a href="#" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a></li>';
            if ($page >= $genreResult["total_pages"])
            {
            	$page = $genreResult["total_pages"];
            }
            $nav.=  '<li id="1"><a href="'. BASE_URL . '/genre/' . $genreResult["id"] . '/' . $page . '/' .'">' . $page++ . '</a></li>';
            
            if ($page >= $genreResult["total_pages"])
            {
            	$page = $genreResult["total_pages"];
            }
            $nav.=  '<li id="2"><a href="'. BASE_URL . '/genre/' . $genreResult["id"] . '/' . $page . '/' .'">' . $page++ . '</a></li>';
            
            if ($page >= $genreResult["total_pages"])
            {
            	$page = $genreResult["total_pages"];
            }
           	$nav.=  '<li id="3"><a href="'. BASE_URL . '/genre/' . $genreResult["id"] . '/' . $page . '/' .'">' . $page++ . '</a></li>';
            
            if ($page >= $genreResult["total_pages"])
            {
            	$page = $genreResult["total_pages"];
            }
            $nav.=  '<li id="4"><a href="'. BASE_URL . '/genre/' . $genreResult["id"] . '/' . $page . '/' .'">' . $page++ . '</a></li>';
            
            if ($page >= $genreResult["total_pages"])
            {
            	$page = $genreResult["total_pages"];
            }
            $nav.=  '<li id="5"><a href="'. BASE_URL . '/genre/' . $genreResult["id"] . '/' . $page . '/' .'">' . $page++ . '</a></li>';

            if ($page >= $genreResult["total_pages"])
            {
            	$page = $genreResult["total_pages"];
            }
            $nav.=  '<li id="6"><a href="'. BASE_URL . '/genre/' . $genreResult["id"] . '/' . $page . '/' .'">' . $page++ . '</a></li>';

            if ($page >= $genreResult["total_pages"])
            {
            	$page = $genreResult["total_pages"];
            }
            $nav.=  '<li id="7"><a href="'. BASE_URL . '/genre/' . $genreResult["id"] . '/' . $page . '/' .'">' . $page++ . '</a></li>';

            if ($page >= $genreResult["total_pages"])
            {
            	$page = $genreResult["total_pages"];
            }
            $nav.=  '<li id="8"><a href="'. BASE_URL . '/genre/' . $genreResult["id"] . '/' . $page . '/' .'">' . $page++ . '</a></li>';

            if ($page >= $genreResult["total_pages"])
            {
            	$page = $genreResult["total_pages"];
            }
            $nav.=  '<li id="9"><a href="'. BASE_URL . '/genre/' . $genreResult["id"] . '/' . $page . '/' .'">' . $page++ . '</a></li>';

            if ($page >= $genreResult["total_pages"])
            {
            	$page = $genreResult["total_pages"];
            }
            $nav.=  '<li id="10"><a href="'. BASE_URL . '/genre/' . $genreResult["id"] . '/' . $page . '/' .'">' . $page . '</a></li>';

            $nav.=  '<li id="right" max="' . $genreResult["total_pages"] . '""><a href="#" aria-label="Next"><span aria-hidden="true">&raquo;</span></a></li>
                  </ul>';

            // Set the title
			$title = $id;
			include_once SYSTEM_PATH.'/view/genre_page.tpl';
		}
	}

	private function rowBuilder($genreResult, $low, $high)
	{
		$html = "";
		for ($count = $low; $count < $high; $count++)
		{
			if ($count < count($genreResult["results"])) 
			{
				$html .= '<div class="col-md-3">';
					$url = BASE_URL . '/movie/' . $genreResult["results"][$count]["id"];
					$html .= '<div id="movie_deets">';
					$html .= '<a href="' . $url . '"><img id= "poster_pic" src="http://image.tmdb.org/t/p/original' . $genreResult["results"][$count]["poster_path"] . '"></img></a>';
					$html .= '<a href="' . $url . '">' . $genreResult["results"][$count]["title"] . '</a>';
					$html .= '</div>';
				$html .= '</div>';
			}
		}
		return $html;
	}
}
