<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="style2.css">
  <style>
 	.error {display: block;color: #FF0000; }
 	</style>
</head>
<body>
<?php
include('init.php');
if(isset($_GET['token']) && $_GET['token'] === $token) {
   session_destroy();
   header("location: login.php");
   exit();
}


if($_POST){
  session_destroy();
  header("location: login.php");
  exit();
}
?>
<div class="login">
  <div class="login-triangle"></div>
	<form class='login-container' action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
		<input type="submit" name="login" value="Click to confirm logout" class='button'/>
	</form>
</div>

</body>
<?php include('footer.php'); ?>
</html>
