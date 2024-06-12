<?php

require_once 'Database.php';
require_once 'Answer.php';

class Question {
    private $conn;
    private $table_name = "questions";

    public $id;
    public $quiz_id;
    public $question;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getQuestionsByQuiz($quiz_id)
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE quiz_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $quiz_id);
        $stmt->execute();
        return $stmt;
    }
    
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " SET quiz_id=:quiz_id, question=:question";

        $stmt = $this->conn->prepare($query);

        $this->quiz_id = htmlspecialchars(strip_tags($this->quiz_id));
        $this->question = htmlspecialchars(strip_tags($this->question));

        $stmt->bindParam(":quiz_id", $this->quiz_id);
        $stmt->bindParam(":question", $this->question);

        if ($stmt->execute()) {
            return $this->conn->lastInsertId();
        }

        return false;
    }

    public function readByQuizId($quiz_id) {
        $query = "SELECT id, quiz_id, question FROM " . $this->table_name . " WHERE quiz_id = ?";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $quiz_id);
        $stmt->execute();

        return $stmt;
    }
}
