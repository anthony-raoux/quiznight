<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once './classes/Database.php';
require_once './classes/Admin.php';

$database = new Database();
$db = $database->getConnection();

$admin = new Admin($db);

$error_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $admin->username = $_POST['username'];
    $admin->password = $_POST['password'];

    if ($admin->login()) {
        $_SESSION['admin_id'] = $admin->id;
        header("Location: admin.php");
        exit;
    } else {
        $error_message = "Invalid username or password.";
    }
}
?>

<?php include 'navbar.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
</head>
<body>
    
    <form action="login.php" method="post">
        <div>
            <label for="username">Username</label>
            <input type="text" name="username" id="username" required>
        </div>
        <div>
            <label for="password">Password</label>
            <input type="password" name="password" id="password" required>
        </div>
        <button type="submit">Login</button>
    </form>
    <?php if ($error_message): ?>
        <p><?php echo $error_message; ?></p>
    <?php endif; ?>
</body>
</html>
