<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $created_by = $_SESSION['user_id'];

    $stmt = $pdo->prepare('INSERT INTO quizzes (title, description, created_by) VALUES (?, ?, ?)');
    $stmt->execute([$title, $description, $created_by]);

    $quiz_id = $pdo->lastInsertId();

    if (isset($_POST['question_text']) && is_array($_POST['question_text'])) {
        foreach ($_POST['question_text'] as $index => $questionText) {
            if (isset($_POST['answer_text'][$index])) {
                $answerText = $_POST['answer_text'][$index];

                $stmt = $pdo->prepare('INSERT INTO questions (quiz_id, question_text) VALUES (?, ?)');
                $stmt->execute([$quiz_id, $questionText]);
                $question_id = $pdo->lastInsertId();

                $stmt = $pdo->prepare('INSERT INTO answers (question_id, answer_text) VALUES (?, ?)');
                $stmt->execute([$question_id, $answerText]);
            }
        }
    }

    header('Location: admin.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Quiz</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="custom.css" rel="stylesheet"> 
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="index.php">Quiz App</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="index.php">Home <span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="admin.php">Create Quiz</a>
                </li>
            </ul>
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container mt-4">
        <h1>Create Quiz</h1>
        <form id="quizForm" method="post" action="admin.php">
            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" class="form-control" name="title" id="title" required>
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea class="form-control" name="description" id="description" rows="3" required></textarea>
            </div>
            <div id="questions">
                <div class="form-group">
                    <label for="question_text">Question</label>
                    <input type="text" class="form-control" name="question_text[]" required>
                </div>
                <div class="form-group">
                    <label for="answer_text">Answer</label>
                    <input type="text" class="form-control" name="answer_text[]" required>
                </div>
            </div>
            <button type="button" class="btn btn-primary mt-3" onclick="addQuestion()">Add Question</button>
            <button type="submit" class="btn btn-success mt-3">Create Quiz</button>
        </form>
    </div>

    <script>
        function addQuestion() {
            var questionsDiv = document.getElementById('questions');
            var questionDiv = document.createElement('div');
            questionDiv.classList.add('question', 'mt-4');

            questionDiv.innerHTML = `
                <div class="form-group">
                    <label for="question_text">Question</label>
                    <input type="text" class="form-control" name="question_text[]" required>
                </div>
                <div class="form-group">
                    <label for="answer_text">Answer</label>
                    <input type="text" class="form-control" name="answer_text[]" required>
                </div>
            `;

            questionsDiv.appendChild(questionDiv);
        }
    </script>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
