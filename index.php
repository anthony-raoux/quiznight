<?php
session_start();
include 'db.php';

$stmt = $pdo->query('SELECT * FROM quizzes');
$quizzes = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz List</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="custom.css" rel="stylesheet"> 
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="#">Quiz App</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
                </li>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="admin.php">Create Quiz</a>
                    </li>
                <?php endif; ?>
            </ul>
            <ul class="navbar-nav ml-auto">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">Logout</a>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="login.php">Login</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>

    <div class="container mt-4">
        <h1>Available Quizzes</h1>
        <ul class="list-group">
            <?php foreach ($quizzes as $quiz): ?>
                <li class="list-group-item">
                    <span class="quizItem" data-id="<?php echo $quiz['id']; ?>" style="cursor: pointer;"><?php echo htmlspecialchars($quiz['title']); ?></span>
                    <?php if (isset($_SESSION['user_id']) && $quiz['created_by'] == $_SESSION['user_id']): ?>
                        <a href="edit_quiz.php?id=<?php echo $quiz['id']; ?>" class="btn btn-secondary btn-sm ml-2">Edit</a>
                        <a href="delete_quiz.php?id=<?php echo $quiz['id']; ?>" class="btn btn-danger btn-sm ml-2">Delete</a>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ul>
        <div id="quizDetails" class="mt-4">
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        var quizItems = document.querySelectorAll('.quizItem');

        quizItems.forEach(function (item) {
            item.addEventListener('click', function () {
                var quizId = this.getAttribute('data-id');
                fetchQuizDetails(quizId);
            });
        });

        function fetchQuizDetails(quizId) {
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function () {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        var quizDetails = JSON.parse(xhr.responseText);
                        displayQuizDetails(quizDetails);
                    } else {
                        console.error('Failed to fetch quiz details');
                    }
                }
            };
            xhr.open('GET', 'get_quiz_details.php?id=' + quizId, true);
            xhr.send();
        }

        function displayQuizDetails(quizDetails) {
            var quizDetailsContainer = document.getElementById('quizDetails');
            quizDetailsContainer.innerHTML = '';

            var title = document.createElement('h3');
            title.textContent = quizDetails.title;
            quizDetailsContainer.appendChild(title);

            var description = document.createElement('p');
            description.textContent = quizDetails.description;
            quizDetailsContainer.appendChild(description);

            // Afficher les questions et réponses associées
            var questions = quizDetails.questions;
            questions.forEach(function (question, index) {
                var questionHeader = document.createElement('h4');
                questionHeader.textContent = 'Question ' + (index + 1) + ': ' + question.question_text;
                quizDetailsContainer.appendChild(questionHeader);

                var answersList = document.createElement('ul');
                question.answers.forEach(function (answer) {
                    var answerItem = document.createElement('li');
                    answerItem.textContent = answer.answer_text + (answer.is_correct ? ' (Correct)' : '');
                    answersList.appendChild(answerItem);
                });
                quizDetailsContainer.appendChild(answersList);
            });
        }
    });
</script>


    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

