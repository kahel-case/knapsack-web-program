<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="resources/bootstrap.min.css">
    <link rel="stylesheet" href="resources/dataTables.dataTables.css">
    <link rel="stylesheet" href="resources/extra.css">
    <title>Knapsack</title>

</head>

<body>

    <div class="login-card">
        <!-- GIF goes here -->
        <div class="gif-container">
            <img src="resources/images/cute-dog.gif" alt="Animated GIF">
        </div>
        <h1>Snapsack: Login</h1>
        <form action="login.php" method="post">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" id="username" name="username" class="form-control" required>
            </div>
            <div class="mb-4">
                <label for="password" class="form-label">Password</label>
                <input type="password" id="password" name="password" class="form-control" required>
            </div>
            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-warning rounded-pill text-white">Login</button>
                <button type="button" class="btn btn-outline-warning rounded-pill" onclick="window.location.href='register.php'">Sign Up</button>
            </div>
        </form>
    </div>

</body>

</html>