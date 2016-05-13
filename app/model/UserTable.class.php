<?php

/*
 Class for interacting with Users table
 */
class UserTable extends Table {
    // name of database table
    const DB_TABLE = 'Users';

    /*
     Create a user
     */
    public function createUser($username, $email, $password, $firstName, $lastName) {
        $sql = '
            INSERT INTO '.self::DB_TABLE.' (Username, Password, Email, FirstName, LastName, Type, FeedLimit, BubbleLimit)
            VALUES (:Username, :Password, :Email, :FirstName, :LastName, :Type, :FeedLimit, :BubbleLimit)';

        $params = array(
            ':Username' => $username,
            ':Password' => $password,
            ':Email' => $email,
			':FirstName' => $firstName,
			':LastName' => $lastName,
			':Type' => "Registered",
			':FeedLimit' => 10,
			':BubbleLimit' => 10
        );
        return $this->makeStatement($sql, $params);
    }

    /*
     Get fields of Username
     */
    public function getUserByUsername($username) {
        $sql = '
            SELECT *
            FROM '.self::DB_TABLE.'
            WHERE Username = :Username';

        $params = array(
            ':Username' => $username
        );

        $user = null;

        try {
            $userRow = $this->makeStatement($sql, $params);
            $user = $userRow->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo 'Execute query failed '.$e->getMessage();
        }

        return $user;
    }
    
	/*
     Get fields of UserID
     */
    public function getUserByUserID($userID) {
        $sql = '
            SELECT *
            FROM '.self::DB_TABLE.'
            WHERE UserID = :UserID';

        $params = array(
            ':UserID' => $userID
        );

        $user = null;

        try {
            $userRow = $this->makeStatement($sql, $params);
            $user = $userRow->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo 'Execute query failed '.$e->getMessage();
        }

        return $user;
    }
	
    /*
     Get user by Email
     */
    public function getUserByEmail($email) {
        $sql = '
            SELECT *
            FROM '.self::DB_TABLE.'
            WHERE Email = :email';

        $params = array(
            ':email' => $email
        );

        $user = null;

        try {
            $userRow = $this->makeStatement($sql, $params);
            $user = $userRow->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo 'Execute query failed '.$e->getMessage();
        }

        return $user;
    }
	
    /*
     Set the specified ToWatchID on the user with the specified UserId
     */
	public function updateToWatchID($userID, $toWatchID) {
        $sql = '
            UPDATE '.self::DB_TABLE.' SET ToWatchID = :ToWatchID WHERE UserID = :UserID';

        $params = array(
            ':UserID' => $userID,
            ':ToWatchID' => $toWatchID
        );
		
        return $this->makeStatement($sql, $params);
    }
	
    /*
     Set the specified WatchID on the user with the specified UserId
     */
	public function updateWatchedID($userID, $watchedID) {
        $sql = '
            UPDATE '.self::DB_TABLE.' SET WatchedID = :WatchedID WHERE UserID = :UserID';

        $params = array(
            ':UserID' => $userID,
            ':WatchedID' => $watchedID
        );
		
        return $this->makeStatement($sql, $params);
    }
    
    /*
     * Set the specified first name for the user specified by $userID
     */ 
    public function updateFirstName($userID, $firstName) {
        $sql = '
            UPDATE '.self::DB_TABLE.'
            SET FirstName = :FirstName
            WHERE UserID = :UserID';

        $params = array(
            ':UserID' => $userID,
            ':FirstName' => $firstName
        );
		
        return $this->makeStatement($sql, $params);
    }
    
    /*
     * Set the specified last name for the user specified by $userID
     */ 
    public function updateLastName($userID, $lastName) {
        $sql = '
            UPDATE '.self::DB_TABLE.'
            SET LastName = :LastName
            WHERE UserID = :UserID';

        $params = array(
            ':UserID' => $userID,
            ':LastName' => $lastName
        );
		
        return $this->makeStatement($sql, $params);
    }
    
    /*
     * Set the specified email for the user specified by $userID
     */ 
    public function updateEmail($userID, $email) {
        $sql = '
            UPDATE '.self::DB_TABLE.'
            SET Email = :Email
            WHERE UserID = :UserID';

        $params = array(
            ':UserID' => $userID,
            ':Email' => $email
        );
		
        return $this->makeStatement($sql, $params);
    }
    
    /*
     * Set the specified password for the user specified by $userID
     */ 
    public function updatePassword($userID, $password) {
        $sql = '
            UPDATE '.self::DB_TABLE.'
            SET Password = :Password
            WHERE UserID = :UserID';

        $params = array(
            ':UserID' => $userID,
            ':Password' => $password
        );
		
        return $this->makeStatement($sql, $params);
    }
    
    /*
     * Set the specified activity feed limit for the user specified by $userID
     */ 
    public function updateFeedLimit($userID, $limit) {
        $sql = '
            UPDATE '.self::DB_TABLE.'
            SET FeedLimit = :FeedLimit
            WHERE UserID = :UserID';

        $params = array(
            ':UserID' => $userID,
            ':FeedLimit' => $limit
        );
		
        return $this->makeStatement($sql, $params);
    }
	
	/*
		Returns the statement result with all the moderators (type = "Moderator")
	*/
	public function getModerators() {
		$sql = '
            SELECT *
            FROM '.self::DB_TABLE.'
            WHERE Type = :Type';

        $params = array(
            ':Type' => 'Moderator'
        );
		
		$moderators = null;

        try {
            $moderators = $this->makeStatement($sql, $params);
        } catch (PDOException $e) {
            echo 'Execute query failed '.$e->getMessage();
        }

        return $moderators;

	}
	
	/*
		Set a user's type, should be "Moderator" or "Registered"
	*/
	public function setUserType($userID, $type)
	{
		$sql = '
            UPDATE '.self::DB_TABLE.'
            SET Type = :Type
            WHERE UserID = :UserID';

        $params = array(
            ':UserID' => $userID,
            ':Type' => $type
        );
		
        return $this->makeStatement($sql, $params);
	}
	
	/*
		Set the bubble limit for a user
	*/
	public function updateBubbleLimit($userID, $bubbleLimit) {
		$sql = '
            UPDATE '.self::DB_TABLE.'
            SET BubbleLimit = :BubbleLimit
            WHERE UserID = :UserID';

        $params = array(
            ':UserID' => $userID,
            ':BubbleLimit' => $bubbleLimit
        );
		
        return $this->makeStatement($sql, $params);
	}
}
