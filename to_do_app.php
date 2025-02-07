<?php
// Start the session
session_start();

// Access the session value
if (isset($_SESSION['username']) && isset($_SESSION['password'])) {
    // echo 'Username: ' . $_SESSION['username'] . "<br />";
    // echo 'Password: ' . $_SESSION['password'];
} else {
    echo '
        <div style=display:flex;justify-content:center;align-items:center;flex-direction:column;height:100vh;margin:0;padding:0;box-sizing:border-box;>>
            <a href="../index.php" style="padding-left:auto; padding-right:auto;">  
                <input type="submit" value="Return to login page"/>
            </a>
        </div>';
    echo "
        <style>
        .container {
            display: none;
        }
        </style>
        ";
}

// Include database handler
require_once './handler/dbh.inc.php';

try {
    // Use variables from dbh.inc.php
    $conn = new PDO($dbadress, $dbusername, $dbpassword);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Handle actions
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($_POST['contents'])) {
        // Add new task
        $stmt = $conn->prepare("INSERT INTO lists (username, contents) VALUES (?, ?)");
        $stmt->execute([$_SESSION['username'], $_POST['contents']]);
    } elseif (!empty($_POST['delete_id'])) {
        // Delete task
        $stmt = $conn->prepare("DELETE FROM lists WHERE listID = ? AND username = ?");
        $stmt->execute([$_POST['delete_id'], $_SESSION['username']]);
    }
}

// Fetch tasks for current user
$stmt = $conn->prepare("SELECT * FROM lists WHERE username = ?");
$stmt->execute([$_SESSION['username']]);
$tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>To Do List for <?= htmlspecialchars($_SESSION['username']) ?></title>
    <link rel="stylesheet" href="./to_do_app_style.css">

<body>
    <div class="container">
        <h1>📝 Todo List for <?= htmlspecialchars($_SESSION['username']) ?></h1>

        <form class="add-form" method="POST">
            <input type="text" name="contents" placeholder="What needs to be done?" required autocomplete="off">
            <button type="submit" class="btn btn-primary">Add Task</button>
        </form>

        <ul class="task-list">
            <?php foreach ($tasks as $task): ?>
                <li class="task-item">
                    <span><?= htmlspecialchars($task['contents']) ?></span>
                    <form method="POST">
                        <input type="hidden" name="delete_id" value="<?= $task['listID'] ?>">
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</body>

</html>