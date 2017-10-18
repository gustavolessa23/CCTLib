<?php
//
// This is a very small sample register page. The user will fill out their information
// on the form. When they click the submit button, the data will be inserted into the database.
session_start();
$usernameErr="";
$username="";
$studentid="";
$studentidErr="";

if($_POST){
  	$studentid = $_POST['studentid'];
    $username = $_POST['username'];
    $password = $_POST['password'];


	if(empty($username) || strlen($username) < 4){
		$usernameErr = "Username should be at least 3 characters long";
	}
	if (!is_numeric($studentid) || strlen($studentid) != 7){
		$studentidErr = "Student ID should be 7 characters long";
	}
    if(empty($usernameErr) && empty($studentidErr) ){
	    try {
	        $host = '127.0.0.1';
	        $dbname = 'cctlib';
	        $user = 'root';
	        $pass = '';
	        # MySQL with PDO_MYSQL
	        $DBH = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);

			$sql = "INSERT INTO students (student_id, username, password) VALUES (?, ?, ?);";
		    $sth = $DBH->prepare($sql);

			$sth->bindParam(1, $studentid, PDO::PARAM_STR);
			$sth->bindParam(2, $username, PDO::PARAM_STR);
			$sth->bindParam(3, $password, PDO::PARAM_STR);

			$sth->execute();
      $_SESSION["studentid"] = $studentid;
			$_SESSION["username"] = $username;

			$_SESSION["pageOrigin"] = "register";
			header('Location:  login.php');
			exit();

		    echo 'You are now registered!';

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
    .error2 {
    	display: block;
    	color: #FF0000;
    }
</head>
<body>
<h2> Registration Form</h2>
<form class='form-style' action="register.php" method="post">
Student ID <input type="text" name="studentid" value="<?php echo $studentid; ?>"/>
  	<span class ="error"> <?php echo $studentidErr; ?></span>
Username <input type="text" name="username" value="<?php echo $username; ?>"/>
	<span class ="error"> <?php echo $usernameErr; ?></span>

Password <input type="password" name="password"/>
<input type="submit" class='button' name='submit' value= 'Register'/>
</form>
</body>
</html>
