<?php
class ListsTable extends Table {
    // name of database table
    const DB_TABLE = 'Lists';

	//Creates a new row in the Lists table
    public function createUserLists($userID, $tableName) {
        $sql = '
            INSERT INTO '.self::DB_TABLE.' (UserID, TableName)
            VALUES (:UserID, :TableName)';

        $params = array(
            ':UserID' => $userID,
            ':TableName' => $tableName
        );
        return $this->makeStatement($sql, $params);
    }

	//Gets the specified list row with the given tableName and userID
    public function getListByUserID($userID, $tableName) {
        $sql = '
            SELECT *
            FROM '.self::DB_TABLE.'
            WHERE UserID = :UserID AND TableName = :TableName';

        $params = array(
            ':UserID' => $userID,
			':TableName' => $tableName
        );

        $list = null;

        try {
            $listRow = $this->makeStatement($sql, $params);
            $list = $listRow->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo 'Execute query failed '.$e->getMessage();
        }

        return $list;
    }
	
	//Gets the specified user row with the given tableName and listID
    public function getUserByListID($listID, $tableName) {
        $sql = '
            SELECT *
            FROM '.self::DB_TABLE.'
            WHERE ListID = :ListID AND TableName = :TableName';

        $params = array(
            ':ListID' => $listID,
			':TableName' => $tableName
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
}

?>
