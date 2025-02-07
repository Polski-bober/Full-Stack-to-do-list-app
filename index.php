<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="index_style.css">
    <title>To Do List App</title>
</head>

<body>
    <form action="handler/loginhandle.inc.php" method="POST">
        <h1>Login</h1>

        <label for="username">Username</label>
        <input type="text" name="username" id="username" required> <br />

        <label for="password">Password</label>
        <input type="password" name="password" id="password" required> <br />

        <select name="selectType" id="selectType">
            <option value="logIn">Log In</option>
            <option value="createAccount">Create Account</option>
        </select> <br />

        <button type="submit">Login/Create Account</button>
    </form>
</body>

</html>