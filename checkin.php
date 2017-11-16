<?php

include('init.php');
include('checkanyuser.php');
include('displaynotifications.php');

if(isset($_GET['id'])){
	$_SESSION['book_id'] = $_GET['id'];
}
$book_id = $_SESSION['book_id'];
$title = "";
$author = "";
$isbn = "";

try{
	require('sqlconnection.php');
	$stmt = $DBH->prepare("SELECT * FROM books WHERE title_id= :id");
	$stmt->bindValue(':id', $book_id);
	$stmt->execute();
} catch(PDOException $e) {
  echo "PDO error :" . $exception->getMessage();
}

//include('errordb.php');
$row = $stmt->fetch(PDO::FETCH_ASSOC);

$title_id = "";
$title = $row['title'];
$author = $row['author'];
$isbn = $row['isbn'];
$book_id = (int)$row['title_id'];


if(isset($_POST['confirm'])){
	//$book_id = (int)substr($_POST['id'], 3);
	checkIn($book_id);
}

function checkIn($book_id){
	try{
		require('sqlconnection.php');
		if($_SESSION['usertype'] == "student"){
	  	$sql = "DELETE FROM book_student WHERE title_id = ? AND student_id = ?;";
			$stmt = $DBH->prepare($sql);
 			$stmt->bindParam(1, $book_id, PDO::PARAM_INT);
			$stmt->bindParam(2, $_SESSION["student_id"], PDO::PARAM_INT);
		} else if ($_SESSION['usertype'] == "admin"){
			$sql = "DELETE FROM book_student WHERE title_id = ?;";
			$stmt = $DBH->prepare($sql);
 			$stmt->bindParam(1, $book_id, PDO::PARAM_INT);
		}

	  if($stmt->execute()){
			setAvailable($book_id);
			header("Location: checkedoutbooks.php");
		  exit();
		}
	} catch(PDOException $e) {
	  echo "PDO error :" . $exception->getMessage();
	}
}

function setAvailable($book_id){
	try{
		require('sqlconnection.php');
		$sql = "UPDATE `books` SET `available` = 'Yes' WHERE `title_id` = ?;";
		$stmt = $DBH->prepare($sql);
		$stmt->bindParam(1, $book_id, PDO::PARAM_INT);
		if($stmt->execute()){
			$_SESSION['book_success'] = "Book checked in successfully!";
		}
	} catch(PDOException $e) {
		echo "PDO error :" . $exception->getMessage();
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
<h2 class="login-header">Check In Book</h2>
<form class='login-container' action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">

<input type="text" name="title" value="Title: <?php echo $title; ?>" readonly />

<input type="text" name="author" value="Author: <?php echo $author; ?>" readonly />

<input type="text" name="isbn" value="ISBN: <?php echo $isbn; ?>" readonly />

<input type="submit" name="confirm" value="Confirm Check in"/>
</div>
<?php include('backbutton.php'); ?>
</body>
</html>
