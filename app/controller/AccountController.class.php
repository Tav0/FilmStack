<?php
include_once '../global.php';

class AccountController {

	// route us to the appropriate class method for this page
	public function route($action) {
		switch($action) {
			case 'signup':
				$this->signup();
				break;
			case 'password':
				$this->password();
				break;
			case 'settings':
				$this->settings();
				break;
			case 'username':
				$this->username();
				break;
			case 'email':
				$this->email();
				break;
			case 'firstname':
				$this->firstName();
				break;
			case 'lastname':
				$this->lastName();
				break;
			case 'follow':
				$this->follow();
				break;
			case 'unfollow':
				$this->unfollow();
				break;
            case 'moderator':
                $this->moderator();
                break;
			case 'ismoderator':
				$this->isModerator();
				break;
			case 'promote':
				$this->promote();
				break;
			case 'getuserdata':
				$this->getuserdata();
				break;
        }
	}

	/*
	 * Return an associative array array of the form:
	 [
		 'valid'   => boolean,   // indicates wheter password is valid
		 'messages' => [         // array of messages describing reasons why password is invalid
			 index: string
		 ]
	 ]
	 * A valid password contains at least 8 characters,
	 * must contain an uppercase character A-Z, lowercase character a-z,
	 * a digit 0-9, and allows $^!?_
	 */
	private function validatePassword($password) {
		$valid = true;
		$messages = array();

		// check contains an uppercase char
		if (!preg_match('/[A-Z]/', $password)) {
			$valid = false;
			$message = 'password does not contain an uppercase character';
			array_push($messages, $message);
		}
		// check contains a lowercase char
		if (!preg_match('/[a-z]/', $password)) {
			$valid = false;
			$message = 'password does not contain an lowercase character';
			array_push($messages, $message);
		}
		// check contains a digit 0-9
		if (!preg_match('/[0-9]/', $password)) {
			$valid = false;
			$message = 'password does not contain a digit';
			array_push($messages, $message);
		}
		// check password does not contain anything other than
		// a-z, A-Z, 0-9, $, !, ?, _, ^, @
		if (preg_match('/[^a-zA-Z0-9\$!\?_\^@]/', $password)) {
			$valid = false;
			$message = "password contains something other than a-z, A-Z, 0-9, $, !, ?, _, ^, @";
			array_push($messages, $message);
		}

		// check password is at least 8 characters long
		if (!preg_match('/.{8,}/', $password)) {
			$valid = false;
			$message = "password must be at least 8 characters long";
			array_push($messages, $message);
		}

		$validationResult = array(
			'valid' => $valid,
			'messages' => $messages
		);

		return $validationResult;
	}

	/*
	 * Return whether email contains an at symbol
	 */
	private function emailContainsAtSymbol($email) {
		return (strpos($email, '@') !== false);
	}

	/*
	 * Return whether email contains something after the '@' symbol
	 * precondition: $email contains an '@' symbol
	 */
	private function emailContainsSuffix($email) {
		$atSymbolPosition = strPos($email, '@');
		$substring = substr($email, $atSymbolPosition);
		if (strlen($substring) > 1) {
			return true;
		}
		return false;
	}


		/*
		 signup:
		 - GET:
						- if user not logged in: display signup page
						- if user logged in: redirect to base url
		 - POST:
						- requires params 'username' 'email' and 'password'
						- if username and email do not already exist, create user
						- send JSON response:
						{
										success: success,           // true or false
										message: message,           // error message if username or email already exists in db
										url: url                    // url to redirect user to on success
										inputToFocus: inputToFocus  // name of the input field to focus on
						}
		 */
	public function signup() {
		if ($_SERVER['REQUEST_METHOD'] === 'GET') {
			// redirect already logged in users
			if ((isset($_SESSION['username']) && $_SESSION['username'] != '')) {
				header('Location: '.BASE_URL);
			}

			$title = 'Sign Up';
			include_once SYSTEM_PATH.'/view/signup.tpl';            
		}
		else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			$responseArray = array();
			$message = '';        // message
			$success = false;     // false on error. true on success
			$url = '';            // empty on error. url for redirecting user on success.
			$inputToFocus = '';   // value of the "name" attribute on the input element to focus on, on error. empty on success.

			$username = $_POST['username'];
			$email = $_POST['email'];
			$password = $_POST['password'];
			$firstName = $_POST['firstname'];
			$lastName = $_POST['lastname'];

			$db = Db::instance();
			$userTable = new UserTable($db);
			$listsTable = new ListsTable($db);

			// check if username already exists in db
			$user = $userTable->getUserByUsername($username);

			$validation = $this->validatePassword($password);

			if (!empty($user)) {
				$message = "username '{$username}' is already taken";
				$inputToFocus = 'username';
			}
			// check if email already exists in db
			else if (!empty($userTable->getUserByEmail($email))) {
				$message = "email '{$email}' is already taken";
				$inputToFocus = 'email';
			}
			// check if email contains '@' symbol
			else if (!$this->emailContainsAtSymbol($email)) {
				$message = "email '{$email}' does not contain an '@' symbol";
				$inputToFocus = 'email';
			}
			// check if email contains something after the '@' symbol
			else if (!$this->emailContainsSuffix($email)) {
				$message = "email '{$email}' does NOT have something after the '@' symbol";
				$inputToFocus = 'email';
			}
			else if(!$validation['valid']) {
				$message = "";
				$messages = $validation['messages'];
				for($i = 0; $i < count($messages); $i++)
				{
					//$message = "Hi";
					$message = $message . $messages[$i] . "<br />";
				}
				$inputToFocus = 'password';
			}
			else if(trim($firstName) == "") {
				$message = "first name cannot be empty";
				$inputToFocus = "firstName";
			}
			else if(trim($lastName) == "") {
				$message = "last name cannot be empty";
				$inputToFocus = "lastName";
			}
			// create the user
			else if($result = $userTable->createUser($username, $email, $password, $firstName, $lastName)) {
				$userID = $db->connection()->lastInsertId();
				$result = $listsTable->createUserLists($userID, "ToWatch");
				$toWatchID = $db->connection()->lastInsertId();
				$result = $listsTable->createUserLists($userID, "Watched");
				$watchedID = $db->connection()->lastInsertId();
				$userTable->updateToWatchID($userID, $toWatchID);
				$userTable->updateWatchedID($userID, $watchedID);

				// set the session
				$_SESSION['username'] = $username;
				$_SESSION['userid'] = $userID;
				$_SESSION['type'] = 'Registered';
				$success = true;
				$message = "user created successfully";
				$url = BASE_URL;

			}
			// the insert statement failed
			else {
				$message = "Oops. Something went wrong attempting to create a user.";
				$inputToFocus = 'username';
			}

			$responseArray = array(
				'success' => $success,
				'message' => $message,
				'url'     => $url,
				'inputToFocus' => $inputToFocus
			);

			$responseJson = json_encode($responseArray);
			header('Content-Type: application/json');
			echo $responseJson;
		}
	}

	/*
		send AJAX response indicating whether the password is okay or not
	 */
	public function password() {
		$available = true;
		$message = 'password param not set';

		if (isset($_GET['password']) && $_GET['password'] !== '') {
			$password = $_GET['password'];

			//validate the password
			$validation = $this->validatePassword($password);

			//Get all the messages about the password
			$message = "";
			$messages = $validation['messages'];
			for($i = 0; $i < count($messages); $i++)
			{
				$message = $message . $messages[$i] . "<br />";
			}
			$available = $validation['valid'];
		}
		//there were no spaces (is empty)
		else
		{
			$available = false;
		}

		$responseArray = array(
			'available' => $available,
			'message' => $message,
		);

		$responseJson = json_encode($responseArray);
		header('Content-Type: application/json');
		echo $responseJson;
	}

	/*
	 * Update the user specified by $userID
	 * by setting the field specified by $field to the
	 * value specified by $value
	 */
	private function updateUserField($field, $value) {
		$db = Db::instance();
		$userTable = new UserTable($db);
		$user = $userTable->getUserByUsername($_SESSION['username']);

		$methodToUse = '';
		switch($field) {
		case 'FirstName':
			$methodToUse = 'updateFirstName';
			$messageField = 'First Name';
			break;
		case 'LastName':
			$methodToUse = 'updateLastName';
			$messageField = 'Last Name';
			break;
		case 'Email':
			$methodToUse = 'updateEmail';
			$messageField = 'Email';
			break;
		case 'Password':
			$methodToUse = 'updatePassword';
			$messageField = 'Password';
			break;
		case 'FeedLimit':
			$methodToUse = 'updateFeedLimit';
			$messageField = 'Activity Feed Limit';
			break;
		case 'BubbleLimit':
			$methodToUse = 'updateBubbleLimit';
			$messageField = 'Graphic Bubble Limit';
			break;
		}

		$message = '';
		if ($userTable->$methodToUse($user['UserID'], $value)) {
			$message = "{$messageField} updated to {$value}";
		} else {
			$message = "Update failed, could not update {$messageField}";
		}
		return $message;
	}


	/*
	 * precondition: user is logged in (i.e., $_SESSION['username'] is set and is not the empty string)
	 *
	 * Send JSON to client in the following form
	{
		messages: [
			index: string
		]
}
	 */
	private function handleSettingsPost($postArgs) {
		$messages = array();

		if (isset($postArgs['firstName']) && $postArgs['firstName'] !== '') {
			$message = $this->updateUserField('FirstName', $postArgs['firstName']);
			array_push($messages, $message);
		}

		if (isset($postArgs['lastName']) && $postArgs['lastName'] !== '') {
			$message = $this->updateUserField('LastName', $postArgs['lastName']);
			array_push($messages, $message);
		}

		if (isset($postArgs['email']) && $postArgs['email'] !== '') {
			// todo: validate email, in case user forged their own 'email' post argument
			$message = $this->updateUserField('Email', $postArgs['email']);
			array_push($messages, $message);
		}

		if (isset($postArgs['password']) && $postArgs['password'] !== '') {
			$message = '';
			$validationResult = $this->validatePassword($postArgs['password']);

			if ($validationResult['valid']) {
				$message = $this->updateUserField('Password', $postArgs['password']);
				array_push($messages, $message);
			} else {
				$validationMessages = $validationResult['messages'];
				for ($i = 0; $i < count($validationMessages); $i++) {
					array_push($messages, $validationMessages[$i]);
				}
			}
		}

		if (isset($postArgs['feedLimit']) && $postArgs['feedLimit'] !== '' &&
			($postArgs['feedLimit'] == 50 || $postArgs['feedLimit'] == 25 || $postArgs['feedLimit'] == 10)) {
			$message = $this->updateUserField('FeedLimit', $postArgs['feedLimit']);
			array_push($messages, $message);
		}

		if (isset($postArgs['bubbleLimit']) && $postArgs['bubbleLimit'] !== '' && 
			($postArgs['bubbleLimit'] == 50 || $postArgs['bubbleLimit'] == 25 || $postArgs['bubbleLimit'] == 10)) {
			$message = $this->updateUserField('BubbleLimit', $postArgs['bubbleLimit']);
			array_push($messages, $message);
		}

		$responseArray = array(
			'messages' => $messages
		);

		$responseJson = json_encode($responseArray);
		header('Content-Type: application/json');
		echo $responseJson;
	}


	/*
	 * Handle GET and POST requests for modifying user settings
	 */
	private function settings() {
		// redirect user if not logged in
		if ((!isset($_SESSION['username']) || $_SESSION['username'] == '')) {
			header('Location: '.BASE_URL);
		}

		if ($_SERVER['REQUEST_METHOD'] === 'GET') {
			$title = 'Settings';

			// supply template with user
			$db = Db::instance();
			$userTable = new UserTable($db);
			$user = $userTable->getUserByUsername($_SESSION['username']);

			include_once SYSTEM_PATH.'/view/settings.tpl';
			include_once SYSTEM_PATH.'/view/footer.tpl';
		}
		else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			$this->handleSettingsPost($_POST);
		}
	}


	/*
		send AJAX response indicating whether username exists
	 */
	public function username() {
		$available = true;
		$message = 'username param not set';

		if (isset($_GET['username']) && $_GET['username'] !== '') {
			$username = $_GET['username'];

			$db = Db::instance();
			$userTable = new UserTable($db);
			$user = $userTable->getUserByUsername($username);

			// check to see if the username exists
			if (!empty($user)) {
				$available = false;
				$message = "username '{$username}' is already taken";
			}
			//is the username empty?
			else if(trim($username) == "") {
				$available = false;
			}
			else {
				$message = "username '{$username}' is available";
			}
		}
		//the username had no spaces (is empty)
		else {
			$available = false;
		}

		$responseArray = array(
			'available' => $available,
			'message' => $message,
		);

		$responseJson = json_encode($responseArray);
		header('Content-Type: application/json');
		echo $responseJson;
	}

	/*
		send AJAX response indicating whether email exists
	 */
	public function email() {
		$available = true;
		$message = 'email param not set';

		if (isset($_GET['email']) && $_GET['email'] !== '') {
			$email = $_GET['email'];

			$db = Db::instance();
			$userTable = new UserTable($db);
			$user = $userTable->getUserByEmail($email);

			// check if email already exists in db
			if (!empty($user)) {
				$available = false;
				$message = "this email address, '{$email}', already has an account";
			}
			//is the email empty
			else if(trim($email) == "")
			{
				$available = false;
			}
			else {
				$message = "email address '{$email}' is available";
			}
		}
		//the email had no spaces (is empty)
		else
		{
			$available = false;
		}

		$responseArray = array(
			'available' => $available,
			'message' => $message,
		);

		$responseJson = json_encode($responseArray);
		header('Content-Type: application/json');
		echo $responseJson;
	}

	/*
		Use an Ajax call to see if the first name is empty or not
	 */
	function firstName() {
		$available = true;
		$message = 'first name param not set';

		if (isset($_GET['firstname']) && $_GET['firstname'] !== '') {
			$firstName = $_GET['firstname'];

			// were there only spaces?
			if (trim($firstName) == "") {
				$available = false;
			}
			else {
				$message = "first name is okay";
			}
		}
		//the string was completely empty
		else {
			$available = false;
		}

		$responseArray = array(
			'available' => $available,
			'message' => $message,
		);

		$responseJson = json_encode($responseArray);
		header('Content-Type: application/json');
		echo $responseJson;
	}

	/*
		An Ajax call to this function to see if the last name was empty
	 */
	function lastName() {
		$available = true;
		$message = 'last name param not set';

		if (isset($_GET['lastname']) && $_GET['lastname'] !== '') {
			$lastName = $_GET['lastname'];

			// was the last name only spaces?
			if (trim($lastName) == "") {
				$available = false;
			}
			else {
				$message = "last name is okay";
			}
		}
		//the last name was completely empty
		else {
			$available = false;
		}

		$responseArray = array(
			'available' => $available,
			'message' => $message,
		);

		$responseJson = json_encode($responseArray);
		header('Content-Type: application/json');
		echo $responseJson;
	}

	/*
	 * endpoint for following a user
	 */
	public function follow() {
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			$success = false;
			$message = '';
			$profileId = null;

			if (isset($_GET['profileid']) && $_GET['profileid'] !== '') {
				$db = Db::instance();
				$followerTable = new FollowerTable($db);

				$followerId = null;
				$leaderId = null;
				if (isset($_SESSION['userid']) && $_SESSION['userid'] !== '') {
					$followerId = $_SESSION['userid'];
				}
				$leaderId = $_GET['profileid'];

				// check if leader is in db
				$userTable = new UserTable($db);
				$leader = $userTable->getUserByUserID($leaderId);
				if (!empty($leader)) {
					// leader found in db

					// check if follower relationship already exists
					if (!$followerTable->relationshipExists($followerId, $leaderId)) {
						// attempt to insert new record in Follower table
						if ($followerTable->followUser($followerId, $leaderId)) {
							$success = true;
							$profileId = $leaderId;
							$leaderUsername = $leader['Username'];
							$message = "following <strong>{$leaderUsername}</strong>";

							// attempt to record "following" event in Event table
							$eventTable = new EventTable(Db::instance());
							if (!$eventTable->createEvent('follow', $followerId, $leaderId)) {
								$message = "Something went wrong attempting to insert the 'follow' event for {$_SESSION['username']} following {$leaderUsername}";
							}
						} else {
							$message = 'Something went wrong attempting to follower user';
						}
					} else {
						$message = "Follower {$followerId} is already following Leader {$leaderId}";
					}
				}
				else {
					// leader not found in db
					$message = 'leader not found in db';
				}
			}
			else {
				$message = 'GET parameter "profileid" either is not set or is empty';
			}

			$responseArray = array(
				'success' => $success,
				'message' => $message,
				'profileId' => $profileId,
				'userId' => $_SESSION['userid'],
				'username' => $_SESSION['username']
			);

			$responseJson = json_encode($responseArray);
			header('Content-Type: application/json');
			echo $responseJson;
		}
	}


	/*
	 * endpoint for unfollowing a user
	 */
	public function unfollow() {
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			$success = false;
			$message = 'unfollowing user id '.$_GET['profileid'];
			$profileId = null;

			if (isset($_GET['profileid']) && $_GET['profileid'] !== '') {
				$db = Db::instance();
				$followerTable = new FollowerTable($db);

				$followerId = null;
				$leaderId = null;
				if (isset($_SESSION['userid']) && $_SESSION['userid'] !== '') {
					$followerId = $_SESSION['userid'];
				}
				$leaderId = $_GET['profileid'];

				// check if leader is in db
				$userTable = new UserTable($db);
				$leader = $userTable->getUserByUserID($leaderId);
				if (!empty($leader)) {
					// leader found in db

					// make sure that relationship exists
					if ($followerTable->relationshipExists($followerId, $leaderId)) {
						// attempt to remove the relationship in Follower table
						if ($followerTable->unfollowUser($followerId, $leaderId)) {
							$success = true;
							$profileId = $leaderId;
							$leaderUsername = $leader['Username'];
							$message = "unfollowed <strong>{$leaderUsername}</strong>";

							// attempt to record "following" event in Event table
							$eventTable = new EventTable(Db::instance());
							if (!$eventTable->createEvent('unfollow', $followerId, $leaderId)) {
								$message = "Something went wrong attempting to insert the 'unfollow' event for {$_SESSION['username']} following {$leaderUsername}";
							}
						} else {
							$message = 'Something went wrong attempting to unfollower user';
						}
					} else {
						$message = "Follower {$followerId} is already not following Leader {$leaderId}";
					}
				}
				else {
					// leader not found in db
					$message = 'leader not found in db';
				}
			}
			else {
				$message = 'GET parameter "profileid" either is not set or is empty';
			}

			$responseArray = array(
				'success' => $success,
				'message' => $message,
				'profileId' => $profileId
			);

			$responseJson = json_encode($responseArray);
			header('Content-Type: application/json');
			echo $responseJson;
		}
	}

		/*
		Function that sets the moderator page
		Page is inaccessible for a regular and unregistered user
		 */
	private function moderator() {
		//Is the user logged in
		if ((!isset($_SESSION['username']) || $_SESSION['username'] == '')) {
			header('Location: '.BASE_URL .'/signup');
		}
		//Is the user a moderator
		if ((!isset($_SESSION['type']) || $_SESSION['type'] != 'Moderator')) {
			header('Location: '.BASE_URL);
		}
		$title = "Moderator";

		$db = Db::instance();
		$usersTable = new UserTable($db);
		$moderatorRows = $usersTable->getModerators();
		$moderators = "";
		while($moderator = $moderatorRows->fetch(PDO::FETCH_ASSOC))
		{
			$moderators = $moderators . '<p><a href="' . BASE_URL . '/profile/' . $moderator['UserID']. '">' . $moderator['Username'] . '</a></p>';
		}
		include_once SYSTEM_PATH . '/view/moderator.tpl';
	}

	/*
		send AJAX response indicating whether they are a moderator
	 */
	public function isModerator() {
		$okay = false;
		$message = '';

		//Is the field set?
		if (isset($_GET['username']) && $_GET['username'] !== '') {
			$username = $_GET['username'];


			$db = Db::instance();
			$userTable = new UserTable($db);
			$user = $userTable->getUserByUsername($username);

			// Does the user exist?
			if (empty($user)) {
				$message = "user '{$username}' does not exist";
			}
			// Yes, Is the username full of spaces?
			else if(trim($username) == "username param not set") {
				$message = "";
			}
			//No, is the user already a moderator?
			else if($user['Type'] == "Moderator"){
				$message = "user '{$username}' is already a moderator";
			}
			//No, hooray?
			else
			{
				$okay = true;
			}
		}
		else {
			$message = 'username param not set';
		}

		$responseArray = array(
			'okay' => $okay,
			'message' => $message
		);
		$responseJson = json_encode($responseArray);
		header('Content-Type: application/json');
		echo $responseJson;		
	}

	function getuserdata() {
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			$db = Db::instance();
			$listsTable = new ListsTable($db);
			$toWatchTable = new ToWatchTable($db);
			$watchedTable = new WatchedTable($db);
			$userID = $_SESSION['userid'];
			//Get the list ids
			$toWatchResult = $listsTable->getListByUserID($userID, "ToWatch");
			$toWatchID = $toWatchResult['ListID'];
			$watchedResult = $listsTable->getListByUserID($userID, "Watched");
			$watchedID = $watchedResult['ListID'];


			$responseArray = array(
				'username' => $_SESSION['username'],
				'userID' => $userID,
				'toWatchID' => $toWatchID,
				'watchedID' => $watchedID,
				'success' => true
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
		Ajax call to promote the a user to become a moderator
	 */
	function promote() {
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			$message = "";
			$success = true;
		
			$username = $_POST['username'];
			$db = Db::instance();
			$usersTable = new UserTable($db);
			$user = $usersTable->getUserByUsername($username);
			//Does the user exist
			if(empty($user))
			{
				$success = false;
				$message = "The user does not exist";
			}
			//Is the user already a moderator
			else if($user['Type'] == "Moderator") {
				$message = "The user is already a moderator";
				$success = false;
			}
			else
			{
				//promote the user
				$result = $usersTable->setUserType($user['UserID'], "Moderator");
			}
			
			
			$responseArray = array(
				'success' => $success,
				'message' => $message,
				'userID' => $user['UserID']
			);

			$responseJson = json_encode($responseArray);
			header('Content-Type: application/json');
			echo $responseJson;
		}
		else
		{
			header("Location: " . BASE_URL);
		}


		$responseArray = array(
			'success' => $success,
			'message' => $message,
			'userID' => $user['UserID']
		);

		$responseJson = json_encode($responseArray);
		header('Content-Type: application/json');
		echo $responseJson;
	}
}
