<?php
include('checktoken.php');

if (isset($_SESSION["username"]))  {
	echo '<div class="login">';
	echo '<form action="'.htmlspecialchars($_SERVER["PHP_SELF"]).'" method="post">';
	echo '<input type="submit" name="logout_username" value="Logged in as '.$_SESSION["username"].'. Click here to logout"/>';
	echo '</form>';
	echo '</div>';
}

if(isset($_POST['logout_username'])){
	unset($_POST['logout_username']);
	header('Location: logout.php?token='.md5(session_id()));
	die();
}

?>
