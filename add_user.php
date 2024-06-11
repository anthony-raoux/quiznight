<?php
require 'db.php'; // Include the database connection

// Example to add a user
$username = 'Jean';
$password = password_hash('uwu', PASSWORD_BCRYPT);

$stmt = $pdo->prepare('INSERT INTO users (username, password) VALUES (?, ?)');
$stmt->execute([$username, $password]);

echo "User added successfully";
?>
