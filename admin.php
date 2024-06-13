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

<?php include 'navbar.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin</title>
</head>
<body>
    <div class="container">
        <h1>Create Quiz</h1>
        <form action="admin.php" method="post">
            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" name="title" id="title" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea name="description" id="description" class="form-control" required></textarea>
            </div>
            <div id="questions">
                <div class="form-group">
                    <label>Question</label>
                    <input type="text" name="questions[0][question]" class="form-control" placeholder="Question" required>
                    <div class="answers mt-2">
                        <div class="form-group">
                            <input type="text" name="questions[0][answers][]" class="form-control" placeholder="Answer 1" required>
                            <input type="radio" name="questions[0][correct_answer]" value="0" required> Correct
                        </div>
                        <div class="form-group">
                            <input type="text" name="questions[0][answers][]" class="form-control" placeholder="Answer 2" required>
                            <input type="radio" name="questions[0][correct_answer]" value="1" required> Correct
                        </div>
                        <div class="form-group">
                            <input type="text" name="questions[0][answers][]" class="form-control" placeholder="Answer 3" required>
                            <input type="radio" name="questions[0][correct_answer]" value="2" required> Correct
                        </div>
                        <div class="form-group">
                            <input type="text" name="questions[0][answers][]" class="form-control" placeholder="Answer 4" required>
                            <input type="radio" name="questions[0][correct_answer]" value="3" required> Correct
                        </div>
                    </div>
                  
                </div>
            </div>
            <button type="button" class="btn btn-secondary" onclick="addQuestion()">Add Question</button>
            <button type="submit" class="btn btn-primary mt-2">Create Quiz</button>
        </form>

        <h1 class="mt-5">Existing Quizzes</h1>
        <?php while ($row = $quizzes->fetch(PDO::FETCH_ASSOC)) { ?>
    <li class="list-group-item">
        <?php echo $row['title']; ?> - <?php echo $row['description']; ?>
        <div class="btn-group float-right" role="group">
            <a href="edit_quiz.php?id=<?php echo $row['id']; ?>" class="btn btn-primary btn-sm">Edit</a>
            <form action="delete_quiz.php" method="post" style="display: inline;">
                <input type="hidden" name="quiz_id" value="<?php echo $row['id']; ?>">
                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this quiz?')">Delete</button>
            </form>
        </div>
    </li>
<?php } ?>



    <script>
        function addQuestion() {
            const questions = document.getElementById('questions');
            const count = questions.children.length;
            const div = document.createElement('div');
            div.classList.add('form-group');
            div.innerHTML = `<label>Question</label>
                             <input type="text" name="questions[${count}][question]" class="form-control" placeholder="Question" required>
                             <div class="answers mt-2">
                                 <div class="form-group">
                                     <input type="text" name="questions[${count}][answers][]" class="form-control" placeholder="Answer 1" required>
                                     <input type="radio" name="questions[${count}][correct_answer]" value="0" required> Correct
                                 </div>
                                 <div class="form-group">
                                     <input type="text" name="questions[${count}][answers][]" class="form-control" placeholder="Answer 2" required>
                                     <input type="radio" name="questions[${count}][correct_answer]" value="1" required> Correct
                                 </div>
                                 <div class="form-group">
                                     <input type="text" name="questions[${count}][answers][]" class="form-control" placeholder="Answer 3" required>
                                     <input type="radio" name="questions[${count}][correct_answer]" value="2" required> Correct
                                 </div>
                                   <div class="form-group">
                                     <input type="text" name="questions[${count}][answers][]" class="form-control" placeholder="Answer 4" required>
                                     <input type="radio" name="questions[${count}][correct_answer]" value="3" required> Correct
                                 </div>
                             </div>
                             <button type="button" class="btn btn-secondary></button>`;
            questions.appendChild(div);
        }

     
    </script>

    <?php include 'footer.php'; ?>
</body>
</html>
