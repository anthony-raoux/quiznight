<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$quiz_id = $_GET['id'];
$stmt = $pdo->prepare('SELECT * FROM quizzes WHERE id = ?');
$stmt->execute([$quiz_id]);
$quiz = $stmt->fetch();

if (!$quiz || $quiz['created_by'] != $_SESSION['user_id']) {
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];

    $stmt = $pdo->prepare('UPDATE quizzes SET title = ?, description = ? WHERE id = ?');
    $stmt->execute([$title, $description, $quiz_id]);

    $stmt = $pdo->prepare('DELETE FROM questions WHERE quiz_id = ?');
    $stmt->execute([$quiz_id]);

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

    header('Location: index.php');
    exit;
}

$stmt = $pdo->prepare('SELECT * FROM questions WHERE quiz_id = ?');
$stmt->execute([$quiz_id]);
$questions = $stmt->fetchAll();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Quiz</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
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
        <h1>Edit Quiz</h1>
        <form method="post" action="edit_quiz.php?id=<?php echo $quiz_id; ?>">
            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" class="form-control" name="title" id="title" value="<?php echo htmlspecialchars($quiz['title']); ?>" required>
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea class="form-control" name="description" id="description" rows="3" required><?php echo htmlspecialchars($quiz['description']); ?></textarea>
            </div>
            <div id="questions">
                <h2>Questions</h2>
                <?php foreach ($questions as $question): ?>
                    <div class="question">
                        <div class="form-group">
                            <label>Question</label>
                            <input type="text" class="form-control" name="questions[][text]" value="<?php echo htmlspecialchars($question['question_text']); ?>">
                        </div>
                        <div class="form-group">
                            <label>Answers</label>
                            <?php
                            $stmt = $pdo->prepare('SELECT * FROM answers WHERE question_id = ?');
                            $stmt->execute([$question['id']]);
                            $answers = $stmt->fetchAll();
                            ?>
                            <?php foreach ($answers as $answer): ?>
                                <input type="text" class="form-control" name="questions[][answers][]" value="<?php echo htmlspecialchars($answer['answer_text']); ?>">
                            <?php endforeach; ?>
                        </div>
                        <button type="button" class="btn btn-primary" onclick="addAnswer(this)">Add Answer</button>
                    </div>
                <?php endforeach; ?>
            </div>

            <button type="button" class="btn btn-primary mt-3" onclick="addQuestion()">Add Question</button>
            <button type="submit" class="btn btn-success mt-3">Update Quiz</button>
        </form>
    </div>

    <script>
        function addQuestion() {
            var questionsDiv = document.getElementById('questions');
            var questionDiv = document.createElement('div');
            questionDiv.classList.add('question', 'mt-4');

            questionDiv.innerHTML = `
                <div class="form-group">
                    <label>Question</label>
                    <input type="text" class="form-control" name="questions[][text]">
                </div>
                <div class="form-group">
                    <label>Answers</label>
                    <input type="text" class="form-control" name="questions[][answers][]">
                </div>
                <button type="button" class="btn btn-primary" onclick="addAnswer(this)">Add Answer</button>
            `;

            questionsDiv.appendChild(questionDiv);
        }

        function addAnswer(button) {
            var questionDiv = button.parentElement;
            var answersDiv = questionDiv.querySelector('.form-group:last-child');
            var answerInput = document.createElement('input');
            answerInput.type = 'text';
            answerInput.className = 'form-control mt-2';
            answerInput.name = 'questions[][answers][]';
            answersDiv.appendChild(answerInput);
        }
    </script>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
