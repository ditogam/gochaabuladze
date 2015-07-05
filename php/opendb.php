<?php


try {
    // This is an example opendb.php
    $conn = new mysqli($servername, $username, $password, $dbname);

    $conn->set_charset("utf8");
    $conn->autocommit(false);
} catch (Exception $e) {
    echo 'Caught exception: ',  $e->getMessage(), "\n";
}

?>