<?php

$connection = new mysqli("127.0.0.1", "root", "");

$result = $connection->query("DROP DATABASE IF EXISTS gui_proj");

$result0 = $connection->query("CREATE DATABASE gui_proj");

$connection->select_db("gui_proj");

$result1 = $connection->query("CREATE TABLE IF NOT EXISTS Users(
	UserID INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	Username VARCHAR(50) NOT NULL,
	Password VARCHAR(30) NOT NULL,
	WatchedID INT UNSIGNED,
	ToWatchID INT UNSIGNED,
	Email VARCHAR(50) NOT NULL,
	FirstName VARCHAR(50) NOT NULL,
	LastName VARCHAR(50) NOT NULL,
	Type VARCHAR (20) NOT NULL
)");
if($result1 == TRUE)
	echo "Users created\n";
else
	echo "ERROR: Users not created, " . $connection->error . "\n";
	
$result2 = $connection->query("CREATE TABLE IF NOT EXISTS Lists (
	ListID INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	UserID INT UNSIGNED,
	TableName VARCHAR(30) NOT NULL
)");
if($result2 == TRUE)
	echo "Lists created\n";
else
	echo "ERROR: Lists not created, " . $connection->error . "\n";
	
$result3 = $connection->query("CREATE TABLE IF NOT EXISTS ToWatch (
	ListID INT UNSIGNED,
	MovieID INT UNSIGNED,
	Timestamp TIMESTAMP
)");
if($result3 == TRUE)
	echo "ToWatch created\n";
else
	echo "ERROR: ToWatch not created, " . $connection->error . "\n";
	
$result4 = $connection->query("CREATE TABLE IF NOT EXISTS Watched (
	ListID INT UNSIGNED,
	MovieID INT UNSIGNED,
	Timestamp TIMESTAMP
)");
if($result4 == TRUE)
	echo "Watched created\n";
else
	echo "ERROR: Watched not created, " . $connection->error . "\n";
	
$result5 = $connection->query("CREATE TABLE IF NOT EXISTS Follower (
	LeaderID INT UNSIGNED,
	FollowerID INT UNSIGNED,
	Timestamp TIMESTAMP
)");
if($result5 == TRUE)
	echo "Follower created\n";
else
	echo "ERROR: Follower not created, " . $connection->error . "\n";

$result6 = $connection->query("CREATE TABLE IF NOT EXISTS Reviews (
	UserID INT UNSIGNED,
	MovieID INT UNSIGNED,
	Review TEXT,
	Rating TINYINT UNSIGNED,
	Endorsed TINYINT UNSIGNED,
	Timestamp TIMESTAMP
)");
if($result6 == TRUE)
	echo "Reviews created\n";
else
	echo "ERROR: Reviews not created, " . $connection->error . "\n";
	
$connection->close();

?>
