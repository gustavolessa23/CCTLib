<?php
include('checktoken.php');

if($_SESSION['usertype'] != "student") {
	echo '<div class="login">';
	echo '<form action="'.htmlspecialchars($_SERVER["PHP_SELF"]).'" method="post">';
	echo '<input type="submit" name="login" value="Session could not be verified. Click here to log in"/>';
	echo '</form>';
	echo '</div>';
	if($_POST){
		header('Location: login.php');
		exit();
	}
	die();
}

include('displayusername.php');
?>
