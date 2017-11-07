<?php
include('checktoken.php');

if($_SESSION['usertype'] != "admin") {
	echo '<div class="login">';
	echo '<form action="'.htmlspecialchars($_SERVER["PHP_SELF"]).'" method="post">';
	echo '<input type="submit" name="login_not_admin" value="Session could not be verified. Click here to log in" class="button"/>';
	echo '</form>';
	echo '</div>';
	
	if(isset($_POST['login_not_admin'])){
		header('Location: login.php');
		exit();
	}
	die();
}



include('displayusername.php');
?>
