<?php
include_once '../global.php';

class SiteController {

	// route us to the appropriate class method for this page
	public function route($action) {
		switch($action) {
			case 'main':
				$this->main();
				break;
			case 'login':
				$this->login();
				break;
			case 'logout':
				$this->logout();
				break;
			case 'profile':
				$this->profile();
				break;
			case 'graph':
				$this->graph();
				break;
			case 'graphdata':
				$this->graphdata();
				break;
            case 'search':
                    $this->search();
				break;
            case 'credits':
                    $this->credits();
                    break;
		}
	}


	/*
	 * Login:
	 * - POST:
	 *    - requires params 'username' and 'password'
	 *    - send JSON response:
	 *    {
	 *        success: success, // true or false
	 *        error: error,     // error message
	 *        url: url          // url to redirect user to
	 *    }
	 */
	public function login() {
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			$responseArray = array();
			$error = '';        // string error message on error. empty on success.
			$success = false;   // false on error. true on success
			$url = '';          // empty on error. url to send user to on success.

			$username = $_POST['username'];
			$password = $_POST['password'];

			$db = Db::instance();
			$userTable = new UserTable($db);

			// get user from db
			$user = $userTable->getUserByUsername($username);

			// empty username
			if (empty($user)) {
				$error = 'username does not exist';
			}
			// incorrect password
			else if ($password !== $user['Password']) {
				$error = 'incorrect  password';
			}
			// correct password
			else {
				$_SESSION['username'] = $username;
				$_SESSION['userid'] = $user['UserID'];
				$_SESSION['type'] = $user['Type'];

				$url = BASE_URL;
				$success = true;
			}

			$responseArray = array(
				'success' => $success,
				'error' => $error,
				'url' => $url
			);

			$responseJson = json_encode($responseArray);
			header('Content-Type: application/json');
			echo $responseJson;
		}
		else
		{
			header("Location: " . BASE_URL);
		}
	}

	/*
	 logout:
	 - unset session keys: 'username' and 'userid'
	 - destory session
	 - redirect user to base url
	 */
	public function logout() {
		// erase the session
		unset($_SESSION['username']);
		unset($_SESSION['userid']);
		unset($_SESSION['type']);
		session_destroy();

		// redirect to home page
		header('Location: '.BASE_URL);
	}

	/*
		Displays the upcoming movies
	 */
	private function displayUpcoming() {
		$apiHandler = new APIHandler();
		$upcoming = $apiHandler -> searchUpComing();
		$movieList = $upcoming['results'];
        return $movieList;
	}

	/*
	 * Return an array of associative array of the form
	 * [
	 *     'EventType'  => string
	 *     'User1ID'    => integer,
	 *     'User2ID'    => integer,
	 *     'MovieID'    => integer,
	 *     'Comment'    => string,
	 *     'Rating'     => string,
	 *     'Endorse'    => string,
	 *     'ListID'     => integer,
	 *     'Timestamp'  => string MySQL timestamp (e.g. 2016-04-06 19:41:27)
	 * ]
	 */
	private function getEvents() {
		$eventTable = new EventTable(Db::instance());
		return $eventTable->getEvents();
	}

	/*
	 * Return an array of associative array of the form
	 * [
	 *     'EventType'  => string
	 *     'User1ID'    => integer,
	 *     'User2ID'    => integer,
	 *     'MovieID'    => integer,
	 *     'Comment'    => string,
	 *     'Rating'     => string,
	 *     'Endorse'    => string,
	 *     'ListID'     => integer,
	 *     'Timestamp'  => string MySQL timestamp (e.g. 2016-04-06 19:41:27)
	 * ]
	 */
	private function getRelatedEvents() {
		$db = Db::instance();
		$followerTable = new FollowerTable($db);
		$eventTable = new EventTable($db);

		$leaders = $followerTable->getLeadersByFollowerId($_SESSION['userid']);
		$leaderIDs = array();
		for($i = 0; $i < count($leaders); $i++)
		{
			$leader = $leaders[$i];
			$leaderIDs[$i] = $leader['UserID'];
		}
		$events = $eventTable->getEventsForMultiple($leaderIDs);


		return $events;
	}

	/*
	 * Return an array of associative array of the form
	 * [
	 *     'EventType'  => string
	 *     'User1ID'    => integer,
	 *     'User2ID'    => integer,
	 *     'MovieID'    => integer,
	 *     'Comment'    => string,
	 *     'Rating'     => string,
	 *     'Endorse'    => string,
	 *     'ListID'     => integer,
	 *     'Timestamp'  => string MySQL timestamp (e.g. 2016-04-06 19:41:27)
	 * ]
	 *	
	 * The user id param is to find the events specific to that user
	 */
	private function getEventsForUserID($userID) {
		$eventTable = new EventTable(Db::instance());
		return $eventTable->getEventsForUserID($userID);
	}

	/*
	 * Return a string that describes the event
	 * precondition: $event is an array of the form
	 * [
	 *     'EventType'  => string
	 *     'User1ID'    => integer,
	 *     'User2ID'    => integer,
	 *     'MovieID'    => integer,
	 *     'Comment'    => string,
	 *     'Rating'     => string,
	 *     'Endorse'    => string,
	 *     'ListID'     => integer,
	 *     'Timestamp'  => string MySQL timestamp (e.g. 2016-04-06 19:41:27)
	 * ]
	 */
	private function makeEventString($event) {
		$eventString = '';        
		include_once SYSTEM_PATH.'/view/Helper.class.php';

		$eventTimeDescription = '';
		// if the activity's date is the same as today's date,
		// use relative time (e.g. "4 hours and 25 minutes ago")
		if (Helper::monthDateYear($event['Timestamp']) === Helper::monthDateYear('now')) {
			$currentTimestamp = Helper::currentTimestampMysqlFormat();
			$eventTimeDescription = Helper::relativeTime($event['Timestamp'], $currentTimestamp);
		}
		// else use the clock time (e.g. "at 12:30 pm")
		else {
			$eventTimeDescription = 'at '.Helper::clockTimeString($event['Timestamp']);
		}

		switch ($event['EventType']) {
		case 'follow':
			$userTable = new UserTable(Db::instance());
			$followerUser = $userTable->getUserByUserID($event['User1ID']);
			$leaderUser = $userTable->getUserByUserID($event['User2ID']);
			$followerUrl = BASE_URL.'/profile/'.$followerUser['UserID'];
			$leaderUrl = BASE_URL.'/profile/'.$leaderUser['UserID'];
			$eventString = "<span class='event-follow'><a href='{$followerUrl}'>{$followerUser['Username']}</a> followed <a href='{$leaderUrl}'>{$leaderUser['Username']}</a> {$eventTimeDescription}.</span>";
			break;
		case 'unfollow':
			$userTable = new UserTable(Db::instance());
			$followerUser = $userTable->getUserByUserID($event['User1ID']);
			$leaderUser = $userTable->getUserByUserID($event['User2ID']);
			$followerUrl = BASE_URL.'/profile/'.$followerUser['UserID'];
			$leaderUrl = BASE_URL.'/profile/'.$leaderUser['UserID'];
			$eventString = "<span class='event-unfollow'><a href='{$followerUrl}'>{$followerUser['Username']}</a> unfollowed <a href='{$leaderUrl}'>{$leaderUser['Username']}</a> {$eventTimeDescription}.</span>";
			break;
		case 'review':
			$userTable = new UserTable(Db::instance());
			$reviewer = $userTable->getUserByUserID($event['User1ID']);
			$reviewerUrl = BASE_URL.'/profile/'.$reviewer['UserID'];
			$movieUrl = BASE_URL.'/movie/'.$event['MovieID'];
			$eventString = "<span class='event-movie-review'><a href='{$reviewerUrl}'>{$reviewer['Username']}</a> reviewed <a href='{$movieUrl}'>{$event['MovieName']}</a> {$eventTimeDescription}.</span>";
			break;
		case 'endorse':
			$userTable = new UserTable(Db::instance());
			$endorser = $userTable->getUserByUserID($event['User1ID']);
			$reviewerUrl = BASE_URL.'/profile/'.$endorser['UserID'];
			$movieUrl = BASE_URL.'/movie/'.$event['MovieID'];
			$eventString = "<span class='event-movie-endorse'><a href='{$reviewerUrl}'>{$endorser['Username']}</a> endorsed <a href='{$movieUrl}'>{$event['MovieName']}</a> {$eventTimeDescription}.</span>";
			break;
		case 'watch':
			$userTable = new UserTable(Db::instance());
			$user = $userTable->getUserByUserID($event['User1ID']);
			$userUrl = BASE_URL.'/profile/'.$user['UserID'];
			$movieUrl = BASE_URL.'/movie/'.$event['MovieID'];
			$eventString = "<span class='event-movie-watch'><a href='{$userUrl}'>{$user['Username']}</a> wants to watch <a href='{$movieUrl}'>{$event['MovieName']}</a> {$eventTimeDescription}.</span>";
			break;
		case 'watched':
			$userTable = new UserTable(Db::instance());
			$user = $userTable->getUserByUserID($event['User1ID']);
			$userUrl = BASE_URL.'/profile/'.$user['UserID'];
			$movieUrl = BASE_URL.'/movie/'.$event['MovieID'];
			$eventString = "<span class='event-movie-watched'><a href='{$userUrl}'>{$user['Username']}</a> watched <a href='{$movieUrl}'>{$event['MovieName']}</a> {$eventTimeDescription}.</span>";
			break;
		}
		return $eventString;
	}

	/*
	 * Return HTML for the home feed
	 */
	private function makeHomeFeedHtml() {
		$homeFeedHtml = '';
		// check if user is logged in
		if (isset($_SESSION['userid']) && $_SESSION['userid'] !== '') {
			$events = $this->getRelatedEvents();

			// no events exist
			if (count($events) == 0) {
				$homeFeedHtml .= '<p>No activity to display</p>';
			}
			// follow events exists
			else {
				$prevMonthDateYear = '';
				foreach($events as $event) {
					// display a header for events that have the same date (e.g., "April 5, 2016")
					include_once SYSTEM_PATH.'/view/Helper.class.php';
					$monthDateYear = Helper::monthDateYear($event['Timestamp']);
					if ($prevMonthDateYear !== $monthDateYear) {
						$prevMonthDateYear = $monthDateYear;
						$homeFeedHtml .= "<h4>{$monthDateYear}</h4>";
					}

					$homeFeedHtml .= '<p>';
					$homeFeedHtml .= $this->makeEventString($event);
					$homeFeedHtml .= '</p>';
				}
			}
		}

		return $homeFeedHtml;
	}

	/*
	 * Return HTML for the profile feed
	 */
	private function makeProfileFeedHtml($profileID, $limit) {
		$profileFeedHtml = '';
		$events = $this->getEventsForUserID($profileID, $limit);
		// no events exist
		if (count($events) == 0) {
			$profileFeedHtml .= '<p>No activity to display</p>';
		}
		else {
			$prevMonthDateYear = '';
			$count = 0;
			// Get $count amount of events related to the user
			foreach($events as $event) {
				if($count == $limit)
					break;
				// display a header for events that have the same date (e.g., "April 5, 2016")
				include_once SYSTEM_PATH.'/view/Helper.class.php';
				$monthDateYear = Helper::monthDateYear($event['Timestamp']);
				if ($prevMonthDateYear !== $monthDateYear) {
					$prevMonthDateYear = $monthDateYear;
					$profileFeedHtml .= "<h4>{$monthDateYear}</h4>";
				}

				$profileFeedHtml .= '<p>';
				$profileFeedHtml .= $this->makeEventString($event);
				$profileFeedHtml .= '</p>';
				$count = $count + 1;
			}
		}
		return $profileFeedHtml;
	}

		/*
				Sets variables for the main page
		 */
	public function main() {
		$_displayUpcoming = "";
		$_displayGenre = "";

		$title = 'FilmStack';

		$homeFeedHtml = $this->makeHomeFeedHtml();

		$apiHandler = new APIHandler();
        $movieList = $this->displayUpcoming();
        
        
		include_once SYSTEM_PATH.'/view/main.tpl';
	}

	/*
		Gets the user information and creates variables to hold html
	 */
	public function profile() {
		$db = Db::instance();
		$usersTable = new UserTable($db);
		$listsTable = new ListsTable($db);
		$toWatchTable = new ToWatchTable($db);
		$watchedTable = new WatchedTable($db);
		$followerTable = new FollowerTable($db);

		$title = "Profile";

		//The username and userID will be the username and userID of the profile's owner
		$username = "";
		$userID = $_GET['profileid'];

		$user = $usersTable->getUserByUserID($userID);
		if($user == null)
			header("Location: " . BASE_URL);


		//true if the user is on their own profile
		$same = false;

		//true if logged in
		$loggedIn = false;

		//set the username
		if(isset($_SESSION['userid']) && $_SESSION['userid'] !== '') {
			$loggedIn = true;
			if($_SESSION['userid'] == $_GET['profileid']) {
				$same = true;
				$username = $_SESSION['username'];
			}
		}
		if(!$same) {
			$username = $user['Username'];
		}

		//Collect information about the user
		$userInformation = "";
		$userInformation = $userInformation . '<div class="col-sm-3"><span id="userInfo">Name:</span> ' . $user['FirstName'] . ' ' .
			$user['LastName'] . '</div>';
		$userInformation = $userInformation . '<div class="col-sm-3"><span id="userInfo">Email:</span> ' . $user['Email'] . ' ' . '</div>';
		$userInformation = $userInformation . '<div class="col-sm-3"><span id="userInfo">Account Type:</span> ' . $user['Type'] . ' ' . '</div>';

		$toWatchRow = $listsTable->getListByUserID($userID, 'ToWatch');
		$toWatchID = $toWatchRow['ListID'];
		$watchedRow = $listsTable->getListByUserID($userID, 'Watched');
		$watchedID = $watchedRow['ListID'];

		$toWatchCount = $toWatchTable->getCountForList($toWatchID);
		$watchedCount = $watchedTable->getCountForList($watchedID);

		/********Old code, might be removed later***********/
		$watchedMovies = $watchedTable->getMoviesByListID($watchedID);
		$watched = array();
		$count = 0;
		$watchedStr = "";
		$movieID = "";
		$userTable = new UserTable($db);
		$userRow = $userTable->getUserByUserID($userID);
		$userName = $userRow['Username'];

		while($row = $watchedMovies->fetch(PDO::FETCH_ASSOC)) {
			$movieID = $row['MovieID'];
			$time = $row['Timestamp'];
			$movie = new Movie($movieID);
			$movieName = $movie->getTitle();

			$watchedStr = $watchedStr . '<div class="col-md-3"><a href="' . BASE_URL . '/profile/'
				. $userID . '">'. $userName . '</a></div>' .
				'<div class="col-md-3">watched</div>' .
				'<div class="col-md-3">' . '<a href="' . BASE_URL . '/movie/' . $movieID 
				. '">'. $movieName . '</a></div>' . 
				'<div class="col-md-3">' . $time . '</div>';
		}
		/**********************************************************/

		//Get the followers and leaders
		$leaders = "";
		$followers = "";
		$peopleIFollowHtml = '';
		$followerTable = new FollowerTable(Db::instance());
		$peopleIFollow = $followerTable->getLeadersByFollowerId($userID);
		foreach($peopleIFollow as $person) {
			$leaders = $leaders . '<p><a href="' . BASE_URL . '/profile/' . $person['UserID'] . '">' . $person['Username'] . '</a></p>';
		}
		$peopleFollowingMe = $followerTable->getFollowersByLeaderId($userID);
		foreach($peopleFollowingMe as $person) {
			if($loggedIn && ($person['UserID'] == $_SESSION['userid']))
				$followers = $followers . '<p id="removeMe"><a href="' . BASE_URL . '/profile/' . $person['UserID'] . '">' . $person['Username'] . '</a></p>';
			else
				$followers = $followers . '<p><a href="' . BASE_URL . '/profile/' . $person['UserID'] . '">' . $person['Username'] . '</a></p>';
		}

		//Sets the button to be a follow or unfollow button
		$relationshipExists = false;
		if ($loggedIn)
			$relationshipExists = $followerTable->relationshipExists($_SESSION['userid'], $_GET['profileid']);

		$profileFeedHtml = $this->makeProfileFeedHtml($userID, $user['FeedLimit']);
		include_once SYSTEM_PATH . '/view/profile.tpl';
	}
    
    private function credits() {
        $title = 'Credits';
        include_once SYSTEM_PATH . '/view/credits.tpl';
    }

	public function graph() {
		if(isset($_SESSION['userid']) && $_SESSION['userid'] !== "") {
			$title = 'Graph';

			$userTable = new UserTable(Db::instance());
			$user = $userTable->getUserByUserID($_SESSION['userid']);
			$bubbleLimit = $user['BubbleLimit'];
			include_once SYSTEM_PATH . '/view/data.tpl';
			include_once SYSTEM_PATH . '/view/footer_main.tpl';
		}

		else
			header("Location: " . BASE_URL);
	}

	public function graphdata() {
		if ($_SERVER['REQUEST_METHOD'] === 'GET') {
			$db = Db::instance();
			$toWatchTable = new ToWatchTable($db);
			$watchedTable = new WatchedTable($db);
			$mostPopularMovies = array();
			$mostPopularMovies['ToWatch'] = $toWatchTable->getAllMoviesOrdered(true);
			$mostPopularMovies['Watched'] = $watchedTable->getAllMoviesOrdered(true);
			$list = $mostPopularMovies['ToWatch'];
			foreach($list as $id => $element)
			{
				$movie = new Movie($id);
				$element['MovieName'] = $movie->getTitle();
				$list[$id] = $element;
			}
			$mostPopularMovies['ToWatch'] = $list;
			$list = $mostPopularMovies['Watched'];
			foreach($list as $id => $element)
			{
				$movie = new Movie($id);
				$element['MovieName'] = $movie->getTitle();
				$list[$id] = $element;
			}
			$mostPopularMovies['Watched'] = $list;
			$mostPopularMovies['UserID'] = $_SESSION['userid'];

			header('Content-Type: application/json');
			$data = json_encode($mostPopularMovies);
			echo $data;

		}
	}

	private function validateSearchParams($params) {
		if (isset($params['searchterm']) && $params['searchterm'] !== '') {
			return true;
		}
		return false;
	}

	private function search() {
		if ($_SERVER['REQUEST_METHOD'] === 'GET') {

			// request came from the form
			if (isset($_GET['search-input'])) {
				// redirect to nice url
				header("Location: " . BASE_URL . "/search/{$_GET['search-input']}");
			}
			// request came from the redirect to the nice url /search/{searchterm} or /search/{searchterm}/{page}
			else {
				$resultsMessage = 'Invalid search';
				$searchIsValid = $this->validateSearchParams($_GET);
				if ($searchIsValid) {
					$searchParam = $_GET['searchterm'];

					$currentPage = '1';
					if (isset($_GET['page']) && $_GET['page'] !== '') {
						$currentPage = $_GET['page'];
					}

					// API only allows page param to be min 1 and max 1000
					$pageIsValid = $currentPage > 0 && $currentPage <= 1000;
					if ($pageIsValid) {
						$apiHandler = new APIHandler();
						$searchResults = $apiHandler->searchByName($_GET['searchterm'], $currentPage);
						$totalPages = $searchResults['total_pages'];
						$numResults = $searchResults['total_results'];

						$pageIsValid = $currentPage > 0 && $currentPage <= $totalPages;
						if ($pageIsValid) {
							$movieArray = $searchResults['results'];
							//$resultsMessage = "{$numResults} search results for <strong>{$searchParam}</strong>";
							$resultsMessage = '';
						}
						else {
							$resultsMessage = "Results page {$currentPage} does not exist";
						}                    
					} else {
						$resultsMessage = "Results page {$currentPage} does not exist";
					}
				}

				$title = 'Search Results';
				include_once SYSTEM_PATH.'/view/search-results.tpl';
			}
		}
		else {
			echo "Request method {$_SERVER['REQUEST_METHOD']} not supported";
		}
	}
}
