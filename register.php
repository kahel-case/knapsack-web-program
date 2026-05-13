<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Page</title>
</head>

<body>
    <div>
        <h1>Register</h1>
        <form action="register_user.php" method="post">
            <div>
                <label for="username">Username: </label>
                <input type="text" id="username" name="username" required>
            </div>
            <div>
                <label for="password">Password: </label>
                <input type="password" id="password" name="password" required>
            </div>
            <div>
                <label for="confirm_password">Confirm Password: </label>
                <input type="password" id="confirm_password" name="confirm_password" required>
            </div>
            <div>
                <button type="submit">Register</button>
            </div>
        </form>
    </div>
</body>

</html>