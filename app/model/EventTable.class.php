<?php

/*
 Class for interacting with Follower table
 */
class EventTable extends Table {
    // name of database table
    const DB_TABLE = 'Events';

    /*
     * Return all the event records in the table ordered in reverse
     * chronological order (newest first, oldest last)
     */
    public function getEvents() {
        $sql = '
            SELECT *
            FROM '.self::DB_TABLE.'
            ORDER BY Timestamp DESC
            LIMIT 50';
        
        $stmt = $this->makeStatement($sql);
        
        $events = array();
        while (!empty($row = $stmt->fetch(PDO::FETCH_ASSOC))) {
            array_push($events, $row);
        }

        return $events;
    }
	
	/*
     * Return all the event records in the table ordered in reverse
     * chronological order (newest first, oldest last) based on a certain user
     */
    public function getEventsForUserID($userID, $limit=null) {
        $sql = '';
        $params = null;
        
        if (empty($limit)) {
            $sql = '
                SELECT *
                FROM '.self::DB_TABLE.'
                WHERE User1ID = :UserID ORDER BY Timestamp DESC';
                
            $params = array(":UserID" => $userID);

        } else {
            $sql = '
                SELECT *
                FROM '.self::DB_TABLE.'
                WHERE User1ID = :UserID
                ORDER BY Timestamp DESC
                LIMIT :Limit';

                $params = array(
                    ':UserID' => $userID,
                    ':Limit'  => $limit
                );
        }

        
		
        $stmt = $this->makeStatement($sql, $params);
        
        $events = array();
        while (!empty($row = $stmt->fetch(PDO::FETCH_ASSOC))) {
            array_push($events, $row);
        }

        return $events;
    }
    
    /*
     * Insert an event
     * An event can be one of the following types:
     * 'follow'
     * 'unfollow'
     * 'review'
     * 'endorse'
     * 'watch'
     * 'watched'
     */
    public function createEvent($eventType,
                                $user1Id,
                                $user2Id=null,
                                $movieId=null,
								$movieName=null,
                                $comment=null,
                                $rating=null,
                                $endorse=null,
                                $listId=null) {
        $sql = '
            INSERT INTO '.self::DB_TABLE.' (EventType, User1ID, User2ID, MovieID, MovieName, Comment, Rating, Endorse, ListID)
            VALUES (:EventType, :User1ID, :User2ID, :MovieID, :MovieName, :Comment, :Rating, :Endorse, :ListID)';

        $params = array(
            ':EventType' => $eventType,
            ':User1ID' => $user1Id,
            ':User2ID' => $user2Id,
            ':MovieID' => $movieId,
			':MovieName' => $movieName,
            ':Comment' => $comment,
            ':Rating' => $rating,
            ':Endorse' => $endorse,
            ':ListID' => $listId
        );
        return $this->makeStatement($sql, $params);
    }
	
	//Send in an array of user ids, where the indexes go from 0 onwards
	//Returns an array of 50 elements at max of events from those specified user ids
	public function getEventsForMultiple($arrayOfUserIds) {
		if(count($arrayOfUserIds) == 0)
			return array();
		$sqlPart1 = 'SELECT * FROM '.self::DB_TABLE;
		$sqlPart2 = ' WHERE ';
		$sqlPart3 = 'ORDER BY Timestamp DESC LIMIT 50';
		$params = array();
		for($i = 0; $i < count($arrayOfUserIds); $i++)
		{
			if($i != (count($arrayOfUserIds) - 1))
				$sqlPart2 = $sqlPart2 . ' User1ID = :userid' . $i . ' OR ';
			else
				$sqlPart2 = $sqlPart2 . ' User1ID = :userid' . $i . ' ';
			$params[':userid'.$i] = $arrayOfUserIds[$i];
		}
        $sql = $sqlPart1 . $sqlPart2 . $sqlPart3;
        $stmt = $this->makeStatement($sql, $params);
        
        $events = array();
        while (!empty($row = $stmt->fetch(PDO::FETCH_ASSOC))) {
            array_push($events, $row);
        }

        return $events;
	}
}
