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
include('checkadmin.php');
include('displaynotifications.php');

$insert_title="";
$insert_author="";
$insert_isbn="";
$insert_title_err="";
$insert_author_err="";
$insert_isbn_err="";


if(isset($_POST['insert'])){
	$insert_title = $_POST['book_title'];
	$insert_author= $_POST['book_author'];
	$insert_isbn= $_POST['book_isbn'];
	$regex_title_author = '/[\S]/';
	$regex_isbn = '/^\d{10}$/';

	if(empty($insert_title) || !preg_match($regex_title_author, $insert_title)){
		$insert_title_err = "Please insert the title to be added.";
	}
	if(empty($insert_author) || !preg_match($regex_title_author, $insert_author)){
		$insert_author_err = "Please insert the author to be added.";
	}
	if(empty($insert_title) || !preg_match($regex_isbn, $insert_isbn)){
		$insert_isbn_err = "Please insert ISBN containing exacly 10 digits.";
	}

  if(empty($insert_title_err) && empty($insert_author_err) && empty($insert_isbn_err)){

			if (checkIsbn($insert_isbn) == false) { //no user with this ID exists

				registerBook($insert_title, $insert_author, $insert_isbn);

			} else {
				echo '<span class ="error">ISBN already exists in database!</span>';
			}
  } else {
		echo "You have one or more errors.";
	}
}

if(isset($_POST['checkedout'])){
	header("location: checkedoutbooks.php");
}

if(isset($_POST['overdue'])){
	header("location: overduebooks.php");
}

function checkIsbn($isbn){
	try{
	  require 'sqlconnection.php';
	  $stmt = $DBH->prepare("SELECT * FROM books WHERE isbn = ?" );
	  $stmt->bindParam(1, $isbn);
	  $stmt->execute();
	  if ($stmt->rowCount() == 0){
	    return false;
	  } else {
	    return true;
	  }
	} catch(PDOException $e) {
		echo "PDO error :" . $exception->getMessage();
	}
}

function registerBook($title, $author, $isbn){
	try{
		require 'sqlconnection.php';
	  $sql = "INSERT INTO books (title, author, isbn) VALUES (?, ?, ?);";
	  $stmt = $DBH->prepare($sql);
	  $stmt->bindParam(1, $title, PDO::PARAM_STR);
	  $stmt->bindParam(2, $author, PDO::PARAM_STR);
	  $stmt->bindParam(3, $isbn, PDO::PARAM_STR);
	  if($stmt->execute()){
			$_SESSION['book_success'] = "Book registered successfully!";
			header('Location: admin.php');
		}
	} catch(PDOException $e) {
		echo "PDO error :" . $exception->getMessage();
	}
}


?>
<h2 class="login-header">Admin's Page</h2>

<div class="login">
  <div class="login-triangle"></div>
<h2 class="login-header">Insert New Book</h2>
<form class='login-container' action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">

<input type="text" name="book_title" value="<?php echo $insert_title; ?>" pattern="\S(.*\S)?" placeholder="Title" required="required" title="Insert book title"/>
  <span class ="error"> <?php echo $insert_title_err; ?></span>

<input type="text" name="book_author" value="<?php echo $insert_author; ?>" pattern="\S(.*\S)?" placeholder="Author" required="required" title="Insert author"/>
  <span class ="error"> <?php echo $insert_author_err; ?></span>

<input type="text" name="book_isbn" value="<?php echo $insert_isbn; ?>" pattern="^\d{10}$" placeholder="ISBN (10 digits)" required="required" title="Insert ISBN"/>
  <span class ="error"> <?php echo $insert_isbn_err; ?></span>

<input type="submit" name="insert" value="Add book"/>
</form>
</div>

<div class="login">
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
<input type="submit" name="checkedout" value="List Checked Out Books" class='button'/>
</form>
</div>

<div class="login">
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
<input type="submit" name="overdue" value="List Overdue Books" class='button'/>
</form>
</div>
</body>
</html>
