<?php
session_start();

// Vérifie si l'utilisateur est connecté en tant qu'admin
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

// Inclusion des fichiers requis
require_once __DIR__ . '/classes/Database.php';
require_once __DIR__ . '/classes/Quiz.php';

// Initialisation de la connexion à la base de données et de l'objet Quiz
$database = new Database();
$db = $database->getConnection();
$quiz = new Quiz($db);

// Vérifie si l'ID du quiz à éditer est présent dans l'URL
if (isset($_GET['id'])) {
    $quiz_id = $_GET['id'];

    // Utilisation de la méthode readOne() pour récupérer les détails du quiz
    $stmt = $quiz->readOne($quiz_id);

    // Vérification si le quiz existe
    if ($stmt->rowCount() > 0) {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $quiz_title = $row['title'];
        $quiz_description = $row['description'];
    } else {
        // Si le quiz n'est pas trouvé, rediriger ou afficher un message d'erreur
        echo "Quiz not found.";
        exit;
    }
} else {
    // Si l'ID du quiz n'est pas présent dans l'URL, gérer cette situation
    echo "Quiz ID is missing.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Quiz</title>
    <!-- Intégration de Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <!-- Navbar -->
    <?php include 'navbar.php'; ?>

    <div class="container mt-5">
        <h1>Edit Quiz</h1>
        <form action="update_quiz.php" method="post">
            <input type="hidden" name="quiz_id" value="<?php echo htmlspecialchars($quiz_id); ?>">
            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" name="title" id="title" class="form-control" value="<?php echo htmlspecialchars($quiz_title); ?>" required>
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea name="description" id="description" class="form-control" required><?php echo htmlspecialchars($quiz_description); ?></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Update Quiz</button>
        </form>
    </div>

    <!-- Footer -->
    <?php include 'footer.php'; ?>

    <!-- Intégration de Bootstrap JS (optionnel, dépendant de vos besoins) -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
