<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 'On');


include 'storedInfo.php'; //password file, permissions set to exclude everyone from seeing it

$mysqli = new mysqli("oniddb.cws.oregonstate.edu", "waltmanr-db", $password, "waltmanr-db"); 
	//initialize the connection

$userNameInput = $mysqli->real_escape_string($_GET['u']);
$userPasswordInput = $mysqli->real_escape_string($_GET['p']);
//escape the input to avoid injection
$stmt = $mysqli->query("SELECT * FROM userTable WHERE userName='$userNameInput' AND userPassword='$userPasswordInput'");

if($stmt->num_rows == 0){
	$returnText = 0;
	echo $returnText; //return 0 if there were no results found for the specified info
}
else{
	$stmt->data_seek(0);
	$row = $stmt->fetch_assoc();
	$returnText = 1;
	$_SESSION['userID'] = $row['userID'];
	$_SESSION['userName'] = $row['userName']; //otherwise bind the returned data to the current session
	echo $returnText;
}
?>


