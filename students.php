<?php
session_start();
//$token = md5(session_id());

?>
<!DOCTYPE html>
<html>
<body>

<?php
	// get session variables

	if($_SESSION["pageOrigin"] == "login"){
		if (isset($_SESSION["username"]))  {
			echo "<br/>Logged in as: ".$_SESSION["username"];
		}
	}

	$row = $_SESSION["resultsRow"];
		if (!empty($row)){ //is the array empty
			$username = $row['username'];
		} else {
		    $message= 'Sorry your log in details are not correct';
		}
		echo '<br><a href="logout.php?token='.md5(session_id()).'">Logout</a>';
?>

</body>
</html>
