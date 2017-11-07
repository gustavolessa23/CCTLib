<?php
$host = '127.0.0.1';
$dbname = 'cctlib';
$user = 'root';
$pass = '';
# MySQL with PDO_MYSQL
$DBH = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
$DBH->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
?>
