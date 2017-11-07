<?php
include('checktoken.php');

if(!isset($_SESSION['usertype']) || ($_SESSION['usertype'] != "admin" && $_SESSION['usertype'] != "student")) {
	echo '<div class="login">';
	echo '<form action="'.htmlspecialchars($_SERVER["PHP_SELF"]).'" method="post">';
	echo '<input type="submit" name="session_login" value="Session could not be verified. Click here to log in" class="button"/>';
	echo '</form>';
	echo '</div>';
	if(isset($_POST['session_login'])){
		header('Location: login.php');
	}
	die();
}

include('displayusername.php');
?>
