<?php
session_start();
require 'db.php';
require 'User.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $user = new User($pdo);
    $authenticatedUser = $user->authenticate($username, $password);

    if ($authenticatedUser) {
        $_SESSION['user_id'] = $authenticatedUser['id'];
        $_SESSION['username'] = $authenticatedUser['username'];
        header('Location: dashboard/index.php');
        exit();
    } else {
        $error = "Nom d'utilisateur ou mot de passe incorrect.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion Admin - Quiz Night</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <style>
        .bg_register, .bg_login {
            background: url("./images/quiz_bg.jpeg");
            background-repeat: no-repeat;
            background-attachment: fixed;
            background-size: cover;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }
        .container {
            backdrop-filter: blur(10px);
            background: rgb(37 37 37 / 35%);
        }

        @keyframes shake {
            0% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            50% { transform: translateX(5px); }
            75% { transform: translateX(-5px); }
            100% { transform: translateX(0); }
        }

        .error_message {
            animation: shake 0.5s;
        }

        /*  */

        @keyframes fadeInDown {
            0% {
                opacity: 0;
                transform: translateY(-20px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .fadeInDown {
            animation: fadeInDown 1s;
        }
    </style>
</head>
<body class="bg_login">
    <div class="w-50">
        <?php if (isset($error)) { echo "<div class='alert alert-danger text-center error_message'>$error</div>"; } ?>

        <div class="container border border-dark-subtle rounded text-light fadeInDown">
            <h2 class="mt-5">Connexion Admin</h2>
            <form method="post" action="login.php">
                <div class="form-group">
                    <label for="username">Nom d'utilisateur</label>
                    <input type="text" class="form-control" id="username" name="username" required>
                </div>
                <div class="form-group">
                    <label for="password">Mot de passe</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Se connecter</button>
                <p class="mt-3 text-center">Vous n'avez toujours pas de compte ? <br> <a href="register.php">Inscrivez-vous ici</a>.</p>
            </form>
        </div>
    </div>
</body>
</html>
