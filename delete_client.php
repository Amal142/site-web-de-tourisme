<?php
session_start();
require("./config.php");

// Vérification admin
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin_login.php");
    exit;
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    try {
        $stmt = $pdo->prepare("DELETE FROM clients WHERE id = ?");
        $stmt->execute([$id]);
        
        $_SESSION['message'] = "Client supprimé avec succès";
    } catch (PDOException $e) {
        $_SESSION['error'] = "Erreur lors de la suppression: " . $e->getMessage();
    }
}

header("Location: admin.php");
exit;
?>