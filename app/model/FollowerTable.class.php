<?php

/*
 Class for interacting with Follower table
 */
class FollowerTable extends Table {
    // name of database table
    const DB_TABLE = 'Follower';

    /*
     * Unfollow another user
     */
    public function unfollowUser($followerId, $leaderId) {
        $sql = '
            DELETE FROM '.self::DB_TABLE.'
            WHERE FollowerID = :FollowerID
            AND LeaderID = :LeaderID';

        $params = array(
            ':FollowerID' => $followerId,
            ':LeaderID' => $leaderId
        );
        return $this->makeStatement($sql, $params);
    }

    /*
     * Follow another user
     */
    public function followUser($followerId, $leaderId) {
        $sql = '
            INSERT INTO '.self::DB_TABLE.' (FollowerID, LeaderID)
            VALUES (:FollowerID, :LeaderID)';

        $params = array(
            ':FollowerID' => $followerId,
            ':LeaderID' => $leaderId
        );
        return $this->makeStatement($sql, $params);
    }
    
    /*
     * Return whether follower $followerId already follows leader $leaderId
     */
    public function relationshipExists($followerId, $leaderId) {
        $sql = '
            SELECT *
            FROM '.self::DB_TABLE.'
            WHERE FollowerID = :FollowerID
            AND LeaderID = :LeaderID';

        $params = array(
            ':FollowerID' => $followerId,
            ':LeaderID' => $leaderId
        );
        
        $stmt = $this->makeStatement($sql, $params);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!empty($row)) {
            return true;
        }
        return false;
    }
    
    /*
     * Return all the records in the table
     */
    public function getRelationships() {
        $sql = '
            SELECT *
            FROM '.self::DB_TABLE.'
            ORDER BY Timestamp DESC';
        
        $stmt = $this->makeStatement($sql);
        
        $relationships = array();
        while (!empty($row = $stmt->fetch(PDO::FETCH_ASSOC))) {
            array_push($relationships, $row);
        }

        return $relationships;
    }
    
    /*
     * Get people whom $followerId follows
     */
    public function getLeadersByFollowerId($followerId) {
        $sql = '
            SELECT Users.Username, Users.UserID
            FROM '.self::DB_TABLE.'
            JOIN Users
            ON '.self::DB_TABLE.'.LeaderID = Users.UserID
            WHERE '.self::DB_TABLE.'.FollowerID = :FollowerID';
            
        $params = array(
            ':FollowerID' => $followerId
        );
        
        $stmt = $this->makeStatement($sql, $params);
        
        $relationships = array();
        while (!empty($row = $stmt->fetch(PDO::FETCH_ASSOC))) {
            array_push($relationships, $row);
        }

        return $relationships;
    }
	
	/*
     * Get people whom $followerId follows
     */
    public function getFollowersByLeaderId($leaderId) {
        $sql = '
            SELECT Users.Username, Users.UserID
            FROM '.self::DB_TABLE.'
            JOIN Users
            ON '.self::DB_TABLE.'.FollowerID = Users.UserID
            WHERE '.self::DB_TABLE.'.LeaderID = :LeaderID';
            
        $params = array(
            ':LeaderID' => $leaderId
        );
        
        $stmt = $this->makeStatement($sql, $params);
        
        $relationships = array();
        while (!empty($row = $stmt->fetch(PDO::FETCH_ASSOC))) {
            array_push($relationships, $row);
        }

        return $relationships;
    }
}
