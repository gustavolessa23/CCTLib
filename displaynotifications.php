<?php
if(isset($_SESSION['book_err'])){
  echo '<div class="login">';
  echo '<form action="'.htmlspecialchars($_SERVER["PHP_SELF"]).'" method = "post">';
  echo '<input type="submit" name="book_err" value="'.$_SESSION['book_err'].'"/>';
  echo '</form>';
  echo '</div>';
  unset($_SESSION['book_err']);
}

if(isset($_SESSION['book_success'])){
  echo '<div class="login">';
  echo '<form action="'.htmlspecialchars($_SERVER["PHP_SELF"]).'" method = "post">';
  echo '<input type="submit" name="book_success" value="'.$_SESSION['book_success'].'"/>';
  echo '</form>';
  echo '</div>';
  unset($_SESSION['book_success']);
}

if(isset($_SESSION['registration_success'])){
  echo '<div class="login">';
  echo '<form action="'.htmlspecialchars($_SERVER["PHP_SELF"]).'" method = "post">';
  echo '<input type="submit" name="registration_success" value="'.$_SESSION['registration_success'].'"/>';
  echo '</form>';
  echo '</div>';
  unset($_SESSION['registration_success']);
}
?>
