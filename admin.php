<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

require_once __DIR__ . '/classes/Database.php';
require_once __DIR__ . '/classes/Quiz.php';
require_once __DIR__ . '/classes/Question.php';
require_once __DIR__ . '/classes/Answer.php';

$database = new Database();
$db = $database->getConnection();

$quiz = new Quiz($db);
$question = new Question($db);
$answer = new Answer($db);

if ($_POST) {
    $quiz->title = $_POST['title'];
    $quiz->description = $_POST['description'];
    $quiz->created_by = $_SESSION['admin_id'];

    if ($quiz->create()) {
        $quiz_id = $db->lastInsertId();

        foreach ($_POST['questions'] as $q) {
            $question->quiz_id = $quiz_id;
            $question->question = $q['question'];
            $question_id = $question->create();

            foreach ($q['answers'] as $key => $answer_text) {
                $is_correct = ($key == $q['correct_answer']) ? 1 : 0;
                $answer->question_id = $question_id;
                $answer->answer = $answer_text;
                $answer->is_correct = $is_correct;
                $answer->create();
            }
        }
    }
}

$quizzes = $quiz->readAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin</title>
</head>
<body>
    <h1>Create Quiz</h1>
    <form action="admin.php" method="post">
        <div>
            <label for="title">Title</label>
            <input type="text" name="title" id="title" required>
        </div>
        <div>
            <label for="description">Description</label>
            <textarea name="description" id="description" required></textarea>
        </div>
        <div>
            <label for="questions">Questions</label>
            <div id="questions">
                <div class="question">
                    <input type="text" name="questions[0][question]" placeholder="Question" required>
                    <div class="answers">
                        <input type="text" name="questions[0][answers][]" placeholder="Answer 1" required>
                        <input type="radio" name="questions[0][correct_answer]" value="0" required> Correct<br>
                        <input type="text" name="questions[0][answers][]" placeholder="Answer 2" required>
                        <input type="radio" name="questions[0][correct_answer]" value="1" required> Correct<br>
                        <input type="text" name="questions[0][answers][]" placeholder="Answer 3" required>
                        <input type="radio" name="questions[0][correct_answer]" value="2" required> Correct
                    </div>
                    <button type="button" onclick="addAnswer(this)">Add Answer</button>
                </div>
            </div>
            <button type="button" onclick="addQuestion()">Add Question</button>
        </div>
        <button type="submit">Create Quiz</button>
    </form>

    <h1>Existing Quizzes</h1>
    <ul>
        <?php while ($row = $quizzes->fetch(PDO::FETCH_ASSOC)) { ?>
            <li><?php echo $row['title']; ?> - <?php echo $row['description']; ?></li>
        <?php } ?>
    </ul>

    <script>
        function addQuestion() {
            const questions = document.getElementById('questions');
            const count = questions.children.length;
            const div = document.createElement('div');
            div.classList.add('question');
            div.innerHTML = `<input type="text" name="questions[${count}][question]" placeholder="Question" required>
                             <div class="answers">
                                 <input type="text" name="questions[${count}][answers][]" placeholder="Answer" required>
                                 <input type="radio" name="questions[${count}][correct_answer]" value="0" required> Correct<br>
                                 <input type="text" name="questions[${count}][answers][]" placeholder="Answer" required>
                                 <input type="radio" name="questions[${count}][correct_answer]" value="1" required> Correct<br>
                                 <input type="text" name="questions[${count}][answers][]" placeholder="Answer" required>
                                 <input type="radio" name="questions[${count}][correct_answer]" value="2" required> Correct
                                 
                             </div>
                             <button type="button" onclick="addAnswer(this)">Add Answer</button>`;
            questions.appendChild(div);
        }

        function addAnswer(button) {
            const answers = button.previousElementSibling;
            const count = answers.children.length / 2; // divide by 2 because each answer has two elements (input + radio)
            const div = document.createElement('div');
            div.classList.add('answer');
            div.innerHTML = `<input type="text" name="answers[${count}][answer]" placeholder="Answer" required>
                             <input type="radio" name="questions[${count}][correct_answer]" value="${count}" required> Correct`;
            answers.appendChild(div);
        }
    </script>
</body>
</html>
