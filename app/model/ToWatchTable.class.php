<?php
class ToWatchTable extends Table {
    // name of database table
    const DB_TABLE = 'ToWatch';

	//Creates a row in the ToWatch table
    public function createMovieToWatch($listID, $movieID) {
        $sql = '
            INSERT INTO '.self::DB_TABLE.' (ListID, MovieID)
            VALUES (:ListID, :MovieID)';

        $params = array(
            ':ListID' => $listID,
            ':MovieID' => $movieID
        );
		
		try {
			$result = $this->makeStatement($sql, $params);
		} catch (PDOException $e) {
            echo 'Execute query failed '.$e->getMessage();
        }
		
    }

	//Deletes a row in the ToWatch table
	public function deleteMovieToWatch($listID, $movieID) {
        $sql = '
            DELETE FROM '.self::DB_TABLE.' WHERE ListID = :ListID AND MovieID = :MovieID';

        $params = array(
            ':ListID' => $listID,
            ':MovieID' => $movieID
        );
        try {
			$result = $this->makeStatement($sql, $params);
		} catch (PDOException $e) {
            echo 'Execute query failed '.$e->getMessage();
        }
    }
	
	//Gets the movies in the ToWatch table with a certain list id
    public function getMoviesByListID($listID) {
        $sql = '
            SELECT *
            FROM '.self::DB_TABLE.'
            WHERE ListID = :ListID';

        $params = array(
            ':ListID' => $listID
        );

        $list = null;

        try {
            $results = $this->makeStatement($sql, $params);
        } catch (PDOException $e) {
            echo 'Execute query failed '.$e->getMessage();
        }

        return $results;
    }
	
	//Returns true if a movie with a certain movie id and list id was found in the
	//ToWatch table
	public function movieInListExist($listID, $movieID){
		$sql = '
            SELECT *
            FROM '.self::DB_TABLE.'
            WHERE ListID = :ListID AND MovieID = :MovieID';

        $params = array(
            ':ListID' => $listID,
			':MovieID' => $movieID
        );
		
		try {
            $results = $this->makeStatement($sql, $params);
			if($results->fetchColumn() > 0)
				return true;
        } catch (PDOException $e) {
            echo 'Execute query failed '.$e->getMessage();
        }

        return false;
	}
	
	//Returns the number of movies in the Watched list for a certain list id
	public function getCountForList($listID) {
		$sql = 'SELECT COUNT(*) AS count FROM ' . self::DB_TABLE . ' WHERE ListID = :ListID';
		
		$params = array(':ListID' => $listID);
		
		try {
            $results = $this->makeStatement($sql, $params);
			$data = $results->fetch(PDO::FETCH_ASSOC);
			return $data['count'];
        } catch (PDOException $e) {
            echo 'Execute query failed '.$e->getMessage();
        }
		
		return 0;
	}
	
	//Returns the results of which lists have the given movie id in their to watch list
	public function getListIDsWithMovie($movieID) {
		$sql = 'SELECT * FROM ' . self::DB_TABLE . ' WHERE MovieID = :MovieID';
		
		$params = array(':MovieID' => $movieID);
		
		try {
            $results = $this->makeStatement($sql, $params);
        } catch (PDOException $e) {
            echo 'Execute query failed '.$e->getMessage();
        }
		
		return $results;
	}
	
	//Returns all movies in order of movie id
	public function getAllMoviesOrdered($ascending) {
		if($ascending == true)
			$sql = 'SELECT * FROM ' . self::DB_TABLE . ' ORDER BY MovieID ASC';
		else
			$sql = 'SELECT * FROM ' . self::DB_TABLE . ' ORDER BY MovieID DESC';
		
		try {
			$db = Db::instance();
			$listsTable = new ListsTable($db);
			
            $results = $this->makeStatement($sql);
			$returnData = array();
			while($data = $results->fetch(PDO::FETCH_ASSOC))
			{
				if(array_key_exists($data['MovieID'], $returnData))
				{
					$movieIndex = $returnData[$data['MovieID']];
					$user = $listsTable->getUserByListID($data['ListID'], "ToWatch");
					array_push($movieIndex['Users'], $user['UserID']);
					$returnData[$data['MovieID']] = $movieIndex;
				}
				else
				{
					$movieIndex['Users'] = array();
					$user = $listsTable->getUserByListID($data['ListID'], "ToWatch");
					array_push($movieIndex['Users'], $user['UserID']);
					
					$returnData[$data['MovieID']] = $movieIndex;
				}
			}
        } catch (PDOException $e) {
            echo 'Execute query failed '.$e->getMessage();
        }
		
		return $returnData;
	}
}

?>
