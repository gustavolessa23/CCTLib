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

$searchRaw = $_SESSION['searchtext'];
$search = '%'.$searchRaw.'%';
$student_id = $_SESSION['student_id'];

// create the connection
require('sqlconnection.php');

if($_SESSION['searchoption'] == 'title'){
	$stmt = $DBH->prepare("SELECT * FROM books WHERE title LIKE :search");
} else if ($_SESSION['searchoption'] == 'author'){
	$stmt = $DBH->prepare("SELECT * FROM books WHERE author LIKE :search");
} else if ($_SESSION['searchoption'] == 'isbn'){
	$stmt = $DBH->prepare("SELECT * FROM books WHERE isbn LIKE :search");
}

$stmt->bindValue(':search', $search);

if($stmt->execute()){
  showTable($stmt);
} else {
	echo "Some execution error.";
}
include('errordb.php');

// get the rows and put it in a variable


function showTable($stmt){
  $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
  echo '<div class="login">';
  echo '<div class="login-triangle"></div>';
  echo '<h2 class="login-header">Books</h2>';
  echo '<form class="login-container">';
  echo "<table>";
  echo "<tr><th>ID</th><th>Title</th><th>Author</th><th>ISBN</th><th>Available</th></tr>";

  foreach($rows as $row){
  	echo "<tr>";
  	echo "<td>";
  	echo $row['title_id'];
  	echo "</td>";
  	echo "<td>";
  	echo $row['title'];
  	echo "</td>";
  	echo "<td>";
  	echo $row['author'];
  	echo "</td>";
  	echo "<td>";
  	echo $row['isbn'];
  	echo "</td>";
  	echo "<td>";
  	echo $row['available'];
  	echo "</td>";
  	echo "<td>";
    if($row['available'] == 'Yes'){
      echo "<a href=checkout.php?id=".$row['title_id'].">Checkout</a>";
    }
  	echo "</td>";
  	echo "</tr>";
  }
  echo "</table>";
  echo "</form>";
  echo '</div>';

}

?>
<?php include('backbutton.php'); ?>
</body>
</html>
