<?php
include_once '../global.php';

class ListController {

	// route us to the appropriate class method for this page
	public function route($action) {
		switch($action) {
			case 'watch':
				$this->watch();
				break;
			case 'watched':
				$this->watched();
				break;
			case 'remove':
				$this->remove();
				break;
			case 'list':
				$this->listPage();
				break;
		}
	}

	/*
		Add a row to the ToWatch Table
		Delete the old row in the Watched Table if deleteOld is set to true
	 */
	function watch() {
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			$responseArray = array();
			$message = '';        // message
			$success = false;     // false on error. true on success

			$userID = $_POST['userID'];
			$movieID = $_POST['movieID'];
			$movieName = $_POST['movieName'];
			$deleteOld = $_POST['deleteOld'];

			$db = Db::instance();
			$listsTable = new ListsTable($db);
			$toWatchTable = new ToWatchTable($db);
			$watchedTable = new WatchedTable($db);

			//Get the list ids
			$toWatchResult = $listsTable->getListByUserID($userID, "ToWatch");
			$toWatchID = $toWatchResult['ListID'];
			$watchedResult = $listsTable->getListByUserID($userID, "Watched");
			$watchedID = $watchedResult['ListID'];

			//Modify the database
			if(!$toWatchTable->movieInListExist($toWatchID, $movieID))
			{
				$toWatchTable->createMovieToWatch($toWatchID, $movieID);
				if($deleteOld === "true")
					$watchedTable->deleteMovieWatched($watchedID, $movieID);

				// attempt to record "watch" event in Event table
				$eventTable = new EventTable(Db::instance());
				if (!$eventTable->createEvent('watch', $userID, null, $movieID, $movieName, null, null, null, $toWatchID)) {
					$message = "Something went wrong attempting to insert the 'watch' event for {$_SESSION['username']}";
					$success = false;
				}
				else
					$success = true;
			}
			else
			{
				$message = "The movie already exists in the list!";
				$success = false;
			}

			$responseArray = array(
				'success' => $success,
				'message' => $message
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
		Add a new row to the Watched table
		Delete the old row in the ToWatch table if deleteOld is set to true
	 */
	function watched() {
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			$responseArray = array();
			$message = '';        // message
			$success = false;     // false on error. true on success

			$userID = $_POST['userID'];
			$movieID = $_POST['movieID'];
			$movieName = $_POST['movieName'];
			$deleteOld = $_POST['deleteOld'];

			$db = Db::instance();
			$listsTable = new ListsTable($db);
			$toWatchTable = new ToWatchTable($db);
			$watchedTable = new WatchedTable($db);

			//Get the list ids
			$toWatchResult = $listsTable->getListByUserID($userID, "ToWatch");
			$toWatchID = $toWatchResult['ListID'];
			$watchedResult = $listsTable->getListByUserID($userID, "Watched");
			$watchedID = $watchedResult['ListID'];

			//Modify the database
			if(!$watchedTable->movieInListExist($watchedID, $movieID))
			{
				if($deleteOld === "true")
					$toWatchTable->deleteMovieToWatch($toWatchID, $movieID);
				$watchedTable->createMovieWatched($watchedID, $movieID);
				// attempt to record "watched" event in Event table
				$eventTable = new EventTable(Db::instance());
				if (!$eventTable->createEvent('watched', $userID, null, $movieID, $movieName, null, null, null, $watchedID)) {
					$message = "Something went wrong attempting to insert the 'watched' event for {$_SESSION['username']}";
					$success = false;
				}
				else
					$success = true;
			}
			else
			{
				$message = "The movie already exists in the list!";
				$success = false;
			}

			$responseArray = array(
				'success' => $success,
				'message' => $message
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
		Ajax call to remove a move from a list
	*/
	function remove() {
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			$responseArray = array();
			$message = '';        // message
			$success = false;     // false on error. true on success

			$listID = $_POST['listID'];
			$movieID = $_POST['movieID'];
			$listName = $_POST['listName'];

			$db = Db::instance();
			$toWatchTable = new ToWatchTable($db);
			$watchedTable = new WatchedTable($db);

			//Modify the database
			if($listName === "Watched" && $watchedTable->movieInListExist($listID, $movieID))
			{
				$watchedTable->deleteMovieWatched($listID, $movieID);
				$success = true;
			}
			else if($listName === "ToWatch" && $toWatchTable->movieInListExist($listID, $movieID))
			{
				$toWatchTable->deleteMovieToWatch($listID, $movieID);
				$success = true;
			}
			else
			{
				$succuss = false;
			}

			$responseArray = array(
				'success' => $success,
				'message' => $message
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
     * Displays and set variables with html code for the list page
     */
	public function listPage() {
	
		$db = Db::instance();
		$listsTable = new ListsTable($db);
		$toWatchTable = new ToWatchTable($db);
		$watchedTable = new WatchedTable($db);
		$usersTable = new UserTable($db);
		
		$title = "Lists";
		//If the user is not logged in, set the $userID to something that it will never be
		if((!isset($_SESSION['username']) || $_SESSION['username'] == ''))
			$userID = -1;
		else
			$userID = $_SESSION['userid'];
			
		//Get the user id of the owner of the lsit
		$listsOwnerID = $_GET['listsownerid'];
		
		//Check to see if the user is the owner of the list
		$sameIDs = false;
		if($userID == $listsOwnerID) {
			$sameIDs = true;
		}
		else
		{
			$userID = $listsOwnerID;
		}
		$user = $usersTable->getUserByUserID($userID);
		if($user == null)
			header("Location: " . BASE_URL);
		$toWatchRow = $listsTable->getListByUserID($userID, 'ToWatch');
		$toWatchID = $toWatchRow['ListID'];
		$watchedRow = $listsTable->getListByUserID($userID, 'Watched');
		$watchedID = $watchedRow['ListID'];
		$watchedStmt = $watchedTable->getMoviesByListID($watchedID);
		$watched = array();
		$toWatch = array();
		$count = 0;
		$toWatchStmt = $toWatchTable->getMoviesByListID($toWatchID);
		$all = "<br />";
		$toWatchString = "<br />";
		$watchedString = "<br />";
		
		//Movies in To Watch
		while($row = $toWatchStmt->fetch(PDO::FETCH_ASSOC)) {
			$movieID = $row['MovieID'];
			$movie = new Movie($movieID);
			$movieName = $movie->getTitle();
			$toWatch[$count] = array("MovieID" => $movieID, "MovieName" => $movieName);
			$count++;
			$movieInfo = '<div class="col-xs-3 movieNameCol"><a href="' . BASE_URL . '/movie/' . $movieID. '">' . $movieName . '</a></div>';
			$buttons = "";
			if($sameIDs)
				$buttons = '<div class="col-xs-3 watch' . $movieID . '"><input class="btn btn-info addToWatchedButton button' . $movieID. '" 
					type="button" value="Add To Watched" onclick="addToWatched(' . $movieID. ', \'' . $movieName . '\',' .$_SESSION['userid']. ', ' . $toWatchID . ', \'true\')" /></div>
					<div class="col-xs-3 watched' .$movieID. '"></div>
					<div class="col-xs-3" id="removeItem"><input type="button" class="btn btn-info remove' . $movieID .
					'" value="Remove" onclick="removeFromToWatch(' . $toWatchID . ',' . $movieID . ')" /></div>';
			$all = $all . '<div class="row allRow' . $movieID . '">' . $movieInfo . $buttons . '</div>';
			$toWatchString = $toWatchString . '<div class="row watchRow' . $movieID . '">' . $movieInfo . $buttons . '</div>';
		}
		
		$count = 0;
		
		//Movies in Watched
		while($row = $watchedStmt->fetch(PDO::FETCH_ASSOC)) {
			$movieID = $row['MovieID'];
			$movie = new Movie($movieID);
			$movieName = $movie->getTitle();
			$watched[$count] = array("MovieID" => $movieID, "MovieName" => $movieName);
			$count++;
			$movieInfo = '<div class="col-xs-3 movieNameCol"><a href="' . BASE_URL . '/movie/' . $movieID. '">' . $movieName . '</a></div>';
			if($sameIDs)
				$buttons = '<div class="col-xs-3 watch' .$movieID. '"></div>
					<div class="col-xs-3 watched' . $movieID . '"><input class="btn btn-info addToWatchButton button' . $movieID . '" 
					type="button" value="Add To Watch" onclick="addToWatch(' . $movieID. ', \'' . $movieName . '\',' .$_SESSION['userid']. ', ' . $watchedID . ', \'true\')" /></div>
					<div class="col-xs-3" id="removeItem"><input type="button" class="btn btn-info remove' . $movieID .
					'" value="Remove" onclick="removeFromWatched(' . $watchedID . ',' . $movieID . ')" /></div>';
			$all = $all . '<div class="row allRow' . $movieID . '">' . $movieInfo . $buttons . '</div>';
			$watchedString = $watchedString . '<div class="row watchedRow' . $movieID . '">' . $movieInfo . $buttons . '</div>';
			
		}

		include_once SYSTEM_PATH.'/view/list.tpl';
	}
}
