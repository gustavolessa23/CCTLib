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

?>

<div class="login">
    <div class="login-triangle"></div>
  <h2 class="login-header">Overdue Books</h2>
  <form class='login-container' action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
<?php

try{
require 'sqlconnection.php';
$sql = "SELECT bs.student_id, b.title, bs.date_return, bs.title_id FROM book_student bs INNER JOIN books b WHERE bs.title_id=b.title_id AND bs.date_return < CURDATE();";
$stmt = $DBH->prepare($sql);
if ($stmt->execute()) {
  $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
  if(!$res){
    echo "<table>";
    echo "<tr><th>There is no overdue books!</th>";
    echo "</table>";
  } else {
    showTable($stmt);
  }
} else {
	echo "Some execution error.";
}

} catch(PDOException $e) {
echo "PDO error :" . $exception->getMessage();
}
// select the correct table


include('errordb.php');
// get the rows and put it in a variable
function showTable($stmt){
  $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

  echo "<table>";
  echo "<tr><th>Student ID</th><th>Book Title</th><th>Date Due</th><th>Checkin</th></tr>";

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
  	echo "<a href=checkin.php?id=".$row['title_id'].">Checkin</a>";
  	echo "</td>";
  	echo "</tr>";
  }
  echo "</table>";
}
?>

</div>
</form>
</body>
</html>
