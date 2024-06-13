<?php

require_once 'Database.php';

class Quiz {
    private $conn;
    private $table_name = "quizzes";

    public $id;
    public $title;
    public $description;
    public $created_by;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAllQuizzes() {
        $query = "SELECT * FROM quizzes";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function delete($quiz_id) {
        $query = "DELETE FROM quizzes WHERE id = :quiz_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':quiz_id', $quiz_id, PDO::PARAM_INT);
        return $stmt->execute();
    }
    
    public function updateQuiz($quiz_id, $title, $description) {
        $query = "UPDATE quizzes SET title = :title, description = :description WHERE id = :quiz_id";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':quiz_id', $quiz_id);

        if ($stmt->execute()) {
            return true; // Succès de la mise à jour du quiz
        } else {
            return false; // Échec de la mise à jour du quiz
        }
    }

    public function getQuestions($quiz_id) {
        $query = "SELECT id, question FROM questions WHERE quiz_id = :quiz_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':quiz_id', $quiz_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateAnswer($answer_id, $answer_text) {
        $query = "UPDATE answers SET answer_text = :answer_text WHERE id = :answer_id";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':answer_text', $answer_text);
        $stmt->bindParam(':answer_id', $answer_id);

        if ($stmt->execute()) {
            return true; // Succès de la mise à jour de la réponse
        } else {
            return false; // Échec de la mise à jour de la réponse
        }
    }

// Méthode pour récupérer les réponses associées à une question d'un quiz
public function getAnswers($question_id) {
    $query = "SELECT id, answer, is_correct FROM answers WHERE question_id = :question_id";
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':question_id', $question_id);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC); // Retourner les résultats sous forme de tableau associatif
}

public function readOne($quiz_id) {
    $query = "SELECT * FROM " . $this->table_name . " WHERE id = ?";
    
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(1, $quiz_id);
    $stmt->execute();

    return $stmt->fetch(PDO::FETCH_ASSOC); // Retourner les données sous forme de tableau associatif
}


    public function getQuizById($quiz_id)
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $quiz_id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create() {
        $query = "INSERT INTO " . $this->table_name . " SET title=:title, description=:description, created_by=:created_by";

        $stmt = $this->conn->prepare($query);

        $this->title = htmlspecialchars(strip_tags($this->title));
        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->created_by = htmlspecialchars(strip_tags($this->created_by));

        $stmt->bindParam(":title", $this->title);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":created_by", $this->created_by);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    public function updateQuestion($question_id, $question_text) {
        $query = "UPDATE questions SET question = :question_text WHERE id = :question_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':question_text', $question_text);
        $stmt->bindParam(':question_id', $question_id, PDO::PARAM_INT);
        
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function readAll() {
        $query = "SELECT id, title, description, created_at FROM " . $this->table_name . " ORDER BY created_at DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt;
    }
}
