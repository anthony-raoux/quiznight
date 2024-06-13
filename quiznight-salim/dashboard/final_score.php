<?php
session_start();
include_once 'header.php';
$page = 'home';
include_once '../db.php'; // Inclure le fichier de connexion à la base de données

$total_questions = $_SESSION['total_questions'];
$correct_answers = $_SESSION['correct_answers'];
?>

<main class="content">
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-12 col-lg-12 col-xxl-12 d-flex">
                <div class="card flex-fill">
                    <div class="card-header">
                        <h1 class="text-center">Score Final</h1>
                        <div class="alert alert-success" role="alert">
                            <p class="mb-4 text-center">Vous avez bien répondu à <?php echo $correct_answers; ?> questions sur <?php echo $total_questions; ?>.</p>
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
    </div>
</main>

<?php
include_once 'footer.php';
?>
