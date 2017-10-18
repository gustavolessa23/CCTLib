
<?php
//
// This is a very small sample register page. The user will fill out their information
// on the form. When they click the submit button, the data will be inserted into the database.
session_start();
$token = md5(session_id());
$userType="";
$usernameErr="";
$username="";
$password="";
$userTypeErr="";

if(isset($_SESSION["pageOrigin"])){
  $username = $_SESSION["username"];
}

if(isset($_SESSION["pageOrigin"]) && $_SESSION["pageOrigin"] == "register" && (!empty($_SESSION["username"]))){
    echo "<br/>Registered as: ".$_SESSION["username"];
}



if($_POST){
    $username = $_POST['username'];
    $password = $_POST['password'];
    if(isset($_POST['usertype'])){
      $userType = $_POST['usertype'];
    } else {
      $userTypeErr = "Please choose the user type";
    }


    if(empty($usernameErr) && empty($userTypeErr) ){
        try {
        $host = '127.0.0.1';
        $dbname = 'cctlib';
        $user = 'root';
        $pass = '';
        # MySQL with PDO_MYSQL
        $DBH = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);

        if (isset($_POST['submit']) && !empty($_POST['usertype'])) {
          if(($_POST['usertype']) == "admin"){
            $q = $DBH->prepare("select * from admin where username = :username and password = :password LIMIT 1");
          } else if(($_POST['usertype']) == "student"){
            $q = $DBH->prepare("select * from students where username = :username and password = :password LIMIT 1");
          }
        }

    		$q->bindValue(':username', $username);
    		$q->bindValue(':password', $password);
    		$q->execute();
    		$row = $q->fetch(PDO::FETCH_ASSOC);

    		//returns table row(s) as an associative array
    		//of values column names to data values
    		//Array ( [id] => 1 [username] => seaanc
    		//        [email] => 12345 [password] => 12345 [date] => 2017-10-05 14:06:07 )
    		$message = '';
    		if (!empty($row)){ //is the array empty?
    			$_SESSION["resultsRow"] = $row;
    			$_SESSION["pageOrigin"] = "login";
    			$username = $row['username'];
    			$_SESSION["username"] = $username;
          $_SESSION["token"] = $token;
          $message = 'Logged in as: '.$username;
          if(($_POST['usertype']) == "admin"){
            header('Location:  admin.php');
      			exit();
          }
          if(($_POST['usertype']) == "student"){
            header('Location:  students.php');
      			exit();
          }


    		} else {
    		    $message= 'Sorry your log in details are not correct';
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
  <style>
 	.error {display: block;color: #FF0000; }
 	</style>
</head>
<body>
<h2>CCT Library Login</h2><br></br>
<form class='form-style' action="login.php" method="post">
Username: <input type="text" name="username" value="<?php echo $username; ?>"/>
Password: <input type="password" name="password" value="<?php echo $password; ?>"/>
User type: <br>
<input type="radio" name="usertype" <?php if (!empty($userType) && $userType=="admin") echo "checked";?> value="admin">Admin
<input type="radio" name="usertype" <?php if (!empty($userType) && $userType=="student") echo "checked";?> value="student">Student
  <span class="error">* <?php echo $userTypeErr;?></span>
<input type="submit" name="submit" value="Login" class='button'/>
<?php



if(!empty($message)){
  echo '<br>';
  echo $message;
}
?>
</form>
</body>
</html>
