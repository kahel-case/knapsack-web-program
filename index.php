<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Knapsack</title>
</head>

<body>
    <div>
        <h1>Login</h1>
        <form action="login.php" method="post">
            <div>
                <label for="username">Username: </label>
                <input type="text" id="username" name="username" required>
            </div>
            <div>
                <label for="password">Password: </label>
                <input type="password" id="password" name="password" required>
            </div>
            <div>
                <button type="button" onclick="window.location.href='register.php'">Sign-up</button>
                <button type="submit">Login</button>
            </div>
        </form>
    </div>
</body>

</html>