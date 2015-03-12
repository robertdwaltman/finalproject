<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 'On');
include 'storedInfo.php'; //password file, permissions set to exclude everyone from seeing it
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8"/>
<title>Login</title>
<style>
.title{
	margin-left: auto;
	margin-right: auto;
	text-align: center;
	width: 70%;
	font-family: Arial, Helvetica, sans-serif;
}
#loginbox{
	background-color: white;
	border-style: solid;
	width: 30%;
	margin-left:auto;
	margin-right:auto;
	padding-top: 5px;
	padding-right: 15px;
	padding-bottom: 5px;
	padding-left: 15px;
}
#loginResponse{
	color: red;
	margin-right: auto;
	margin-left: auto;
	width: 20%;
}
#registerResponse{
	color: red;
	margin-right: auto;
	margin-left: auto;
	width: 20%;
}
#loginbutton{
	width: 10%;
	margin-right: auto;
	margin-left: auto;
}
form {width: 400px;}
label {float: left; width: 150px;}
input[type=text] {float: left; width: 200px; margin-bottom: 5px;}
input[type=password] {float: left; width: 200px; margin-bottom: 5px;}
.clear{clear: both; height: 0; line-height: 0;}
/*formatting rules for text input*/
body{
	background-color: linen;
}
</style>
<script>
function tryLogin(userInput, passInput){
	document.getElementById("loginResponse").innerHTML="";
	if(userInput == ""){
		document.getElementById("loginResponse").innerHTML+="Error: You must input a username.<br>";
	}
	else if(passInput ==""){
		document.getElementById("loginResponse").innerHTML+="Error: You must input a password.<br>";
	}
	else if(passInput != "" && userInput != ""){
		xmlhttp = new XMLHttpRequest();
		xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            	/*responseText is 0 if the user login was wrong*/
                if(xmlhttp.responseText == 0){
                	document.getElementById("loginResponse").innerHTML+="Error: Incorrect username or password.<br>";
                } else if(xmlhttp.responseText == 1) {
                	/*otherwise carry through to display page*/
					window.location.href ="http://web.engr.oregonstate.edu/~waltmanr/databaseDisplay.php";
                }
            }
        }
        xmlhttp.open("GET", "trylogin.php?u="+userInput+"&p="+passInput, true);
        xmlhttp.send();
	}
}
function tryRegister(userInput, passInput, passInput2){
	document.getElementById("registerResponse").innerHTML="";
	if(userInput == ""){
		document.getElementById("registerResponse").innerHTML+="Error: You must input a username.<br>";
	}
	else if(passInput ==""){
		document.getElementById("registerResponse").innerHTML+="Error: You must input a password.<br>";
	}
	else if(passInput != passInput2){
		document.getElementById("registerResponse").innerHTML+="Error: Passwords do not match.<br>";
	}
	else if(passInput != "" && userInput != ""){
		xmlhttp = new XMLHttpRequest();
		xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                if(xmlhttp.responseText == 0){
                	/*responseText is 0 if a dupe is found*/
                	document.getElementById("registerResponse").innerHTML+="Error: User already exists.<br>";
                } else if(xmlhttp.responseText == 1) {
					window.location.href ="http://web.engr.oregonstate.edu/~waltmanr/databaseDisplay.php";
                }
            }
        }
        xmlhttp.open("GET", "tryregister.php?u="+userInput+"&p="+passInput, true);
        xmlhttp.send();
    }
}
</script>
</head>
<body>
	<?php
	//this fires if the user is sent back here from the child pages, otherwise it's ignored
	if(isset($_GET['logout']) && $_GET['logout']==true){
		$_SESSION = array();
		session_destroy(); 
		echo "You have been logged out. <br>";
	}
	?>
	<div class ="title">
		<p><h1>Movie Reviews!</h1></p>
	</div>

	<div id="loginbox">
		<div class="title"><p><h2>Login</h2></p></div>
		<form>
			<label for="username">Username:</label>
			<input type='text', name="userInput" maxlength="30">
			<br>
			<label for="password">Password:</label>
			<input type='password' name="passInput" maxlength="12">
			<br class="clear"/>
			<div id="loginbutton"><button type="button" onclick="tryLogin(userInput.value, passInput.value)">Login</button></div>
		</form>
	</div>

	<div id="loginResponse">
	</div>
	<p></p>

	<div id="loginbox">
		<p>
			<div class="title"><p><h2>Register New Account</h2></p></div>
			<form>
				<label for="newuser">Username:</label>
				<input type="text", name="newUserInput", maxlength="30">
				<br>
				<label for="newpass">Password:</label>
				<input type="password", name="newPassInput", maxlength="12">
				<label for="newpass2">Confirm Password:</label>
				<input type="password", name="newPassInput2", maxlength="12">
				<br class="clear"/>
				<div id="loginbutton"><button type="button" onclick="tryRegister(newUserInput.value, newPassInput.value, newPassInput2.value)">Register</button></div>
			</form>
		</p>
	</div>

	<div id="registerResponse">
	</div>

</body>
</html>