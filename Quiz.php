<?php
class Quiz {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function createQuiz($title, $created_by) {
        $stmt = $this->pdo->prepare("INSERT INTO quiz (title, created_by) VALUES (:title, :created_by)");
        $stmt->execute(['title' => $title, 'created_by' => $created_by]);
    }

    public function getAllQuizzes() {
        $stmt = $this->pdo->query("SELECT * FROM quiz");
        return $stmt->fetchAll();
    }
}
?>
