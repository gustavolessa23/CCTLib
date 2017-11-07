<?php
if(!isset($_SESSION['token']) || $_SESSION['token'] !== $token) {
  echo "Please return to login page in order to restart your session.";
  echo '<br><a href="login.php">Login</a>';
  die();
}
?>
