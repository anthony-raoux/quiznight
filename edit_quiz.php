<?php
session_start();

// Vérification si l'utilisateur est connecté en tant qu'admin
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

// Inclure les fichiers requis
require_once __DIR__ . '/classes/Database.php';
require_once __DIR__ . '/classes/Quiz.php';
require_once __DIR__ . '/classes/Answer.php';

// Initialisation de la connexion à la base de données et des objets Quiz et Answer
$database = new Database();
$db = $database->getConnection();
$quiz = new Quiz($db);
$answer = new Answer($db);

// Variables pour stocker les détails du quiz et les réponses
$quiz_id = null;
$quiz_title = '';
$quiz_description = '';
$answers = []; // Tableau pour stocker les réponses

if (isset($_GET['id'])) {
    $quiz_id = $_GET['id'];

    // Utilisation de la méthode readOne() de la classe Quiz pour récupérer les détails du quiz
    $quiz_data = $quiz->readOne($quiz_id);

    // Vérification si le quiz existe
    if ($quiz_data) {
        $quiz_title = $quiz_data['title'];
        $quiz_description = $quiz_data['description'];

        // Utilisation de la méthode getAnswers() de la classe Answer pour récupérer les réponses associées
        $answers = $answer->getAnswers($quiz_id);

        // Vérification si des réponses existent
        if ($answers) {
            foreach ($answers as $ans) {
                ?>
                <div class="form-group">
                    <input type="hidden" name="answers[<?php echo $ans['id']; ?>][answer_id]" value="<?php echo $ans['id']; ?>">
                    <input type="text" name="answers[<?php echo $ans['id']; ?>][answer_text]" class="form-control" value="<?php echo htmlspecialchars($ans['answer']); ?>" required>
                    <label>
                        <input type="checkbox" name="answers[<?php echo $ans['id']; ?>][is_correct]" value="1" <?php if ($ans['is_correct'] == 1) echo 'checked'; ?>> Correct
                    </label>
                </div>
                <?php
            }
        } else {
            echo "Aucune réponse trouvée pour ce quiz."; // Affiche si aucune réponse trouvée
        }

    } else {
        // Gérer le cas où le quiz n'est pas trouvé (par exemple, rediriger ou afficher un message d'erreur)
        echo "Quiz not found.";
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Quiz</title>
    <!-- Inclure Bootstrap CSS pour la mise en forme -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container">
    <h1>Edit Quiz</h1>
    <form action="update_quiz.php" method="post">
        <input type="hidden" name="quiz_id" value="<?php echo $quiz_id; ?>">
        <div class="form-group">
            <label for="title">Title</label>
            <input type="text" name="title" id="title" class="form-control" value="<?php echo htmlspecialchars($quiz_title); ?>" required>
        </div>
        <div class="form-group">
            <label for="description">Description</label>
            <textarea name="description" id="description" class="form-control" required><?php echo htmlspecialchars($quiz_description); ?></textarea>
        </div>
        <!-- Afficher les réponses existantes -->
        <?php foreach ($answers as $ans) { ?>
            <div class="form-group">
                <input type="hidden" name="answers[<?php echo $ans['id']; ?>][answer_id]" value="<?php echo $ans['id']; ?>">
                <input type="text" name="answers[<?php echo $ans['id']; ?>][answer_text]" class="form-control" value="<?php echo htmlspecialchars($ans['answer']); ?>" required>
                <label>
                    <input type="checkbox" name="answers[<?php echo $ans['id']; ?>][is_correct]" value="1" <?php if ($ans['is_correct'] == 1) echo 'checked'; ?>> Correct
                </label>
            </div>
        <?php } ?>

        <button type="submit" class="btn btn-primary mt-2">Update Quiz</button>
    </form>
</div>

<!-- Inclure Bootstrap JS pour les fonctionnalités supplémentaires (facultatif) -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
