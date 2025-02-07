<!DOCTYPE html>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<style>
    * {
        font-family: Arial, sans-serif;
    }

    body {
        background-color: #222233;
    }

    h2 {
        color: white;
    }

    button:hover {
        background-color: #0056b3;
    }

    input {
        padding: 0.75rem 1.5rem;
        background-color: #007bff;
        color: #fff;
        border: none;
        border-radius: 5px;
        margin-left: 0.5rem;
        cursor: pointer;
        font-size: 1rem;
    }
</style>
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
                echo "<div style=display:flex;justify-content:center;align-items:center;flex-direction:column;height:100vh;margin:0;padding:0;box-sizing:border-box;>";
                echo "<h2 style=text-align:center;>User with this username and password does not exist. Please try again. <h2>";
                echo '
                    <a href="../index.php" style="padding-left:auto; padding-right:auto;">  
                        <input type="submit" value="Return to login page"/>
                    </a>
                </div>';

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

        // start the session
        session_start();

        // stores posted values in the session variables
        $_SESSION['username'] = $username;
        $_SESSION['password'] = $password;
    } catch (PDOException $e) {
        if ($e->errorInfo[1] == 1062) {
            echo "
            <div style=display:flex;justify-content:center;align-items:center;flex-direction:column;height:100vh;margin:0;padding:0;box-sizing:border-box;>
            <h2>User with this username already exists. Please choose another one. <h2>";
            echo '
                    <a href="../index.php">  
                        <input type="submit" value="Return to login page"/>
                    </a>
                </div>';
        } else {
            echo "<div style=display:flex;justify-content:center;align-items:center;flex-direction:column;height:100vh;margin:0;padding:0;box-sizing:border-box;>";
            echo "<h2>Error: " . $e->getMessage() . "</h2> <br />" . "<h2>Contact website administrator </h2>" . "</div>";
        }
        die();
    }
} else {
    exit();
}
