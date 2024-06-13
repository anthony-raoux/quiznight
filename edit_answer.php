<?php
session_start();

// Inclure les fichiers requis
require_once __DIR__ . '/classes/Database.php';
require_once __DIR__ . '/classes/Answer.php';

// Vérification si l'utilisateur est connecté en tant qu'admin
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

// Initialisation des variables et objets
$answer_data = null;

// Vérification de l'existence de l'ID de la réponse
if (isset($_GET['id'])) {
    $answer_id = $_GET['id'];

    // Instanciation de la classe Answer et initialisation avec la connexion à la base de données
    $database = new Database();
    $db = $database->getConnection();
    $answer = new Answer($db);

    // Récupération des détails de la réponse à partir de son ID
    $answer_data = $answer->getAnswerById($answer_id);

    if ($answer_data) {
        // Traitement de la soumission du formulaire de modification de réponse
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['answer_id']) && isset($_POST['answer_text'])) {
                $answer_id = $_POST['answer_id'];
                $answer_text = $_POST['answer_text'];
                $is_correct = isset($_POST['is_correct']) ? 1 : 0;

                if ($answer->updateAnswer($answer_id, $answer_text, $is_correct)) {
                    $success_message = "Réponse mise à jour avec succès.";
                } else {
                    $error_message = "Erreur lors de la mise à jour de la réponse.";
                }
            }
        }
?>

<?php include 'navbar.php'; ?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier Réponse</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
</head>
<body>

<div class="container mt-4">
    <?php if (isset($success_message)): ?>
        <div class="alert alert-success">
            <?php echo $success_message; ?>
        </div>
    <?php endif; ?>
    <?php if (isset($error_message)): ?>
        <div class="alert alert-danger">
            <?php echo $error_message; ?>
        </div>
    <?php endif; ?>

    <div class="card">
        <div class="card-header">
            <h5>Modifier la Réponse</h5>
        </div>
        <div class="card-body">
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?id=' . $answer_id; ?>">
                <input type="hidden" name="answer_id" value="<?php echo htmlspecialchars($answer_data['id']); ?>">
                <div class="form-group">
                    <label for="answer_text">Texte de la réponse:</label>
                    <input type="text" class="form-control" id="answer_text" name="answer_text" value="<?php echo htmlspecialchars($answer_data['answer']); ?>">
                </div>
                <div class="form-group form-check">
                    <input type="checkbox" class="form-check-input" id="is_correct" name="is_correct" <?php echo $answer_data['is_correct'] ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="is_correct">Correct</label>
                </div>
                <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
            </form>
        </div>
    </div>
</div>

<!-- Bootstrap JS and dependencies -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@1.16.1/dist/umd/popper.min.js" integrity="sha384-4a56PQvKdXfrjHLsXzdtH2kEZD1Eg4yZ2Gl6xH/mnIeRMJnlCU3Keh4sCQVW0lN4" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8sh+7Dk0yD95peD/Qlh/wIM+Lcaj8ORmeG7JFg" crossorigin="anonymous"></script>

</body>
</html>

<?php
    } else {
        echo "<div class='container mt-4'>Aucune réponse trouvée avec cet identifiant.</div>";
    }
} else {
    echo "<div class='container mt-4'>Identifiant de réponse non spécifié.</div>";
}
?>
