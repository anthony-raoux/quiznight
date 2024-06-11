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
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="#">Quiz App</a>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="index.php">Home</a>
                </li>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="create_quiz.php">Create Quiz</a>
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
        <ul id="quizList" class="list-group">
            <?php foreach ($quizzes as $quiz): ?>
                <li class="list-group-item quizItem" data-id="<?php echo $quiz['id']; ?>">
                    <?php echo htmlspecialchars($quiz['title']); ?>
                </li>
            <?php endforeach; ?>
        </ul>

        <div id="quizDetails" class="mt-4">
            <!-- Quiz details will be displayed here -->
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

                // Display questions and answers
                quizDetails.questions.forEach(function (question) {
                    var questionDiv = document.createElement('div');
                    var questionTitle = document.createElement('h4');
                    questionTitle.textContent = question.text;
                    questionDiv.appendChild(questionTitle);

                    question.answers.forEach(function (answer) {
                        var answerText = document.createElement('p');
                        answerText.textContent = answer.text;
                        questionDiv.appendChild(answerText);
                    });

                    quizDetailsContainer.appendChild(questionDiv);
                });

                // Add edit and delete buttons if user is logged in
                <?php if (isset($_SESSION['user_id'])): ?>
                    var editButton = document.createElement('button');
                    editButton.textContent = 'Edit Quiz';
                    editButton.classList.add('btn', 'btn-warning');
                    editButton.addEventListener('click', function () {
                        window.location.href = 'edit_quiz.php?id=' + quizDetails.id;
                    });
                    quizDetailsContainer.appendChild(editButton);

                    var deleteButton = document.createElement('button');
                    deleteButton.textContent = 'Delete Quiz';
                    deleteButton.classList.add('btn', 'btn-danger');
                    deleteButton.addEventListener('click', function () {
                        if (confirm('Are you sure you want to delete this quiz?')) {
                            window.location.href = 'delete_quiz.php?id=' + quizDetails.id;
                        }
                    });
                    quizDetailsContainer.appendChild(deleteButton);
                <?php endif; ?>
            }
        });
    </script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
