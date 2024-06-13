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
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-w1QBRaD/smK2uWRzyD1O3uTps9pBBHiC4N9iwUb5Kr8KSkM+SOJwB3TrC6K06fxE" crossorigin="anonymous">
</head>
<body>

<div class="container mt-4">
    <form action="login.php" method="post" class="form-signin">
        <h2 class="form-signin-heading">Please sign in</h2>
        <div class="form-group">
            <label for="username" class="sr-only">Username</label>
            <input type="text" name="username" id="username" class="form-control" placeholder="Username" required autofocus>
        </div>
        <div class="form-group">
            <label for="password" class="sr-only">Password</label>
            <input type="password" name="password" id="password" class="form-control" placeholder="Password" required>
        </div>
        <button class="btn btn-lg btn-primary btn-block" type="submit">Login</button>
        <?php if ($error_message): ?>
            <div class="alert alert-danger mt-2"><?php echo $error_message; ?></div>
        <?php endif; ?>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@1.16.1/dist/umd/popper.min.js" integrity="sha384-4a56PQvKdXfrjHLsXzdtH2kEZD1Eg4yZ2Gl6xH/mnIeRMJnlCU3Keh4sCQVW0lN4" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8sh+7Dk0yD95peD/Qlh/wIM+Lcaj8ORmeG7JFg" crossorigin="anonymous"></script>

</body>
</html>
