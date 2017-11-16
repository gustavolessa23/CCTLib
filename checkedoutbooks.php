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
include('checkanyuser.php');
include('displaynotifications.php');
?>

<div class="login">
    <div class="login-triangle"></div>
  <h2 class="login-header">Checked Out Books</h2>
  <form class='login-container' action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
<?php

try{
  require 'sqlconnection.php';
  if($_SESSION['usertype'] == "admin"){
    $sql = "SELECT bs.student_id, b.title, bs.title_id, bs.date_return FROM book_student bs INNER JOIN books b WHERE bs.title_id=b.title_id;";
    $stmt = $DBH->prepare($sql);
  } else if ($_SESSION['usertype'] == "student"){
    $sql = "SELECT bs.student_id, b.title, bs.title_id, bs.date_return FROM book_student bs INNER JOIN books b WHERE bs.title_id=b.title_id AND bs.student_id=:student_id;";
    $stmt = $DBH->prepare($sql);
    $stmt->bindValue(':student_id', $_SESSION["student_id"]);
  }
  if ($stmt->execute()) {
    showTable($stmt);
  } else {
  	echo "Some execution error.";
  }
} catch(PDOException $e) {
echo "PDO error :" . $e->getMessage();
}

include('errordb.php');
// get the rows and put it in a variable
function showTable($stmt){
  $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
  if(!$rows){
    echo "<table>";
    echo "<tr><th>There is no checked out books!</th>";
    echo "</table>";
  } else {
    echo "<table>";
    if($_SESSION['usertype'] == "admin"){
      echo "<tr><th>Student ID</th><th>Book Title</th><th>Due date</th><th>Check in</th></tr>";
      foreach($rows as $row){
      	echo "<tr>";
        echo "<td>";
      	echo $row['student_id'];
      	echo "</td>";
      	echo "<td>";
      	echo $row['title'];
      	echo "</td>";
        echo "<td>";
        echo $row['date_return'];
        echo "</td>";
      	echo "<td>";
      	echo "<a href=checkin.php?id=".$row['title_id'].">Check in</a>";
      	echo "</td>";
      	echo "</tr>";
      }
    } else if ($_SESSION['usertype'] == "student"){
      echo "<tr><th>Student ID</th><th>Book Title</th><th>Due date</th><th>Return</th></tr>";
      foreach($rows as $row){
      	echo "<tr>";
        echo "<td>";
      	echo $row['student_id'];
      	echo "</td>";
      	echo "<td>";
      	echo $row['title'];
      	echo "</td>";
        echo "<td>";
        echo $row['date_return'];
        echo "</td>";
      	echo "<td>";
      	echo "<a href=checkin.php?id=".$row['title_id'].">Return Book</a>";
      	echo "</td>";
      	echo "</tr>";
      }
    }
    echo "</table>";
  }
}
?>

</div>
</form>
<?php include('backbutton.php'); ?>
</body>
</html>
