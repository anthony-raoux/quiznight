<?php
session_start();
include 'db.php';

// Check if the user is logged in
if (isset($_SESSION['user_id'])) {
    // User is logged in, display the quiz list
    $stmt = $pdo->query('SELECT * FROM quizzes');
    $quizzes = $stmt->fetchAll();
} else {
    // User is not logged in, display the login form
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Login</title>
    </head>
    <body>
        <h2>Login</h2>
        <form action="login.php" method="post">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required><br><br>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required><br><br>
            <input type="submit" value="Login">
        </form>
    </body>
    </html>
    <?php
    // End the script here since we don't want to display the quiz list
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
</head>
<body>
    <h1>Welcome to the Dashboard</h1>
    <h2>Available Quizzes</h2>
    <ul>
        <?php foreach ($quizzes as $quiz): ?>
            <li><?php echo htmlspecialchars($quiz['title']); ?></li>
        <?php endforeach; ?>
    </ul>
</body>
</html>
