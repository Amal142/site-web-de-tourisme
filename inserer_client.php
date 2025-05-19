<?php
require("./config.php");
require("./client.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Validation des données
    $nom = filter_input(INPUT_POST, 'nom', FILTER_SANITIZE_STRING);
    $prenom = filter_input(INPUT_POST, 'prenom', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $telephone = filter_input(INPUT_POST, 'telephone', FILTER_SANITIZE_STRING);
    $adresse = filter_input(INPUT_POST, 'adresse', FILTER_SANITIZE_STRING);
    $date_naissance = $_POST['date_naissance'];
    $hotel = filter_input(INPUT_POST, 'hotel', FILTER_SANITIZE_STRING);
    $typechambre = $_POST['typechambre'];

    if (!$email) {
        die("❌ Email invalide.");
    }

    try {
        $sql = "INSERT INTO clients (nom, prenom, email, telephone, adresse, date_naissance, hotel, typechambre)
                VALUES (:nom, :prenom, :email, :telephone, :adresse, :date_naissance, :hotel, :typechambre)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':nom' => $nom,
            ':prenom' => $prenom,
            ':email' => $email,
            ':telephone' => $telephone,
            ':adresse' => $adresse,
            ':date_naissance' => $date_naissance,
            ':hotel' => $hotel,
            ':typechambre' => $typechambre
        ]);
        
        // Vérifier si l'insertion a réussi
        if ($stmt->rowCount() > 0) {
            echo "✅ Client enregistré avec succès. ID: " . $pdo->lastInsertId();
        } else {
            echo "❌ Aucun client n'a été enregistré.";
        }
    } catch (PDOException $e) {
        echo "❌ Erreur lors de l'enregistrement : " . $e->getMessage();
        // Pour débogage, vous pouvez aussi logger l'erreur
        error_log("Erreur PDO: " . $e->getMessage());
    }
} else {
    echo "❌ Requête invalide.";
}
?>