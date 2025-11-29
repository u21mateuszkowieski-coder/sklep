<?php

  $host = 'ecommercestore.zxp-odp.smallhost.pl';
  $user = 'm3573_casualUser';
  $pass = 'User1234';
  $dbname = 'm3573_db_ecommercestore';
  
  $conn = new mysqli($host, $user, $pass, $dbname);
  
  if ($conn->connect_error) {
    die("Błąd połączenia: " . $conn->connect_error);
  }
  
  $conn->set_charset("utf8");

?>
