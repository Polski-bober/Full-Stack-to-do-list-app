<?php

$dbadress = "mysql:host=localhost;dbname=to_do_list_app";
$dbusername = "root";
$dbpassword = "";

try {
    $conn = new PDO($dbadress, $dbusername, $dbpassword);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
