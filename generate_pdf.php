<?php
session_start();
require("./config.php");
require('fpdf/fpdf.php'); // Télécharger FPDF: http://www.fpdf.org/

// Vérification admin
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin_login.php");
    exit;
}

// Récupération des données
$export_clients = isset($_POST['export_clients']);
$export_inscriptions = isset($_POST['export_inscriptions']);
$export_stats = isset($_POST['export_stats']);

// Création du PDF
class PDF extends FPDF {
    function Header() {
        $this->SetFont('Arial', 'B', 15);
        $this->Cell(0, 10, 'Rapport Admin - Tourisme Tunisie', 0, 1, 'C');
        $this->Ln(10);
    }
    
    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Page ' . $this->PageNo(), 0, 0, 'C');
    }
    
    function ChapterTitle($label) {
        $this->SetFont('Arial', 'B', 12);
        $this->SetFillColor(200, 220, 255);
        $this->Cell(0, 6, $label, 0, 1, 'L', true);
        $this->Ln(4);
    }
    
    function TableHeader($header) {
        $this->SetFont('Arial', 'B', 10);
        foreach ($header as $col) {
            $this->Cell(40, 7, $col, 1);
        }
        $this->Ln();
    }
    
    function TableRow($data) {
        $this->SetFont('Arial', '', 10);
        foreach ($data as $col) {
            $this->Cell(40, 6, $col, 1);
        }
        $this->Ln();
    }
}

$pdf = new PDF();
$pdf->AddPage();

// Statistiques
if ($export_stats) {
    // Calcul des stats
    $stats = [
        'total_clients' => 0,
        'total_inscriptions' => 0,
        'hotels' => [],
        'chambres' => []
    ];

    try {
        // Total clients
        $stmt = $pdo->query("SELECT COUNT(*) as total FROM clients");
        $stats['total_clients'] = $stmt->fetchColumn();
        
        // Total inscriptions
        $stmt = $pdo->query("SELECT COUNT(*) as total FROM inscription");
        $stats['total_inscriptions'] = $stmt->fetchColumn();
        
        // Réservations par hôtel
        $stmt = $pdo->query("SELECT hotel, COUNT(*) as count FROM clients GROUP BY hotel");
        $stats['hotels'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Types de chambre
        $stmt = $pdo->query("SELECT typechambre, COUNT(*) as count FROM clients GROUP BY typechambre");
        $stats['chambres'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        // Erreur silencieuse pour le PDF
    }

    $pdf->ChapterTitle('Statistiques');
    $pdf->SetFont('Arial', '', 10);
    
    $pdf->Cell(0, 6, 'Nombre total de clients: ' . $stats['total_clients'], 0, 1);
    $pdf->Cell(0, 6, 'Nombre total d\'inscriptions: ' . $stats['total_inscriptions'], 0, 1);
    $pdf->Ln(5);
    
    // Réservations par hôtel
    $pdf->SetFont('Arial', 'B', 11);
    $pdf->Cell(0, 6, 'Réservations par hôtel:', 0, 1);
    $pdf->SetFont('Arial', '', 10);
    
    foreach ($stats['hotels'] as $hotel) {
        $pdf->Cell(0, 6, $hotel['hotel'] . ': ' . $hotel['count'] . ' réservations', 0, 1);
    }
    
    $pdf->Ln(5);
    
    // Types de chambre
    $pdf->SetFont('Arial', 'B', 11);
    $pdf->Cell(0, 6, 'Types de chambre:', 0, 1);
    $pdf->SetFont('Arial', '', 10);
    
    foreach ($stats['chambres'] as $chambre) {
        $pdf->Cell(0, 6, $chambre['typechambre'] . ': ' . $chambre['count'] . ' réservations', 0, 1);
    }
    
    $pdf->Ln(10);
}

// Clients
if ($export_clients) {
    $clients = [];
    try {
        $stmt = $pdo->query("SELECT * FROM clients ORDER BY id DESC");
        $clients = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        // Erreur silencieuse pour le PDF
    }

    $pdf->ChapterTitle('Liste des Clients');
    
    if (!empty($clients)) {
        $header = ['ID', 'Nom', 'Prénom', 'Email', 'Téléphone', 'Hôtel', 'Type Chambre'];
        $pdf->TableHeader($header);
        
        foreach ($clients as $client) {
            $pdf->TableRow([
                $client['id'],
                $client['nom'],
                $client['prenom'],
                $client['email'],
                $client['telephone'],
                $client['hotel'],
                $client['typechambre']
            ]);
        }
    } else {
        $pdf->Cell(0, 6, 'Aucun client trouvé', 0, 1);
    }
    
    $pdf->Ln(10);
}

// Inscriptions
if ($export_inscriptions) {
    $inscriptions = [];
    try {
        $stmt = $pdo->query("SELECT * FROM inscription ORDER BY id DESC");
        $inscriptions = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        // Erreur silencieuse pour le PDF
    }

    $pdf->ChapterTitle('Liste des Inscriptions');
    
    if (!empty($inscriptions)) {
        $header = ['ID', 'Nom', 'Prénom', 'Email', 'Téléphone'];
        $pdf->TableHeader($header);
        
        foreach ($inscriptions as $inscription) {
            $pdf->TableRow([
                $inscription['id'],
                $inscription['nom'],
                $inscription['prenom'],
                $inscription['email'],
                $inscription['tel']
            ]);
        }
    } else {
        $pdf->Cell(0, 6, 'Aucune inscription trouvée', 0, 1);
    }
}

$pdf->Output('D', 'rapport_tourisme_tunisie_' . date('Y-m-d') . '.pdf');
?>