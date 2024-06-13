<!-- Page de rajout de quiz -->

<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require '../db.php';
require '../quiz.php';
require '../question.php';
require '../answer.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['quiz_title']) && isset($_POST['created_by']) && isset($_POST['questions'])) {
        $title = $_POST['quiz_title'];
        $created_by = $_POST['created_by'];

        // Ajouter le quiz
        $quiz->createQuiz($title, $created_by);
        $quiz_id = $pdo->lastInsertId();

        // Ajouter les questions et réponses
        foreach ($_POST['questions'] as $q) {
            if (isset($q['question_text']) && isset($q['answers'])) {
                $question_text = $q['question_text'];
                $question->addQuestion($quiz_id, $question_text);
                $question_id = $pdo->lastInsertId();

                foreach ($q['answers'] as $a) {
                    if (isset($a['answer_text']) && isset($a['is_correct'])) {
                        $answer_text = $a['answer_text'];
                        $is_correct = $a['is_correct'];
                        $answer->addAnswer($question_id, $answer_text, $is_correct);
                    }
                }
            }
        }
        echo "Quiz ajouté avec succès !";
    } else {
        echo "Des champs sont manquants dans le formulaire.";
    }
}

// -------------------------------------------------------------------------- 

$page = 'add_quiz'; // Page active
// Inclure le header une seule fois
    include_once 'header.php';
?>


<main class="content">
    <div class="container-fluid p-0">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1 class="h3 mb-3">Ajouter un nouveau Quiz</h1>
        </div>
        
        <div class="row">
            <div class="col-12 col-lg-12 col-xxl-12 d-flex">
                <div class="card flex-fill">
                    <div class="card-header">
                        <div class="container">
                            <form method="POST" action="add_quiz.php" id="add-quiz-form">
                                <div class="form-group">
                                    <label for="quiz-title">Titre du quiz :</label>
                                    <input type="text" class="form-control" id="quiz-title" name="quiz_title" required>
                                </div>
                                <div class="form-group">
                                    <label for="created-by">Créé par :</label>
                                    <input type="text" class="form-control" id="created-by" name="created_by" required>
                                </div>
                                <h2>Questions</h2>
                                <div id="questions-container">
                                    <div class="question-block">
                                        <div class="form-group">
                                            <label for="question-text">Question :</label>
                                            <input type="text" class="form-control" name="questions[0][question_text]" required>
                                        </div>
                                        <h3>Réponses</h3>
                                        <div class="form-group">
                                            <label for="answer-text">Réponse :</label>
                                            <input type="text" class="form-control" name="questions[0][answers][0][answer_text]" required>
                                            <label for="is-correct">Est-ce la bonne réponse ?</label>
                                            <select class="form-control" name="questions[0][answers][0][is_correct]" required>
                                                <option value="1">Oui</option>
                                                <option value="0">Non</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary mt-3">Ajouter le quiz</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
            

<?php
    // Inclure le footer une seule fois
    include_once 'footer.php';
?>
