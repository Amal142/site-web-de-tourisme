<?php
session_start();
require("./config.php");

// Vérification admin
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin_login.php");
    exit;
}

$client = null;
$error = '';

// Récupération du client
if (isset($_GET['id'])) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM client WHERE id = ?");
        $stmt->execute([$_GET['id']]);
        $client = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$client) {
            $error = "Client non trouvé";
        }
    } catch (PDOException $e) {
        $error = "Erreur: " . $e->getMessage();
    }
}

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    $id = $_POST['id'];
    $nom = filter_input(INPUT_POST, 'nom', FILTER_SANITIZE_STRING);
    $prenom = filter_input(INPUT_POST, 'prenom', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $telephone = filter_input(INPUT_POST, 'tel', FILTER_SANITIZE_STRING);
    $adresse = filter_input(INPUT_POST, 'adresse', FILTER_SANITIZE_STRING);
    $date_naissance = $_POST['date_naissance'];
    $hotel = filter_input(INPUT_POST, 'nomhotel', FILTER_SANITIZE_STRING);
    $typechambre = $_POST['typechambre'];

    try {
        $sql = "UPDATE client SET 
                nom = :nom, 
                prenom = :prenom, 
                email = :email, 
                tel = :tel, 
                adresse = :adresse, 
                date_naissance = :date_naissance, 
                nomhotel = :nomhotel, 
                typechambre = :typechambre 
                WHERE id = :id";
                
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':nom' => $nom,
            ':prenom' => $prenom,
            ':email' => $email,
            ':telephone' => $telephone,
            ':adresse' => $adresse,
            ':date_naissance' => $date_naissance,
            ':hotel' => $hotel,
            ':typechambre' => $typechambre,
            ':id' => $id
        ]);
        
        $_SESSION['message'] = "Client mis à jour avec succès";
        header("Location: admin.php");
        exit;
    } catch (PDOException $e) {
        $error = "Erreur lors de la mise à jour: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier Client</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="text-center">Modifier Client</h3>
                    </div>
                    <div class="card-body">
                        <?php if ($error): ?>
                            <div class="alert alert-danger"><?= $error ?></div>
                        <?php endif; ?>
                        
                        <?php if ($client): ?>
                        <form method="post">
                            <input type="hidden" name="id" value="<?= $client['id'] ?>">
                            
                            <div class="mb-3">
                                <label for="nom" class="form-label">Nom</label>
                                <input type="text" class="form-control" id="nom" name="nom" value="<?= htmlspecialchars($client['nom']) ?>" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="prenom" class="form-label">Prénom</label>
                                <input type="text" class="form-control" id="prenom" name="prenom" value="<?= htmlspecialchars($client['prenom']) ?>" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($client['email']) ?>" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="telephone" class="form-label">Téléphone</label>
                                <input type="text" class="form-control" id="telephone" name="telephone" value="<?= htmlspecialchars($client['telephone']) ?>" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="adresse" class="form-label">Adresse</label>
                                <input type="text" class="form-control" id="adresse" name="adresse" value="<?= htmlspecialchars($client['adresse']) ?>" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="date_naissance" class="form-label">Date de naissance</label>
                                <input type="date" class="form-control" id="date_naissance" name="date_naissance" value="<?= htmlspecialchars($client['date_naissance']) ?>" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="hotel" class="form-label">Hôtel</label>
                                <input type="text" class="form-control" id="hotel" name="hotel" value="<?= htmlspecialchars($client['hotel']) ?>" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="typechambre" class="form-label">Type de chambre</label>
                                <select class="form-select" id="typechambre" name="typechambre" required>
                                    <option value="single" <?= $client['typechambre'] === 'single' ? 'selected' : '' ?>>Single</option>
                                    <option value="double" <?= $client['typechambre'] === 'double' ? 'selected' : '' ?>>Double</option>
                                    <option value="suite" <?= $client['typechambre'] === 'suite' ? 'selected' : '' ?>>Suite</option>
                                </select>
                            </div>
                            
                            <button type="submit" name="update" class="btn btn-primary">Mettre à jour</button>
                            <a href="admin.php" class="btn btn-secondary">Annuler</a>
                        </form>
                        <?php else: ?>
                            <div class="alert alert-warning">Client non trouvé</div>
                            <a href="admin.php" class="btn btn-primary">Retour</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>