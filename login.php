<?php
session_start();
require 'db.php'; // Include the database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Debugging: echo received input (can be removed after testing)
    echo "Username: $username<br>";
    echo "Password: $password<br>";

    // Prepare and execute the SQL statement to select the user
    $stmt = $pdo->prepare('SELECT * FROM users WHERE username = ?');
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    // Debugging: var_dump the user data (can be removed after testing)
    if ($user) {
        var_dump($user);
    } else {
        echo "User not found";
    }

    // Check if the user exists and verify the password
    if ($user && password_verify($password, $user['password'])) {
        // Set session variables and redirect to admin page
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        header('Location: admin.php');
        exit;
    } else {
        echo 'Invalid username or password';
    }
}
?>

