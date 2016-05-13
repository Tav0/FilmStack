<?php

/*
 Class for interacting with Users table
 */
class CommentsTable extends Table {
    // name of database table
    const DB_TABLE = 'Comments';

    //Gets the number of comments in the database
    public function numComments($movieID)
    {
        $sql = '
            SELECT * FROM '.self::DB_TABLE.' WHERE `MovieID` = '.$movieID.' ORDER BY Timestamp DESC';

        $commentRow = NULL;
        try {
            $commentRow = $this->makeStatement($sql, null);
        } catch (PDOException $e) {
            echo 'Execute query failed '.$e->getMessage();
        }

        return $commentRow;
    }

    //Enters the comments to the Database
    public function enterComment($username, $comment, $rating, $movieID, $endorse)
    {
        $sql = '
            INSERT INTO '.self::DB_TABLE.' (Username, Comment, Rating, MovieID, Endorse)
            VALUES (:Username, :Comment, :Rating, :MovieID, :Endorse)';

        $params = array(
            ':Username' => $username,
            ':Comment' => $comment,
            ':Rating' => $rating,
            ':MovieID' => $movieID,
            ':Endorse' => $endorse
        );
        return $this->makeStatement($sql, $params);
    }

    //Enters the comments to the Database
    public function deleteComment($username, $comment)
    {
        $sql = '
            DELETE FROM '.self::DB_TABLE.' WHERE Username = \''.$username.'\' AND Comment = \''.$comment.'\'';

        return $this->makeStatement($sql, NULL);
    }
}
