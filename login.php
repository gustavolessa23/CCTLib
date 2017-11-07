
<?php

include('init.php');
include('displaynotifications.php');

$userType="";
$usernameErr="";
$username="";
$password="";
$passHashed="";
$passErr="";
$passRegEx='/^((?=.*[A-Za-z])(?=.*\d)[\S]{6,10})$/';
$userTypeErr="";


if(isset($_POST['register'])){
  header('Location: register.php');
}

if(isset($_POST['submit'])){
    $username = $_POST['username'];
    $password = $_POST['password'];
    $passRetrieved = "";

    if(empty($username) || strlen($username) < 4){
      $usernameErr = "Username should be at least 4 characters long.";
    }

    if (empty($password) || !preg_match($passRegEx, $password)){
      $passErr = "Password should be from 6 to 10 characters and must contain at least one letter and one digit.";
    }

    if(isset($_POST['usertype'])){
      $userType = $_POST['usertype'];
    } else {
      $userTypeErr = "Please choose the user type";
    }

    if(empty($usernameErr) && empty($userTypeErr) && empty($passErr)){
      try {
        require_once 'sqlconnection.php';
        if (isset($_POST['submit']) && !empty($_POST['usertype'])) {
          if(($_POST['usertype']) == "admin"){
            $stmt = $DBH->prepare("select * from admin where username = :username LIMIT 1");
          } else if(($_POST['usertype']) == "student"){
            $stmt = $DBH->prepare("select * from student where username = :username LIMIT 1");
          }
        }
    		$stmt->bindValue(':username', $username);
    		$stmt->execute();
        include 'errordb.php';
    		$row = $stmt->fetch(PDO::FETCH_ASSOC);
    		$message = '';

    		if (!empty($row)){ //is the array empty?
          $passRetrieved = $row["password"];

          if (password_verify($password,$passRetrieved)){
            $_SESSION["resultsRow"] = $row;
      			$_SESSION["pageOrigin"] = "login";
      			$username = $row['username'];
      			$_SESSION["username"] = $username;
            $_SESSION["token"] = $token;
            $_SESSION["usertype"] = $_POST['usertype'];
            $_SESSION["student_id"] = $row['student_id'];
            if(($_POST['usertype']) == "admin"){
              header('Location:  admin.php');
        			exit();
            } else if(($_POST['usertype']) == "student"){
              header('Location:  students.php');
        			exit();
            }
          }
		    } else {
  		    $message= '<span class="error">Sorry your log in details are not correct</span>';
  		  }
   	  } catch(PDOException $e) {
  	 	  echo 'Error' . $e;
  	}
  }
}
?>

<!DOCTYPE>
<html>
<head>
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="style2.css">
  <style>
 	.error {display: block;color: #FF0000; }
 	</style>
</head>
<body>
<div class="login">
<div class="login-triangle"></div>
<h2 class="login-header">CCT Library Login</h2>
<form class='login-container' action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
  <input type="text" name="username" value="<?php echo $username; ?>" pattern=".{3,}" placeholder="Username" required="required" title="Username containing at least 4 characters"/>
    <span class ="error"> <?php echo $usernameErr; ?></span>
  <input type="password" name="password" placeholder="Password" pattern ="^((?=.*[A-Za-z])(?=.*\d)[\S]{6,10})$" value="<?php echo $password; ?>" required="required" title="Password must contain digits and numbers, between 6 and 10 characters"/>
    <span class ="error"> <?php echo $passErr; ?></span>
User type: <br>
<table width="300">
  <tr><td> <input type="radio" name="usertype" <?php if (!empty($userType) && $userType=="admin") echo "checked";?> value="admin">
  </td><td>     Admin</td>
  <td>  <input type="radio" name="usertype" <?php if (!empty($userType) && $userType=="student") echo "checked";?> value="student">
  </td><td>     Student</td></tr>
</table>
<span class="error"> <?php echo $userTypeErr;?></span>
<input type="submit" name="submit" value="Login"/>

<?php
if(!empty($message)){
  echo $message;
}
?>
</div>
</form>

<div class="login">
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
  <input type="submit" name="register" value="New user? Click here to register"/>
</form>
</div>
</form>
</body>
</html>
