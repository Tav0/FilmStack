<?php
include_once '../global.php';

class ReviewController {

	// route us to the appropriate class method for this page
	public function route($action) {
		switch($action) {
			case 'entercomment':
				$this->enterComment();
				break;
			case 'bancomment':
				$this->banComment();
				break;
		}
	}

    /*
     * Adds comments to the MySQL database.
     */
	private function enterComment()
	{
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			$responseArray = array();
			$message = '';        // message
			$success = false;     // false on error. true on success
			
			$username = $_POST['Username'];
			$movieName = $_POST['MovieName'];
			$comment = $_POST['Comment'];
			$rating = $_POST['Rating'];
			$movieID = $_POST['MovieID'];
			$endorse = $_POST['Endorse'];

			$db = Db::instance();
			$commentsTable = new CommentsTable($db);
			
			// attemp to insert review in DB
			if ($commentsTable->enterComment($username, $comment, $rating, $movieID, $endorse)) {
				// attempt to insert a 'review' record in DB
				$eventTable = new EventTable(Db::instance());
				if ($eventTable->createEvent('review', $_SESSION['userid'], null, $movieID, $movieName, $comment, $rating, $endorse)) {
					$success = true;

					// if the user endorsed the movie, attempt to insert a 'endorse' record in DB
					if (!empty($endorse)) {
						if (!$eventTable->createEvent('endorse', $_SESSION['userid'], null, $movieID, $movieName)) {
							$success = false;
							$message = 'Something went wrong attempting to insert a "review" record in the database';
						}
					}
				}
				else {
					$message = 'Something went wrong attempting to insert a "review" record in the database';
				}
			}
			// inserting review attempt failed
			else {
				$message = 'Something went wrong attempting to insert a new record in the database';
			}

			$responseArray = array(
				'success' => $success,
				'message' => $message,
				'moderator' => $_SESSION['type'],
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
	 * Deletes a comment that's deemed inappropriate by the moderator.
	 */
	private function banComment() {
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			$responseArray = array();
			$message = '';        // message
			$success = false;     // false on error. true on success
			
			$username = $_POST['Username'];
			$comment = $_POST['Comment'];

			$db = Db::instance();
			$commentsTable = new CommentsTable($db);
			
			$commentsTable->deleteComment($username, $comment);
			$success = true;

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
}
