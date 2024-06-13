<?php
session_start();

require_once __DIR__ . '/classes/Database.php';
require_once __DIR__ . '/classes/Quiz.php';

$database = new Database();
$db = $database->getConnection();

$quiz = new Quiz($db);

$quizzes = $quiz->readAll();
?>

<?php include 'navbar.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Quizzes</title>
</head>
<body>
    <h1>Quizzes</h1>
    <ul>
        <?php while ($row = $quizzes->fetch(PDO::FETCH_ASSOC)) : ?>
            <li>
                <a href="quiz.php?quiz_id=<?php echo $row['id']; ?>"><?php echo $row['title']; ?></a>
            </li>
        <?php endwhile; ?>
    </ul>
</body>
</html>
