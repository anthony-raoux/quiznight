<?php
class Answer {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function addAnswer($question_id, $answer_text, $is_correct) {
        $stmt = $this->pdo->prepare("INSERT INTO answers (question_id, answer_text, is_correct) VALUES (:question_id, :answer_text, :is_correct)");
        $stmt->execute(['question_id' => $question_id, 'answer_text' => $answer_text, 'is_correct' => $is_correct]);
    }

    public function getAnswersByQuestion($question_id) {
        $stmt = $this->pdo->prepare("SELECT * FROM answers WHERE question_id = :question_id");
        $stmt->execute(['question_id' => $question_id]);
        return $stmt->fetchAll();
    }
}
?>
