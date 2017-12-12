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

if(isset($_POST['login'])){
	header("location: login.php");
}
if(isset($_POST['register'])){
	header("location: register.php");
}
?>

<div class="login">
  <div class="login-triangle"></div>
	<h2 class="login-header">Welcome to CCT Library System</h2>
	<form class='login-container' action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
		<input type="submit" name="login" value="Login" class='button'/>
		<input type="submit" name="register" value="Register" class='button'/>
	</form>
</div>

</body>
<?php include('footer.php'); ?>
</html>
