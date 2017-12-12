<?php
include('init.php');

//Declare and initialize variables
$usernameErr="";
$username="";
$studentid="";
$studentidErr="";
$password="";
$passErr="";
$passRegEx='/^((?=.*[A-Za-z])(?=.*\d)[\S]{6,10})$/';
$passHashed="";
$sidexists="";
$captchaerror="";
$userexists="";


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

if(isset($_POST['login'])){
  header('Location: login.php');
}

if(isset($_POST['submit'])){
	$studentid = $_POST['studentid'];
  $username = $_POST['username'];
  $password = $_POST['password'];

	if(empty($username) || strlen($username) < 4){
		$usernameErr = "Username should be at least 4 characters long.";
	}
	if (empty($studentid) || !is_numeric($studentid) || strlen($studentid) != 7){
		$studentidErr = "Student ID should be exactly 7 characters long, only digits.";
	}
  if (empty($password) || !preg_match($passRegEx, $password)){
    $passErr = "Password should be from 6 to 10 characters and must contain at least one letter and one digit.";
  }
    if(empty($usernameErr) && empty($studentidErr) && empty($passErr) ){

      $passHashed = password_hash($password, PASSWORD_DEFAULT);

      if($res){
        try {
          require 'sqlconnection.php';

          if (checkUsername($username) == false) { //no user with this name exists

            if (checkStudentID($studentid) == false) { //no user with this ID exists

              registerUser($studentid, $username, $passHashed);

            } else {
              $sidexists = "Student ID already exists in the database!";
              //echo '<span class ="error">Student ID already exists in database!</span>';
            }
          } else {
            $userexists = "Username already exists in database!";
            //echo '<span class ="error">Username already exists in database!</span>';
          }
        } catch(PDOException $e) {
          echo 'Error' . $e;
        }
      } else {
        $captchaerror = "Please check the security CAPTCHA box.";
        //echo '<span class ="error">Please go back and make sure you check the security CAPTCHA box.</span>';
      }
    }
}

function checkUsername($username){
  require 'sqlconnection.php';
  $stmt = $DBH->prepare("SELECT * FROM student WHERE username = ?" );
  $stmt->bindParam(1, $username);
  $stmt->execute();
  if ($stmt->rowCount() == 0){
    return false;
  } else {
    return true;
  }
}

function checkStudentID($studentid){
  require 'sqlconnection.php';
  $stmt = $DBH->prepare("SELECT * FROM student WHERE student_id = ?" );
  $stmt->bindParam(1, $studentid);
  $stmt->execute();
  if ($stmt->rowCount() == 0){
    return false;
  } else {
    return true;
  }
}

function registerUser($studentid, $username, $passHashed){
  require 'sqlconnection.php';
  $sql = "INSERT INTO student (student_id, username, password) VALUES (?, ?, ?);";
  $stmt = $DBH->prepare($sql);

  $stmt->bindParam(1, $studentid, PDO::PARAM_STR);
  $stmt->bindParam(2, $username, PDO::PARAM_STR);
  $stmt->bindParam(3, $passHashed, PDO::PARAM_STR);
  $stmt->execute();

  $_SESSION["studentid"] = $studentid;
  $_SESSION["username"] = $username;
  $_SESSION["pageOrigin"] = "register";
  $_SESSION['registration_success'] = "You are now registered as ".$_SESSION["username"];
  header('Location:  login.php');
  exit();
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
    <script src='https://www.google.com/recaptcha/api.js'></script>
</head>
<body>
  <div class="login">
    <div class="login-triangle"></div>
  <h2 class="login-header">CCT Library Registration</h2>
  <form class='login-container' action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">

<input type="text" name="studentid" pattern="^\d{7}$" value="<?php echo $studentid; ?>" required="required"  placeholder="Student ID" title="Student ID e.g 2016999"/>
  	<span class ="error"> <?php echo $studentidErr; ?></span>
    <span class ="error"> <?php echo $sidexists; ?></span>
<input type="text" name="username" value="<?php echo $username; ?>" required="required" placeholder="Choose an username" title="Username containing at least 4 characters"/>
	<span class ="error"> <?php echo $usernameErr; ?></span>
  <span class ="error"> <?php echo $userexists; ?></span>

<input type="password" name="password" pattern ="^((?=.*[A-Za-z])(?=.*\d)[\S]{6,10})$" value="<?php echo $password; ?>" placeholder="Choose a password" required="required" title="Password must contain digits and numbers, between 6 and 10 characters"/>
<span class ="error"> <?php echo $passErr; ?></span>
<input type="submit" name='submit' value= 'Register'/>
<span class ="error"> <?php echo $captchaerror; ?></span>
<div class="g-recaptcha" data-sitekey="6LdGRDUUAAAAAOq3KtfgPSLkaqOA8WJtX2RbGk_C"></div>
</div>
</form>

<div class="login">
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
<input type="submit" name="login" value="Already registered? Click here to login"/>
</form>
</div>

</form>
<?php include('footer.php'); ?>
</body>
</html>
