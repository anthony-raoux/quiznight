<?php

require_once 'Database.php';

class Admin {
    private $conn;
    private $table_name = "admins";

    public $id;
    public $username;
    public $password;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function login() {
        $query = "SELECT id, username, password FROM " . $this->table_name . " WHERE username = ? LIMIT 0,1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->username);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if($row && password_verify($this->password, $row['password'])) {
            $this->id = $row['id'];
            $this->username = $row['username'];
            return true;
        }

        return false;
    }

    public function register() {
        $query = "INSERT INTO " . $this->table_name . " SET username=:username, password=:password";

        $stmt = $this->conn->prepare($query);

        $this->username = htmlspecialchars(strip_tags($this->username));
        $this->password = password_hash($this->password, PASSWORD_BCRYPT);

        $stmt->bindParam(":username", $this->username);
        $stmt->bindParam(":password", $this->password);

        if($stmt->execute()) {
            return true;
        }

        return false;
    }

    public function getAdminById() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $this->username = $row['username'];
            // Ajouter d'autres attributs selon votre besoin
            return true;
        }
        
        return false;
    }

    public function updatePassword($new_password) {
        // Hasher le nouveau mot de passe
        $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);

        // Préparer la requête SQL pour mettre à jour le mot de passe
        $query = "UPDATE " . $this->table_name . " SET password = :password WHERE id = :id";
        $stmt = $this->conn->prepare($query);

        // Liaison des paramètres
        $stmt->bindParam(':password', $hashed_password);
        $stmt->bindParam(':id', $this->id);

        // Exécution de la requête
        if ($stmt->execute()) {
            return true;
        } else {
            // En cas d'erreur, afficher l'erreur SQL pour le débogage
            print_r($stmt->errorInfo());
            return false;
        }
    }
    
    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }
    
}
