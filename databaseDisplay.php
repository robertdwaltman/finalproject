<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 'On');
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8"/>
<title>Your Movie Reviews</title>
<style>
body{
	background-color: linen;
	font-family: Arial, Helvetica, sans-serif;
}
#userReviews{
	background-color: white;
	border-style: solid;
	width: 70%;
	margin-left: auto;
	margin-right: auto;
	margin-bottom: 10px;
	padding-top: 5px;
	padding-right: 15px;
	padding-bottom: 5px;
	padding-left: 15px;
}
#newReview{
	background-color: white;
	border-style: solid;
	width: 70%;
	margin-left: auto;
	margin-right: auto;
	margin-bottom: 10px;
	padding-top: 5px;
	padding-right: 15px;
	padding-bottom: 5px;
	padding-left: 15px;
}
#newReviewResponse{
	color: red;
	margin-right: auto;
	margin-left: auto;
	width: 20%;
}

form {width: 400px;}
label {float: left; width: 150px;}
input[type=text] {float: left; width: 400px; margin-bottom: 5px;}
textarea{float: left; width: 400px; margin-bottom: 5px;}
.clear{clear: both; height: 0; line-height: 0;}
</style>
<script>
function tryAddNew(newTitle, newReview, newRating){
	document.getElementById("newReviewResponse").innerHTML="";
	if(newTitle == ""){
		document.getElementById("newReviewResponse").innerHTML+= "Error: You must enter a title.<br>";
	} else {
		xmlhttp = new XMLHttpRequest();
		xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                if(xmlhttp.responseText != ""){
                	/*if the add was unsuccessful for some reason, output error*/
                	document.getElementById("newReviewResponse").innerHTML+=xmlhttp.responseText;
                } else {
                	/*otherwise reload the page*/
					window.location.href ="http://web.engr.oregonstate.edu/~waltmanr/databaseDisplay.php";
                }
            }
        }
        var statement = "title="+newTitle+"&review="+newReview+"&rating="+newRating;
        xmlhttp.open("POST", "tryadd.php", true);
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xmlhttp.send(statement);
	}
}
</script>
</head>
<body>

<?php
if(!isset($_SESSION['userID'])){
	/*this fires if the user got here without logging in*/
	echo "<p>You are not logged in. Please log in <a href='loginpage.php'>HERE</a></p>";
	exit;
} else{
	echo "<p>";
	echo htmlspecialchars("Hello, $_SESSION[userName]!");
	echo "</p>";
	echo "<p>Click <a href='loginpage.php?logout=true'>HERE</a> to logout and return to the main page.</p>";

	include 'storedInfo.php'; //password file, permissions set to exclude everyone from seeing it

	$mysqli = new mysqli("oniddb.cws.oregonstate.edu", "waltmanr-db", $password, "waltmanr-db"); 
		//initialize the connection
	if(isset($_POST['Remove'])){
		$stmt = $mysqli->prepare("DELETE FROM userVideos WHERE reviewID = (?)");
		$stmt->bind_param('i', $_POST['Remove']);
		$stmt->execute();
		//remove the specified entry if we got here via a remove button
	}
	$cleanUserID = $mysqli->real_escape_string($_SESSION['userID']);
	$stmt = $mysqli->query("SELECT * FROM userVideos WHERE userID='$cleanUserID'");
	if($stmt->num_rows == 0){
		echo "<div id='userReviews'>";
		echo "<p>You have no saved reviews. Add a new review below!</p>";
		echo "</div>";
	}
	else{
		
		$stmt->data_seek(0);
		while ($row = $stmt->fetch_assoc()){ //display each returned result
			echo "<div id='userReviews'>";
			echo "<table cellpadding='2'>";
			echo "<tr><td>Movie Title:</td><td>".$row['title']."</td></tr>";
			echo "<tr><td>Your Review:</td><td>".$row['description']."</td></tr>";
			echo "<tr><td>Your Rating:</td><td>";
			$numstars=$row['rating'];
			while($numstars > 0){ //output a star for every star the user gave it
				echo "<img src='star.png'>";
				$numstars--;
			}
			echo "</td></tr>";
			if($row['RottenLink'] != ""){ //if a link to a review exists, list it here, otherwise skip it
				echo "<tr><td>Critic Reviews:</td><td><a href=".$row['RottenLink'].">Rotten Tomatoes</a></td></tr>";
			}
			echo "<tr><td>
				<form method='post' style='margin: 0; text-align: left;' action='databaseDisplay.php'>
				<button type='submit' name='Remove' value='" . $row['reviewID'] . "'>Remove</button></form></td></tr>";
			echo "</table></div>";
		}
	}
}
?>
	<div id="newReview">
		<form >
				<label for="title">Movie Title:</label>
				<input type='text', name="newTitle">
				<br>
				<textarea rows="4" cols="50" name="newReview" placeholder="Enter your review here..."></textarea>
				<br>
				<label for="rating">Your Rating:</label>
				<select name="newRating">
					<option value="1">1 Star</option>
					<option value="2">2 Stars</option>
					<option value="3">3 Stars</option>
					<option value="4">4 Stars</option>
					<option value="5">5 Stars</option>
				</select>
				<br class="clear"/>
				<div id="submitbutton"><button type="button" name="submitbutton" onclick="tryAddNew(newTitle.value, newReview.value, newRating.value)">Submit</button></div>
		</form>
	</div>
	<div id="newReviewResponse">
	</div>



</body>
</html>