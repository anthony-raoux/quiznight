<?php
class Question {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function addQuestion($quiz_id, $question_text) {
        $stmt = $this->pdo->prepare("INSERT INTO questions (quiz_id, question_text) VALUES (:quiz_id, :question_text)");
        $stmt->execute(['quiz_id' => $quiz_id, 'question_text' => $question_text]);
    }

    public function getQuestionsByQuiz($quiz_id) {
        $stmt = $this->pdo->prepare("SELECT * FROM questions WHERE quiz_id = :quiz_id");
        $stmt->execute(['quiz_id' => $quiz_id]);
        return $stmt->fetchAll();
    }
}
?>
