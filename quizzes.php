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
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-w1QBRaD/smK2uWRzyD1O3uTps9pBBHiC4N9iwUb5Kr8KSkM+SOJwB3TrC6K06fxE" crossorigin="anonymous">
    <link rel="stylesheet" href="./custom.css">
</head>
<body>
<div class="container mt-4">
    <h1 class="mb-4">Quizzes</h1>
    <ul class="list-group">
        <?php while ($row = $quizzes->fetch(PDO::FETCH_ASSOC)) : ?>
            <li class="list-group-item">
                <a href="quiz.php?quiz_id=<?php echo $row['id']; ?>"><?php echo $row['title']; ?></a>
            </li>
        <?php endwhile; ?>
    </ul>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@1.16.1/dist/umd/popper.min.js" integrity="sha384-4a56PQvKdXfrjHLsXzdtH2kEZD1Eg4yZ2Gl6xH/mnIeRMJnlCU3Keh4sCQVW0lN4" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8sh+7Dk0yD95peD/Qlh/wIM+Lcaj8ORmeG7JFg" crossorigin="anonymous"></script>

</body>
</html>
