<?php
$host = "localhost";
$username = "root";   // ou ton nom d'utilisateur MySQL
$dbname = "tourisme"; // ✅ Nom exact de ta base de données
$password = "";       // mot de passe (souvent vide sous XAMPP)

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // echo "Connexion réussie à la base de données.";
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}
?>
