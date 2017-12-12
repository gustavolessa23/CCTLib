<?php
session_start();
$token = md5(session_id());
include('header.php');
?>
