<?php
session_start();
require 'db.php';
require 'User.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password !== $confirm_password) {
        $error = "Les mots de passe ne correspondent pas.";
    } else {
        $user = new User($pdo);
        $existing_user = $user->getUserByUsername($username);

        if ($existing_user) {
            $error = "Ce nom d'utilisateur est déjà utilisé.";
        } else {
            $user->createUser($username, $password);
            $_SESSION['message'] = "Compte créé avec succès. Vous pouvez maintenant vous connecter.";
            header('Location: login.php');
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - Quiz Night</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <style>
        .bg_register {
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
    </style>
</head>
<body class="bg_register">
    <div class="container border border-dark-subtle rounded text-light w-50">
        <div class="text-center my-5 ">
            <h2 >Inscription</h2>
            <p>Bienvenu chez Quiz Night 👋</p>
        </div>
        
        <?php if ($error !== ''): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>
        <form method="post" action="register.php">
            <div class="form-group">
                <label for="username">Nom d'utilisateur</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Mot de passe</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="confirm_password">Confirmer le mot de passe</label>
                <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
            </div>
            <button type="submit" class="btn btn-dark w-100 mt-5">S'inscrire</button>
            <p class="mt-3 text-center">Vous avez déjà un compte? <br> <a class="text-primary px-2 rounded" href="login.php">Connectez-vous ici</a></p>
        </form>
    </div>
</body>
</html>
