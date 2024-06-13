<?php
session_start();

require_once './classes/Database.php';
require_once './classes/Admin.php';


$database = new Database();
$db = $database->getConnection();

$admin = new Admin($db);

if ($_POST) {
    $admin->username = $_POST['username'];
    $admin->password = $_POST['password'];

    if ($admin->login()) {
        $_SESSION['admin_id'] = $admin->id;
        header("Location: admin.php");
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
    <?php if (isset($error_message)) { echo $error_message; } ?>
</body>
</html>
