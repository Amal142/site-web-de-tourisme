<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $coutTotal = floatval($_POST["coutTotal"]);
    $budgetUtilisateur = floatval($_POST["budgetUtilisateur"]);

    if ($budgetUtilisateur >= $coutTotal) {
        echo "✅ Votre budget est suffisant ! Le coût total est de $".$coutTotal;
    } else {
        $manque = $coutTotal - $budgetUtilisateur;
        echo " Votre budget est insuffisant. Il vous manque $".$manque.". Le coût total est de $".$coutTotal.".";
    }
}
?>
