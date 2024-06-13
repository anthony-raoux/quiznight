<?php

require_once 'Database.php';

class Answer {
    private $conn;
    private $table_name = "answers";

    public $id;
    public $question_id;
    public $answer;
    public $is_correct;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAnswersByQuestion($question_id)
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE question_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $question_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    

    public function getCorrectAnswer($question_id)
    {
        $query = "SELECT id FROM " . $this->table_name . " WHERE question_id = ? AND is_correct = 1 LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $question_id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['id'] ?? null;
    }
    
    public function getAnswers($question_id) {
        $query = "SELECT id, answer, is_correct FROM answers WHERE question_id = :question_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':question_id', $question_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " SET question_id=:question_id, answer=:answer, is_correct=:is_correct";

        $stmt = $this->conn->prepare($query);

        $this->question_id = htmlspecialchars(strip_tags($this->question_id));
        $this->answer = htmlspecialchars(strip_tags($this->answer));
        $this->is_correct = htmlspecialchars(strip_tags($this->is_correct));

        $stmt->bindParam(":question_id", $this->question_id);
        $stmt->bindParam(":answer", $this->answer);
        $stmt->bindParam(":is_correct", $this->is_correct);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    public function readByQuestionId($question_id) {
        $query = "SELECT id, question_id, answer, is_correct FROM " . $this->table_name . " WHERE question_id = ?";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $question_id);
        $stmt->execute();

        return $stmt;
    }
}
