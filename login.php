<?php
session_start();
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. Récupération et validation des données
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $password = $_POST['password'] ?? '';

    // 2. Validation des champs
    if (!$email || empty($password)) {
        header('Location: login.html?error=Tous les champs sont obligatoires');
        exit();
    }

    try {
        // 3. Connexion à la base de données avec options de sécurité
        $pdo = new PDO(
            "mysql:host=".DB_HOST.";dbname=tourisme;charset=utf8mb4",
            DB_USER, 
            DB_PASS,
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false
            ]
        );

        // 4. Recherche de l'utilisateur avec la colonne 'passwords'
        $stmt = $pdo->prepare("
            SELECT id, nom, prenom, email, passwords, tel 
            FROM inscription 
            WHERE email = :email
            LIMIT 1
        ");
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();

        // 5. Vérification de l'utilisateur
        if ($stmt->rowCount() === 1) {
            $user = $stmt->fetch();
            
            // 6. Vérification du mot de passe (avec colonne 'passwords')
            if ($user && password_verify($password, $user['passwords'])) {
                // 7. Création de la session avec toutes les données
                $_SESSION['user'] = [
                    'id' => $user['id'],
                    'nom' => $user['nom'],
                    'prenom' => $user['prenom'],
                    'email' => $user['email'],
                    'tel' => $user['tel']
                ];
                
                // 8. Sécurité supplémentaire
                session_regenerate_id(true);
                
                // 9. Redirection vers la page profil
                header('Location: profil.html');
                exit();
            }
        }
        
        // 10. Échec de connexion avec délai de sécurité
        sleep(2); // Décourage les attaques par force brute
        header('Location: login.html?error=Email ou mot de passe incorrect');
        exit();

    } catch (PDOException $e) {
        // 11. Gestion des erreurs avec journalisation
        error_log("Erreur DB: " . $e->getMessage());
        header('Location: login.html?error=Erreur système. Veuillez réessayer.');
        exit();
    }
} else {
    // 12. Accès direct interdit
    header('Location: login.html');
    exit();
}