<?php
    $db_server = 'sql100.infinityfree.com';
    $db_user = 'if0_40401514';
    $db_pass = '12ADMIN34';
    $db_name = 'if0_40401514_db_ecommercestore';

    $conn = mysqli_connect($db_server, $db_user, $db_pass, $db_name);

    if($conn) {
        echo 'DZIAALAAAAA';
    }
?>