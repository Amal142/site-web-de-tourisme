<?php
require_once 'config.php';
require_once 'client.php';

session_start();

// Vérification CSRF
if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    die("❌ Erreur de sécurité");
}

try {
    $client = new Client();
    $client->setNom($_POST['nom']);
    $client->setPrenom($_POST['prenom']);
    $client->setEmail($_POST['email']);
    $client->setHotel($_POST['hotel']);

    $sql = "INSERT INTO clients (...)
            VALUES (:nom, :prenom, ...)";
    $stmt = $pdo->prepare($sql);
    
    if ($stmt->execute($client->toArray())) {
        // Envoi d'email de confirmation
        sendConfirmationEmail($client);
        
        echo json_encode([
            'status' => 'success',
            'message' => '✅ Réservation confirmée !'
        ]);
    }
} catch (PDOException $e) {
    error_log("Erreur BD: " . $e->getMessage());
    echo json_encode([
        'status' => 'error',
        'message' => '❌ Service temporairement indisponible'
    ]);
}

function sendConfirmationEmail($client) {
    // Implémentation d'envoi d'email
}
?>