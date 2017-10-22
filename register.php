<?php
//
// This is a very small sample register page. The user will fill out their information
// on the form. When they click the submit button, the data will be inserted into the database.
session_start();

//Declare and initialize variables
$usernameErr="";
$username="";
$studentid="";
$studentidErr="";
$password="";
$passErr="";


//Function to verify if the reCAPTCHA was answered correctly
function verify($response){
  $ip = $_SERVER['REMOTE_ADDR'];
  $key = "6LdGRDUUAAAAAPSc1Yq8a2RgnyDnPMrKbJtV0KBX";
  $url = 'https://www.google.com/recaptcha/api/siteverify';
  $full_url = $url.'?secret='.$key.'&response='.$response.'&remoteip='.$ip;

  $data = json_decode(file_get_contents($full_url));
  if(isset($data->success) && $data->success == true){
     return true;
  }
  return false;
}

// Check if reCAPTCHA was answered and call verify function.
if(isset($_POST['g-recaptcha-response'])){
  $res = verify($_POST['g-recaptcha-response']);
}


if($_POST){
	$studentid = $_POST['studentid'];
  $username = $_POST['username'];
  $password = $_POST['password'];

	if(empty($username) || strlen($username) < 4){
		$usernameErr = "Username should be at least 4 characters long.";
	}
	if (empty($studentid) || !is_numeric($studentid) || strlen($studentid) != 7){
		$studentidErr = "Student ID should be exactly 7 characters long, only digits.";
	}
    if(empty($usernameErr) && empty($studentidErr) ){

      if($res){
        try {
          $host = 'sql2.freemysqlhosting.net';
          $dbname = 'sql2200059';
          $user = 'sql2200059';
          $pass = 'hA7*cK2*';
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
      } else {
        echo '<p>Please go back and make sure you check the security CAPTCHA box.</p><br>';
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
    <script src='https://www.google.com/recaptcha/api.js'></script>
</head>
<body>
<h2> Registration Form</h2>
<form class='form-style' action="register.php" method="post">
Student ID <input type="number" name="studentid" pattern="^\d{7}$" value="<?php echo $studentid; ?>" required="required" title="Student ID e.g 2016999"/>
  	<span class ="error"> <?php echo $studentidErr; ?></span>
Username <input type="text" name="username" value="<?php echo $username; ?>" required="required" title="Username containing at least 4 characters"/>
	<span class ="error"> <?php echo $usernameErr; ?></span>

Password <input type="password" name="password" pattern ="^((?=.*[A-Za-z])(?=.*\d)[\S]{6,10})$" value="<?php echo $password; ?>" required="required" title="Password must contain digits and numbers, between 6 and 10 characters"/>
<span class ="error"> <?php echo $passErr; ?></span>
<input type="submit" class='button' name='submit' value= 'Register'/>
<div class="g-recaptcha" data-sitekey="6LdGRDUUAAAAAOq3KtfgPSLkaqOA8WJtX2RbGk_C"></div>

</form>
</body>
</html>
