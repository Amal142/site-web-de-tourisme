<?php
require("./config.php"); // Ce fichier doit contenir la connexion PDO à la base
require("./inscription.php"); // Ta classe (optionnel si tu veux structurer)

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Sécurisation et validation des champs
    $nom = filter_input(INPUT_POST, 'nom', FILTER_SANITIZE_STRING);
    $prenom = filter_input(INPUT_POST, 'prenom', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $passwords = $_POST['passwords']; // sera hashé ensuite
    $tel = filter_input(INPUT_POST, 'tel', FILTER_SANITIZE_STRING);


    try {
        // Requête SQL d'insertion
        $sql = "INSERT INTO inscription (nom, prenom, email, passwords, tel)
                VALUES (:nom, :prenom, :email, :passwords, :tel)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':nom' => $nom,
            ':prenom' => $prenom,
            ':email' => $email,
            ':passwords' => password_hash($passwords, PASSWORD_DEFAULT),
            ':tel' => $tel
        ]);

        if ($stmt->rowCount() > 0) {
            echo "✅ Utilisateur inscrit avec succès. ID: " . $pdo->lastInsertId();
        } else {
            echo "❌ Échec de l'inscription.";
        }
    } catch (PDOException $e) {
        echo "❌ Erreur lors de l'inscription : " . $e->getMessage();
        error_log("Erreur PDO : " . $e->getMessage());
    }
} else {
    echo "❌ Requête invalide.";
}
?>
