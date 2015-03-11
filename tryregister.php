<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 'On');


include 'storedInfo.php'; //password file, permissions set to exclude everyone from seeing it

$mysqli = new mysqli("oniddb.cws.oregonstate.edu", "waltmanr-db", $password, "waltmanr-db"); 
	//initialize the connection

$userNameInput = $mysqli->real_escape_string($_GET['u']);
$userPasswordInput = $mysqli->real_escape_string($_GET['p']);
$stmt = $mysqli->query("SELECT * FROM userTable WHERE userName='$userNameInput'");

if($stmt->num_rows == 0){
	$returnText = 1;
	$stmt = $mysqli->query("INSERT INTO userTable (userName,userPassword) VALUES ('$userNameInput','$userPasswordInput')");
	$stmt = $mysqli->query("SELECT * FROM userTable WHERE userName='$userNameInput' AND userPassword='$userPasswordInput'");
	$stmt->data_seek(0);
	$row = $stmt->fetch_assoc();
	$_SESSION['userID'] = $row['userID'];
	$_SESSION['userName'] = $row['userName'];
	echo $returnText;
}
else{
	
	$returnText = 0;
	echo $returnText;
}
?>


