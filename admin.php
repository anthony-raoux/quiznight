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
        $question_text = $question['text'];

        if (!empty($question_text)) {
            $stmt = $pdo->prepare('INSERT INTO questions (quiz_id, question_text) VALUES (?, ?)');
            $stmt->execute([$quiz_id, $question_text]);
            $question_id = $pdo->lastInsertId();

            if (isset($question['answers']) && is_array($question['answers'])) {
                foreach ($question['answers'] as $answer) {
                    $answer_text = $answer;
                    if (!empty($answer_text)) {
                        $stmt = $pdo->prepare('INSERT INTO answers (question_id, answer_text) VALUES (?, ?)');
                        $stmt->execute([$question_id, $answer_text]);
                    }
                }
            }
        }
    }

    header('Location: admin.php');
    exit;
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
            <div class="question">
                <label>Question</label>
                <input type="text" name="questions[0][text]">
                <button type="button" onclick="addAnswer(this)">Add Answer</button>
                <div class="answers">
                    <div>
                        <input type="text" name="questions[0][answers][]">
                    </div>
                </div>
            </div>
        </div>

        <button type="button" onclick="addQuestion()">Add Question</button>
        <button type="submit">Create Quiz</button>
    </form>

    <script>
        let questionCount = 1;

        function addQuestion() {
            let questionsDiv = document.getElementById('questions');
            let questionDiv = document.createElement('div');
            questionDiv.classList.add('question');

            questionDiv.innerHTML = `
                <label>Question</label>
                <input type="text" name="questions[${questionCount}][text]">
                <button type="button" onclick="addAnswer(this)">Add Answer</button>
                <div class="answers">
                    <div>
                        <input type="text" name="questions[${questionCount}][answers][]">
                    </div>
                </div>
            `;

            questionsDiv.appendChild(questionDiv);
            questionCount++;
        }

        function addAnswer(button) {
            let questionDiv = button.parentElement;
            let answersDiv = questionDiv.querySelector('.answers');
            let questionIndex = Array.from(document.getElementsByClassName('question')).indexOf(questionDiv);
            let answerDiv = document.createElement('div');
            
            answerDiv.innerHTML = `
                <input type="text" name="questions[${questionIndex}][answers][]">
            `;

            answersDiv.appendChild(answerDiv);
        }
    </script>
</body>
</html>
