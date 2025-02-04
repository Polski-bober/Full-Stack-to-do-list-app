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

            // when submitted form forward user to to_do_app.php
            header("Location: ../to_do_app.php");
        } else if ($selectType == "logIn") {
            // prepare sql and bind parameters
            $query = "SELECT * FROM `users` WHERE username = :username AND password = :password";
            $stmt = $conn->prepare($query);

            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':password', $password);

            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                // when submitted form forward user to to_do_app.php
                header("Location: ../to_do_app.php");
            } else {
                echo "<h2 style=text-align:center;>User with this username and password does not exist. Please try again. <h2>";

                // close connection to database and die
                $pdo = null;
                $stmt = null;
                die();
            }
        } else {
            // close connection to database and die
            $pdo = null;
            $stmt = null;
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
