<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Quiz</title>
    <!-- Add Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
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
            </ul>
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container mt-4">
        <h1>Create Quiz</h1>
        <form method="post" action="admin.php">
            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" class="form-control" name="title" id="title" required>
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea class="form-control" name="description" id="description" rows="3" required></textarea>
            </div>
            <div id="questions">
                <h2>Questions</h2>
                <div class="question">
                    <div class="form-group">
                        <label>Question</label>
                        <input type="text" class="form-control" name="questions[][text]">
                    </div>
                    <div class="form-group">
                        <label>Answers</label>
                        <input type="text" class="form-control" name="questions[][answers][]">
                    </div>
                    <button type="button" class="btn btn-primary" onclick="addAnswer(this)">Add Answer</button>
                </div>
            </div>

            <button type="button" class="btn btn-primary mt-3" onclick="addQuestion()">Add Question</button>
            <button type="submit" class="btn btn-success mt-3">Create Quiz</button>
        </form>
    </div>

    <script>
        function addQuestion() {
            var questionsDiv = document.getElementById('questions');
            var questionDiv = document.createElement('div');
            questionDiv.classList.add('question', 'mt-4');

            questionDiv.innerHTML = `
                <h2>Question</h2>
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
    answerInput.className = 'form-control';
    answerInput.name = 'questions[][answers][]';
    answersDiv.appendChild(answerInput);
}

    </script>

    <!-- Add Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>


   <!-- Ajouter bootstrap admin et refaire create quiz array -->