<?php
session_start();
$page = 'home';
include_once 'header.php';
include_once '../db.php'; // Inclure le fichier de connexion à la base de données

// Initialiser le jeu
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['start_game'])) {
    $_SESSION['current_question'] = 0;
    $_SESSION['correct_answers'] = 0;
    $_SESSION['total_questions'] = 6; // Nombre de questions
    $_SESSION['answers'] = [];
}

// Récupérer la question actuelle
if (isset($_SESSION['current_question'])) {
    $current_question_index = $_SESSION['current_question'];

    $question_sql = "SELECT * FROM questions LIMIT 1 OFFSET $current_question_index";
    $question_stmt = $pdo->query($question_sql);
    $question = $question_stmt->fetch();

    $answers_sql = "SELECT * FROM answers WHERE question_id = ?";
    $answers_stmt = $pdo->prepare($answers_sql);
    $answers_stmt->execute([$question['id']]);
    $answers = $answers_stmt->fetchAll();
}
?>

<main class="content">
    <div class="container-fluid p-0">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1 class="h3 mb-3">Bonjour <strong><?php echo htmlspecialchars($username); ?></strong> 👋</h1>
        </div>
        
        <?php if (!isset($_SESSION['current_question'])): ?>
            <!-- Panneau de début -->
            <div class="row pannelDeDebut">
                <div class="col-12 col-lg-12 col-xxl-12 d-flex">
                    <div class="card flex-fill">
                        <div class="card-header">
                            <h1 class="text-center">Commencez à jouer</h1>
                            <p class="mb-4 text-center">Testez vos connaissances en répondant à nos questions.</p>
                            <div class="d-flex align-items-center justify-content-center">
                                <form method="post">
                                    <button class="btn btn-dark w-100 fw-bolder my-3" name="start_game" type="submit">
                                        Commencer à jouer
                                        <i class="bi bi-arrow-up-right mx-3"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php else: ?>
        <!-- Panneau de jeu -->
        <div class="row pannelDeJeux">
                <div class="col-12 col-lg-12 col-xxl-12 d-flex">
                    <div class="card flex-fill">
                        <div class="card-header">
                            <div class="container">
                                <form method="post" action="submit_answer.php">
                                    <div class="mt-2 mb-4">
                                        <p class="text-center gameScore">Question <?php echo $current_question_index + 1; ?> / <?php echo $_SESSION['total_questions']; ?></p>
                                        <div class="progress gameScoreBar border border-dark mb-2 w-100 border-1" role="progressbar" aria-label="Example 20px high" aria-valuenow="<?php echo (100 / $_SESSION['total_questions']) * $current_question_index; ?>" aria-valuemin="0" aria-valuemax="100" style="height: 20px">
                                            <div class="progress-bar" style="width: <?php echo (100 / $_SESSION['total_questions']) * $current_question_index; ?>%"></div>
                                        </div>
                                    </div>
                                
                                    <h3 class="text-center mb-5"><?php echo htmlspecialchars($question['question_text']); ?></h3>
                                    <hr>
                                    <div class="mt-3">
                                        <?php foreach ($answers as $answer): ?>
                                            <button class="btn btn-outline-dark w-100 mb-3 choiceOfGames" name="answer" value="<?php echo $answer['id']; ?>" type="radio">
                                                <?php echo htmlspecialchars($answer['answer_text']); ?>
                                            </button>
                                        <?php endforeach; ?>
                                        
                                        <div class="btnEndChoice d-flex gap-3">
                                            <button class="btn btn-outline-dark w-50 fw-bolder my-3 w-100" name="view_answer" type="submit">
                                                Voir les réponses
                                                <i class="bi bi-back"></i>
                                            </button>
                                            <button class="btn btn-dark w-50 fw-bolder my-3 w-100" name="next_question" type="submit">
                                                Partie suivante
                                                <i class="bi bi-arrow-right text-light"></i>
                                            </button>
                                        </div>
                                    </div>
                                 </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['game_over'])): ?>
            <!-- Panneau de score final -->
            <div class="row pannelDeDebut pannelScore">
                <div class="col-12 col-lg-12 col-xxl-12 d-flex">
                    <div class="card flex-fill">
                        <div class="card-header">
                            <h1 class="text-center">Score Final</h1>
                            <div class="alert alert-success" role="alert">
                                <p class="mb-4 text-center">Vous avez bien répondu à <?php echo $_SESSION['correct_answers']; ?> questions sur <?php echo $_SESSION['total_questions']; ?>.</p>
                            </div>
                            <div class="d-flex align-items-center justify-content-center">
                                <form method="post" action="restart_game.php">
                                    <button class="btn btn-dark w-50 fw-bolder my-3 py-2" name="restart_game" type="submit">
                                        Recommencez une nouvelle partie
                                        <i class="bi bi-arrow-clockwise mx-3"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>



    </div>
</main>

<!-- Modal -->
<div class="modal fade" id="viewAnswer" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Modal title</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        ...
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>
    
<?php
include_once 'footer.php';
?>

<script>
function showQuiz() {
    document.getElementById('quiz-container').style.display = 'block';
}
</script>
