<?php

$user = 'root';
$password = 'root';
$db = 'TIJN';
$host = 'localhost';
$port = 8889;

$link = mysqli_init();
$conn = mysqli_real_connect(
   $link, 
   $host, 
   $user, 
   $password, 
   $db,
   $port
);
$db_selected = mysqli_select_db(
   $db, 
   $link
);

?>