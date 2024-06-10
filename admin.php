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

    foreach ($_POST['questions'] as $question) {
        $stmt = $pdo->prepare('INSERT INTO questions (quiz_id, question_text) VALUES (?, ?)');
        $stmt->execute([$quiz_id, $question['text']]);

        $question_id = $pdo->lastInsertId();

        foreach ($question['answers'] as $answer) {
            $stmt = $pdo->prepare('INSERT INTO answers (question_id, answer_text, is_correct) VALUES (?, ?, ?)');
            $stmt->execute([$question_id, $answer['text'], $answer['is_correct']]);
        }
    }

    header('Location: admin.php');
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Create Quiz</title>
</head>
<body>
    <h1>Create Quiz</h1>
    <form method="post" action="admin.php">
        <label>Title</label>
        <input type="text" name="title">
        <label>Description</label>
        <textarea name="description"></textarea>

        <div id="questions">
            <h2>Questions</h2>
            <!-- JavaScript to dynamically add questions and answers -->
        </div>

        <button type="button" onclick="addQuestion()">Add Question</button>
        <button type="submit">Create Quiz</button>
    </form>

    <script>
        function addQuestion() {
            var questionsDiv = document.getElementById('questions');
            var questionDiv = document.createElement('div');

            questionDiv.innerHTML = `
                <label>Question</label>
                <input type="text" name="questions[][text]">
                <div class="answers">
                    <h3>Answers</h3>
                </div>
                <button type="button" onclick="addAnswer(this)">Add Answer</button>
            `;

            questionsDiv.appendChild(questionDiv);
        }

        function addAnswer(button) {
            var answersDiv = button.previousElementSibling;
            var answerDiv = document.createElement('div');

            answerDiv.innerHTML = `
                <input type="text" name="questions[${questionsDiv.children.length - 1}][answers][][text]">
                <label>Correct</label>
                <input type="checkbox" name="questions[${questionsDiv.children.length - 1}][answers][][is_correct]">
            `;

            answersDiv.appendChild(answerDiv);
        }
    </script>
</body>
</html>
