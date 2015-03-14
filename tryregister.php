<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 'On');


include 'storedInfo.php'; //password file, permissions set to exclude everyone from seeing it

$mysqli = new mysqli("oniddb.cws.oregonstate.edu", "waltmanr-db", $password, "waltmanr-db"); 
	//initialize the connection

$userNameInput = $mysqli->real_escape_string($_POST['u']);
$userPasswordInput = $mysqli->real_escape_string($_POST['p']);
//escape the input to avoid injection
$stmt = $mysqli->query("SELECT * FROM userTable WHERE userName='$userNameInput'");

if($stmt->num_rows == 0){ //if a user doesn't already exist with this name, proceed
	$returnText = 1;
	$stmt = $mysqli->query("INSERT INTO userTable (userName,userPassword) VALUES ('$userNameInput','$userPasswordInput')");
	$stmt = $mysqli->query("SELECT * FROM userTable WHERE userName='$userNameInput' AND userPassword='$userPasswordInput'");
	$stmt->data_seek(0);
	$row = $stmt->fetch_assoc();
	$_SESSION['userID'] = $row['userID'];
	$_SESSION['userName'] = $row['userName']; //bind the new data to the current session
	echo $returnText;
}
else{ //otherwise return 0
	
	$returnText = 0;
	echo $returnText;
}
?>


