<?php
require("./config.php"); // Connexion PDO
require("./contact.php"); // Classe Inscription

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupération et filtrage des données
    $nom = filter_input(INPUT_POST, 'nom', FILTER_SANITIZE_STRING);
    $prenom = filter_input(INPUT_POST, 'prenom', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Sécurisation
    $message = filter_input(INPUT_POST, 'message', FILTER_SANITIZE_STRING);

    // ✅ Création de l'objet Inscription
    $inscription = new Inscription(null, $nom, $prenom, $email, $password, $message);

    try {
        // Préparer la requête
        $stmt = $pdo->prepare("INSERT INTO inscription (nom, prenom, email, password, message) 
                               VALUES (:nom, :prenom, :email, :password, :message)");

        // Exécuter avec les getters
        $stmt->execute([
            ':nom' => $inscription->getNom(),
            ':prenom' => $inscription->getPrenom(),
            ':email' => $inscription->getEmail(),
            ':password' => $inscription->getPassword(),
            ':message' => $inscription->getMessage()
        ]);

        echo "✅ Inscription réussie ! ID : " . $pdo->lastInsertId();

    } catch (PDOException $e) {
        echo "❌ Erreur lors de l'enregistrement : " . $e->getMessage();
    }
}
?>
