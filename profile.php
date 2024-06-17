<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

require_once __DIR__ . '/classes/Database.php';
require_once __DIR__ . '/classes/Admin.php';

$database = new Database();
$db = $database->getConnection();

$admin = new Admin($db);
$admin->id = $_SESSION['admin_id'];

// Charger les informations de l'admin à partir de la base de données
if (!$admin->getAdminById()) {
    // Rediriger s'il y a une erreur de chargement du profil
    $_SESSION['error'] = "Unable to load admin profile.";
    header("Location: index.php");
    exit;
}

// Traitement de la mise à jour du mot de passe
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_password'])) {
    $new_password = $_POST['new_password'];

    if ($admin->updatePassword($new_password)) {
        $_SESSION['success'] = "Mot de passe mis à jour avec succès.";
    } else {
        $_SESSION['error'] = "Une erreur s'est produite lors de la mise à jour du mot de passe.";
    }

    header("Location: profile.php");
    exit;
}


// Traitement de la suppression du compte
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_account'])) {
    if ($admin->delete()) {
        session_destroy();
        header("Location: login.php");
        exit;
    } else {
        $_SESSION['error'] = "Une erreur s'est produite lors de la suppression du compte.";
        header("Location: profile.php");
        exit;
    }
}

?>

<?php include 'navbar.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Profile</title>
    <!-- Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="custom.css">
</head>
<body>
    <div class="container mt-5">
        <h1>Admin Profile</h1>
        <p>Bienvenue, <?php echo htmlspecialchars($admin->username); ?></p>
        
        <?php if (isset($_SESSION['error'])) : ?>
            <div class="alert alert-danger" role="alert">
                <?php echo $_SESSION['error']; ?>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['success'])) : ?>
            <div class="alert alert-success" role="alert">
                <?php echo $_SESSION['success']; ?>
            </div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <form action="profile.php" method="post">
            <div class="form-group">
                <label for="new_password">Nouveau mot de passe</label>
                <input type="password" name="new_password" id="new_password" class="form-control" required>
            </div>
            <button type="submit" name="update_password" class="btn btn-primary">Mettre à jour le mot de passe</button>
        </form>

        <form action="profile.php" method="post" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer votre compte? Cette action est irréversible.');">
            <button type="submit" name="delete_account" class="btn btn-danger mt-3">Supprimer mon compte</button>
        </form>

        <a href="index.php" class="btn btn-secondary mt-3">Retour à l'accueil</a>
    </div>

    <!-- Bootstrap JS, Popper.js, and jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
