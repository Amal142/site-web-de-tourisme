<?php
session_start();
require("./config.php");

// Vérification de l'authentification admin
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin_login.php");
    exit;
}

// Récupération des clients
$clients = [];
try {
    $stmt = $pdo->query("SELECT * FROM client ORDER BY id DESC");
    $clients = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = "Erreur lors de la récupération des clients: " . $e->getMessage();
}

// Récupération des inscriptions
$inscriptions = [];
try {
    $stmt = $pdo->query("SELECT * FROM inscription ORDER BY id DESC");
    $inscriptions = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = "Erreur lors de la récupération des inscriptions: " . $e->getMessage();
}

// Statistiques
$stats = [
    'total_clients' => 0,
    'total_inscriptions' => 0,
    'hotels' => [],
    'chambres' => []
];

try {
    // Total clients
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM client");
    $stats['total_clients'] = $stmt->fetchColumn();
    
    // Total inscriptions
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM inscription");
    $stats['total_inscriptions'] = $stmt->fetchColumn();
    
    // Réservations par hôtel
    $stmt = $pdo->query("SELECT nomhotel, COUNT(*) as count FROM client GROUP BY nomhotel");
    $stats['hotels'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Types de chambre
    $stmt = $pdo->query("SELECT typechambre, COUNT(*) as count FROM client GROUP BY typechambre");
    $stats['chambres'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = "Erreur lors du calcul des statistiques: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Gestion Tourisme Tunisie</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .sidebar {
            min-height: 100vh;
            background-color: #343a40;
        }
        .sidebar a {
            color: rgba(255,255,255,.5);
        }
        .sidebar a:hover {
            color: rgba(255,255,255,.75);
        }
        .active {
            color: white !important;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-2 sidebar p-0">
                <div class="p-3">
                    <h4 class="text-white">Admin Panel</h4>
                    <hr class="bg-secondary">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link active" href="admin.php">Dashboard</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#clients">Clients</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#inscriptions">Inscriptions</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="admin_logout.php">Déconnexion</a>
                        </li>
                    </ul>
                </div>
            </div>
            
            <!-- Main Content -->
            <div class="col-md-10 p-4">
                <h2>Dashboard Admin</h2>
                <hr>
                
                <!-- Statistiques -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Statistiques</h5>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="card text-white bg-primary mb-3">
                                            <div class="card-body">
                                                <h5 class="card-title">Clients</h5>
                                                <p class="card-text h2"><?= $stats['total_clients'] ?></p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="card text-white bg-success mb-3">
                                            <div class="card-body">
                                                <h5 class="card-title">Inscriptions</h5>
                                                <p class="card-text h2"><?= $stats['total_inscriptions'] ?></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Graphiques -->
                                <div class="row">
                                    <div class="col-md-12">
                                        <canvas id="hotelChart" height="200"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Export PDF</h5>
                                <form action="generate_pdf.php" method="post" target="_blank">
                                    <div class="mb-3">
                                        <label class="form-label">Sélectionner les données à exporter:</label>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="export_clients" id="export_clients" checked>
                                            <label class="form-check-label" for="export_clients">Clients</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="export_inscriptions" id="export_inscriptions" checked>
                                            <label class="form-check-label" for="export_inscriptions">Inscriptions</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="export_stats" id="export_stats" checked>
                                            <label class="form-check-label" for="export_stats">Statistiques</label>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-danger">Générer PDF</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Liste des clients -->
                <div class="card mb-4" id="clients">
                    <div class="card-header">
                        <h5 class="card-title">Gestion des Clients</h5>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($error)): ?>
                            <div class="alert alert-danger"><?= $error ?></div>
                        <?php endif; ?>
                        
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nom</th>
                                        <th>Prénom</th>
                                        <th>Email</th>
                                        <th>Téléphone</th>
                                        <th>Hôtel</th>
                                        <th>Type Chambre</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($clients as $client): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($client['id']) ?></td>
                                        <td><?= htmlspecialchars($client['nom']) ?></td>
                                        <td><?= htmlspecialchars($client['prenom']) ?></td>
                                        <td><?= htmlspecialchars($client['email']) ?></td>
                                        <td><?= htmlspecialchars($client['tel']) ?></td>
                                        <td><?= htmlspecialchars($client['nomhotel']) ?></td>
                                        <td><?= htmlspecialchars($client['typechambre']) ?></td>
                                        <td>
                                            <a href="edit_client.php?id=<?= $client['id'] ?>" class="btn btn-sm btn-warning">Modifier</a>
                                            <a href="delete_client.php?id=<?= $client['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce client?')">Supprimer</a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                
                <!-- Liste des inscriptions -->
                <div class="card mb-4" id="inscriptions">
                    <div class="card-header">
                        <h5 class="card-title">Gestion des Inscriptions</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nom</th>
                                        <th>Prénom</th>
                                        <th>Email</th>
                                        <th>Téléphone</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($inscriptions as $inscription): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($inscription['id']) ?></td>
                                        <td><?= htmlspecialchars($inscription['nom']) ?></td>
                                        <td><?= htmlspecialchars($inscription['prenom']) ?></td>
                                        <td><?= htmlspecialchars($inscription['email']) ?></td>
                                        <td><?= htmlspecialchars($inscription['tel']) ?></td>
                                        <td>
                                            <a href="delete_inscription.php?id=<?= $inscription['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette inscription?')">Supprimer</a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Graphique des réservations par hôtel
        const hotelCtx = document.getElementById('hotelChart').getContext('2d');
        const hotelChart = new Chart(hotelCtx, {
            type: 'bar',
            data: {
                labels: <?= json_encode(array_column($stats['hotels'], 'hotel')) ?>,
                datasets: [{
                    label: 'Réservations par hôtel',
                    data: <?= json_encode(array_column($stats['hotels'], 'count')) ?>,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</body>
</html>
