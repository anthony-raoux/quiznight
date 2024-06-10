<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$stmt = $pdo->query('SELECT * FROM quizzes');
$quizzes = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <style>
        .quizItem {
            cursor: pointer;
        }

        .loading {
            cursor: wait;
        }
    </style>
</head>
<body>
    <h1>Welcome to the Dashboard</h1>
    <h2>Available Quizzes</h2>
    <ul id="quizList">
        <?php foreach ($quizzes as $quiz): ?>
            <li class="quizItem" data-id="<?php echo $quiz['id']; ?>"><?php echo htmlspecialchars($quiz['title']); ?></li>
        <?php endforeach; ?>
    </ul>

    <div id="quizDetails">
        <!-- Quiz details will be displayed here -->
    </div>

    <form action="logout.php" method="post">
        <button type="submit">Logout</button>
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var quizItems = document.querySelectorAll('.quizItem');

            quizItems.forEach(function (item) {
                item.addEventListener('click', function () {
                    var quizId = this.getAttribute('data-id');
                    this.classList.add('loading');
                    fetchQuizDetails(quizId, this);
                });
            });

            function fetchQuizDetails(quizId, element) {
                var xhr = new XMLHttpRequest();
                xhr.onreadystatechange = function () {
                    if (xhr.readyState === XMLHttpRequest.DONE) {
                        element.classList.remove('loading');
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

                var questionsList = document.createElement('ul');
                quizDetails.questions.forEach(function (question) {
                    var questionItem = document.createElement('li');
                    questionItem.textContent = question.question_text;

                    var answersList = document.createElement('ul');
                    question.answers.forEach(function (answer) {
                        var answerItem = document.createElement('li');
                        answerItem.textContent = answer.answer_text;
                        answersList.appendChild(answerItem);
                    });

                    questionItem.appendChild(answersList);
                    questionsList.appendChild(questionItem);
                });

                quizDetailsContainer.appendChild(questionsList);
            }
        });
    </script>
</body>
</html>
