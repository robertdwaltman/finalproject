<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 'On');


include 'storedInfo.php'; //password file, permissions set to exclude everyone from seeing it

$mysqli = new mysqli("oniddb.cws.oregonstate.edu", "waltmanr-db", $password, "waltmanr-db"); 
	//initialize the connection

$newTitle = $mysqli->real_escape_string($_POST['title']);
$newReview = $mysqli->real_escape_string($_POST['review']);
$newRating = $mysqli->real_escape_string($_POST['rating']);
if(!($stmt = $mysqli->prepare("INSERT INTO userVideos(userID, user, title, description, rating) VALUES (?, ?, ?, ?, ?)"))){
	echo "Failed to add new review.<br>";
}
if(!($stmt->bind_param('issss', $_SESSION['userID'], $_SESSION['userName'], $newTitle, $newReview, $newRating))){
	echo "Failed to add new review.<br>";
}
if(!($stmt->execute())){
	echo "Failed to add new review.<br>";
}

?>