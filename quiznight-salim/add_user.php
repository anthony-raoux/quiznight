<?php
require 'db.php';
require 'classes/User.php';

$user = new User($pdo);
$user->createUser('admin', 'password'); // Remplacez 'admin' et 'password' par les valeurs souhaitées
echo "Utilisateur créé avec succès.";
?>
