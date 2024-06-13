<?php
session_start();

// Inclure les fichiers requis
require_once __DIR__ . '/classes/Database.php';
require_once __DIR__ . '/classes/Quiz.php';
require_once __DIR__ . '/classes/Answer.php';

// Vérification si l'utilisateur est connecté en tant qu'admin
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

// Initialisation des variables et objets
$quiz_data = null;
$questions = [];

// Vérification de l'existence de l'ID du quiz
if (isset($_GET['id'])) {
    $quiz_id = $_GET['id'];

    // Instanciation de la classe Quiz et initialisation avec la connexion à la base de données
    $database = new Database();
    $db = $database->getConnection();
    $quiz = new Quiz($db);
    $answer = new Answer($db);

    // Récupération des détails du quiz à partir de son ID
    $quiz_data = $quiz->getQuizById($quiz_id);

    if ($quiz_data) {
        // Récupération des questions associées à ce quiz
        $questions = $quiz->getQuestions($quiz_id);

        // Traitement de la soumission du formulaire de modification de question
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Mise à jour des détails du quiz
            if (isset($_POST['quiz_id']) && isset($_POST['title']) && isset($_POST['description'])) {
                $quiz_id = $_POST['quiz_id'];
                $title = $_POST['title'];
                $description = $_POST['description'];

                if ($quiz->updateQuiz($quiz_id, $title, $description)) {
                    $success_message = "Quiz mis à jour avec succès.";
                } else {
                    $error_message = "Erreur lors de la mise à jour du quiz.";
                }
            }

            // Mise à jour des questions
            if (isset($_POST['question_id']) && isset($_POST['question_text'])) {
                $question_id = $_POST['question_id'];
                $question_text = $_POST['question_text'];

                if ($quiz->updateQuestion($question_id, $question_text)) {
                    $success_message = "Question mise à jour avec succès.";
                } else {
                    $error_message = "Erreur lors de la mise à jour de la question.";
                }
            }
        }

        // Fonction pour générer la liste des réponses sous forme d'HTML
        function generateAnswerList($answers) {
            $html = '<ul class="list-group">';
            foreach ($answers as $answer) {
                $html .= '<li class="list-group-item">' . htmlspecialchars($answer['answer']) . ' <a href="edit_answer.php?id=' . $answer['id'] . '" class="btn btn-sm btn-primary ml-2">Modifier</a></li>';
            }
            $html .= '</ul>';
            return $html;
        }
?>

<?php include 'navbar.php'; ?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier Quiz</title>
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
            <h5>Modifier le Quiz</h5>
        </div>
        <div class="card-body">
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?id=' . $quiz_id; ?>">
                <input type="hidden" name="quiz_id" value="<?php echo htmlspecialchars($quiz_data['id']); ?>">
                <div class="form-group">
                    <label for="title">Titre:</label>
                    <input type="text" class="form-control" id="title" name="title" value="<?php echo htmlspecialchars($quiz_data['title']); ?>">
                </div>
                <div class="form-group">
                    <label for="description">Description:</label>
                    <textarea class="form-control" id="description" name="description"><?php echo htmlspecialchars($quiz_data['description']); ?></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
            </form>
        </div>
    </div>
</div>

<?php foreach ($questions as $question): ?>
    <div class="container mt-4">
        <div class="card">
            <div class="card-header">
                <h5>Question : <?php echo htmlspecialchars($question['question']); ?></h5>
            </div>
            <div class="card-body">
                <?php echo generateAnswerList($answer->getAnswers($question['id'])); ?>
            </div>
            <div class="card-footer">
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?id=' . $quiz_id; ?>">
                    <input type="hidden" name="question_id" value="<?php echo htmlspecialchars($question['id']); ?>">
                    <div class="form-group">
                        <label for="question_text">Nouveau texte de la question:</label>
                        <input type="text" class="form-control" id="question_text" name="question_text" value="<?php echo htmlspecialchars($question['question']); ?>">
                    </div>
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                </form>
            </div>
        </div>
    </div>
<?php endforeach; ?>

<!-- Bootstrap JS and dependencies -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@1.16.1/dist/umd/popper.min.js" integrity="sha384-4a56PQvKdXfrjHLsXzdtH2kEZD1Eg4yZ2Gl6xH/mnIeRMJnlCU3Keh4sCQVW0lN4" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8sh+7Dk0yD95peD/Qlh/wIM+Lcaj8ORmeG7JFg" crossorigin="anonymous"></script>

</body>
</html>

<?php
    } else {
        echo "<div class='container mt-4'>Aucun quiz trouvé avec cet identifiant.</div>";
    }
} else {
    echo "<div class='container mt-4'>Identifiant de quiz non spécifié.</div>";
}
?>
