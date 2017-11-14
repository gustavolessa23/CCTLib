<?php

echo '<div class="login">';
echo '<form action="'.htmlspecialchars($_SERVER["PHP_SELF"]).'" method="post">';
if($_SESSION['usertype'] == "admin"){
  echo '<input type="submit" name="backadmin" value="Go back to Admin page" class="button"/>';
}

if($_SESSION['usertype'] == "student"){
  echo '<input type="submit" name="backstudents" value="Go back to Students page" class="button"/>';
}
echo '</form>';
echo '</div>';

if (isset($_POST['backstudents'])){
  header("location: students.php");
}
if (isset($_POST['backadmin'])){
  header("location: admin.php");
}

?>
