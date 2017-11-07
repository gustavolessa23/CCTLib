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
include('checkstudent.php');
include('displaynotifications.php');

$searchText = "";
$searchField = "";
$searchFieldErr = "";
$searchTextErr = "";
$student_id = $_SESSION['student_id'];
$regex_isbn = '/^\d{10}$/';
$isbn_err = "";


if(isset($_POST['searchoption']) && $_POST['searchoption']=='isbn' && !preg_match($regex_isbn, $searchText)){
  $isbn_err = "Please insert ISBN containing exacly 10 digits.";
}

if(isset($_POST['checkedout'])){
	header("location: checkedoutbooks.php");
}

if(isset($_POST['search'])){
  $searchText = $_POST['searchtext'];
  if(isset($_POST['searchoption'])){
    $searchField = $_POST['searchoption'];
  } else {
    $searchFieldErr = "Please choose the field to search for";
  }

  if(empty($searchFieldErr) && empty($searchTextErr) && empty($isbn_err)){
    $_SESSION['searchtext'] = $searchText;
    $_SESSION['searchoption'] = $searchField;
		$_SESSION['pageOrigin'] = 'students';
      header('Location: books.php');
			exit();
  }
}

?>
<h2 class="login-header">Student's Page</h2>
<div class="login">
  <div class="login-triangle"></div>
<h2 class="login-header">Search Books</h2>
<form class='login-container' action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
<input type="radio" name="searchoption" value="title"> Title
<input type="radio" name="searchoption" value="author"> Author
<input type="radio" name="searchoption" value="isbn"> ISBN
<br><input type="text" name="searchtext" value="<?php echo $searchText; ?>" title="Search"/>
<span class="error"> <?php echo $isbn_err;?></span>
<span class="error"> <?php echo $searchFieldErr;?></span>
<br><input type="submit" name="search" value="Search"/>
</form>
</div>

<div class="login">
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
<input type="submit" name="checkedout" value="List Checked Out Books" class='button'/>
</form>
</div>
</body>
</html>
