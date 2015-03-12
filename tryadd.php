<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 'On');


include 'storedInfo.php'; //password file, permissions set to exclude everyone from seeing it

$mysqli = new mysqli("oniddb.cws.oregonstate.edu", "waltmanr-db", $password, "waltmanr-db"); 
	//initialize the connection
$rawTitle = $_POST['title'];
$newTitle = $mysqli->real_escape_string($_POST['title']);
$newReview = $mysqli->real_escape_string($_POST['review']);
$newRating = $mysqli->real_escape_string($_POST['rating']);
//escape the input to prevent injection
$searchURL = urlencode($newTitle);
//the call to the Rotten Tomatoes API to search for movies with that title
$call = "http://api.rottentomatoes.com/api/public/v1.0/movies.json?apikey=cew4h9q6xfgp9z6ywbjac79h&q=".$searchURL;
$data[] = json_decode(file_get_contents($call));
$newURL;
if(!empty($data[0]->movies)){ //if any results were returned, continue
	$matchFlag = 0;
	$base = $data[0]->movies;
	foreach($base as &$currentVal){
		if(strcasecmp($currentVal->title, $rawTitle) == 0){ //if the user's input matches a title exactly
			$matchFlag = 1;
			$newURL = $currentVal->links->alternate;
		}
	}
	if($matchFlag == 0){ //if there's no direct match, go with the first result
		$newURL = $data[0]->movies[0]->links->alternate;
	}
} else{ //if no movies were found, the URL is blank and will not appear
	$newURL = "";
}
if(!($stmt = $mysqli->prepare("INSERT INTO userVideos(userID, user, title, description, rating, RottenLink) VALUES (?, ?, ?, ?, ?, ?)"))){
	echo "Failed to add new review.<br>"; //if there's a problem for any reason, display this response text
}
if(!($stmt->bind_param('isssss', $_SESSION['userID'], $_SESSION['userName'], $newTitle, $newReview, $newRating, $newURL))){
	echo "Failed to add new review.<br>";
}
if(!($stmt->execute())){
	echo "Failed to add new review.<br>";
}

?>