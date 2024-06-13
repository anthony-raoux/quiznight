<?php
$host = 'localhost'; // ou l'adresse de votre serveur
$db = 'quiz_night'; // le nom de votre base de données
$user = 'root'; // votre nom d'utilisateur
$pass = ''; // votre mot de passe
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
    echo "<script>console.log('🟢 Connexion réussie à la base de données.');</script>";
} catch (\PDOException $e) {
    echo "<script>console.log('🔴 Erreur de connexion : " . addslashes($e->getMessage()) . "');</script>";
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}
