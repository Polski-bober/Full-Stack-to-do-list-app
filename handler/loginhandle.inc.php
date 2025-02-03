<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = htmlentities($_POST['username']);
    $password = htmlentities($_POST['password']);
    $selectType = htmlentities($_POST['selectType']);


    // when submited empty form forward user to index.php
    if (empty($username) || empty($password)) {
        exit();
    }

    try {
        require_once 'dbh.inc.php';

        if ($selectType == "createAccount") {
            // prepare sql and bind parameters
            $query = "INSERT INTO users (username, password) VALUES (:username, :password)";
            $stmt = $conn->prepare($query);

            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':password', $password);

            $stmt->execute();

            // close connection to database
            $pdo = null;
            $stmt = null;

            // when submitted form forward user to to_do_app.php kill script
            header("Location: ../to_do_app.php");
            die();
        } else if ($selectType == "logIn") {

            header("Location: ../to_do_app.php");
        } else {
            die();
        }
    } catch (PDOException $e) {
        if ($e->errorInfo[1] == 1062) {
            echo "<h2 style=text-align:center;>User with this username already exists. Please choose another one. <h2>";
        } else {
            echo "<h2 style=text-align:center;>Error: " . $e->getMessage() . "<br /> </h2>";
            echo "<h2 style=text-align:center;>Contact website administrator </h2>";
        }
        die();
    }
} else {
    exit();
}
