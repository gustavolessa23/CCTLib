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
	<?php

	include('init.php');
	include('checkstudent.php');
  include('setprevious.php');

  if(isset($_GET['id'])){
    $_SESSION['book_id'] = $_GET['id'];
  }

  $book_id = $_SESSION['book_id'];

	$student_id = $_SESSION['student_id'];

	$title = "";
	$author = "";
	$isbn = "";
	$date_current = "";
	$date_final = "";


	include('sqlconnection.php');
	try{
		$stmt = $DBH->prepare("SELECT * FROM books WHERE title_id= :id");
		$stmt->bindValue(':id', $book_id);
		$stmt->execute();
	} catch(PDOException $e) {
	  echo "PDO error :" . $exception->getMessage();
	}

	$row = $stmt->fetch(PDO::FETCH_ASSOC);
	$title_id = "";
	$title = $row['title'];
	$author = $row['author'];
	$isbn = $row['isbn'];
	//$book_id = (int)$row['title_id'];


	if(isset($_POST['confirm'])){

		//$book_id = (int)substr($_POST['id'], 3);

		if(verifyBook($book_id)){
      checkoutBook($book_id, $student_id);
		} else {
      $_SESSION['book_err'] = 'Sorry, but the selected book has already been taken!';
			header('Location:  students.php');
			exit();
		}
		include('errordb.php');
	}

	function verifyBook($book){
    try{
      require('sqlconnection.php');
  		$sql = "SELECT * FROM book_student WHERE title_id = ?;";
  		$stmt = $DBH->prepare($sql);
  		$stmt->bindParam(1, $book, PDO::PARAM_INT);
  		$stmt->execute();
  		$res = $stmt->fetchAll(PDO::FETCH_ASSOC);
  		if(!$res){
  			return true;
  		} else {
  			return false;
        $_SESSION['book_err'] = 'Sorry, but the selected book has already been taken!';
  		}
    } catch(PDOException $e) {
        echo "PDO error :" . $exception->getMessage();
    }
	}

	function checkoutBook($book_id, $student_id){
    try{
      require('sqlconnection.php');
      $sql = "INSERT INTO book_student (title_id, student_id, date_taken, date_return) VALUES (?, ?, ?, ?);";
      $stmt = $DBH->prepare($sql);
      $date_current = date('Y-m-d');
      $date_temp = new DateTime(date('Y-m-d'));
      $date_temp->modify('+1 week');
      $date_final = $date_temp->format('Y-m-d');

      $stmt->bindParam(1, $book_id, PDO::PARAM_INT);
      $stmt->bindParam(2, $student_id, PDO::PARAM_INT);
      $stmt->bindParam(3, $date_current, PDO::PARAM_STR);
      $stmt->bindParam(4, $date_final, PDO::PARAM_STR);
      if($stmt->execute()){
        setAvailable($book_id);
      }
  	}catch(PDOException $e) {
      echo "PDO error :" . $exception->getMessage();
    }
  }

  function setAvailable($book_id){
    try{
      require('sqlconnection.php');
      $sql = "UPDATE `books` SET `available` = 'No' WHERE `title_id` = ?;";
      $stmt = $DBH->prepare($sql);
      $stmt->bindParam(1, $book_id, PDO::PARAM_INT);
      if($stmt->execute()){
          $_SESSION['book_success'] = "Book checked out successfully!";
        header('Location:  students.php');
        exit();
      }
    } catch(PDOException $e) {
      echo "PDO error :" . $exception->getMessage();
    }
  }
	?>

<div class="login">
  <div class="login-triangle"></div>
<h2 class="login-header">Check Out Book</h2>
<form class='login-container' action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">

<input type="text" name="title" value="Title: <?php echo $title; ?>" readonly />

<input type="text" name="author" value="Author: <?php echo $author; ?>" readonly />

<input type="text" name="isbn" value="ISBN: <?php echo $isbn; ?>" readonly />

<input type="submit" name="confirm" value="Confirm Checkout"/>
</div>
