<?php
session_start();

// 1. Inclure le fichier de configuration
require __DIR__ . '/config.php';

// 2. Vérifier que le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: login.html?error=Méthode non autorisée");
    exit();
}

// 3. Récupérer et valider les données
$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
$password = $_POST['password'] ?? '';

if (!$email || empty($password)) {
    header("Location: login.html?error=Tous les champs sont obligatoires");
    exit();
}

try {
    // 4. Connexion à la base de données
    $pdo = new PDO(
        "mysql:host=".DB_HOST.";dbname=".DB_NAME.";charset=utf8mb4", 
        DB_USER, 
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );

    // 5. Recherche de l'utilisateur
    $stmt = $pdo->prepare("SELECT id, nom, prenom, email, passwords FROM inscription WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    // 6. Vérification du mot de passe
    if ($user && password_verify($passwords, $user['passwords'])) {
        // 7. Création de la session
        $_SESSION['user'] = [
            'id' => $user['id'],
            'nom' => $user['nom'],
            'prenom' => $user['prenom'],
            'email' => $user['email']
        ];
        
        // 8. Redirection vers profil.html
        header("Location: profil.html");
        exit();
    } else {
        header("Location: login.html?error=Email ou mot de passe incorrect");
        exit();
    }
} catch (PDOException $e) {
    error_log("Erreur de connexion DB: " . $e->getMessage());
    header("Location: login.html?error=Erreur système");
    exit();
}
?>