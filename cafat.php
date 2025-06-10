<?php
/**
 * Script de génération d'un fichier XML pour la CAFAT
 * Format conforme à la spécification technique
 */

// Vérification si le script est exécuté en ligne de commande
if (php_sapi_name() !== 'cli') {
    die("Ce script doit être exécuté en ligne de commande.\n");
}

// Configuration de la base de données
$host = 'localhost';
$dbname = 'ecfc6';
$username = 'root';
$password = '';

// Récupération des arguments
$options = getopt("t:y:", ["trimestre:", "annee:"]);

// Récupération du trimestre
$trimestre = isset($options['t']) ? intval($options['t']) : 
            (isset($options['trimestre']) ? intval($options['trimestre']) : date('n'));

// Récupération de l'année
$annee = isset($options['y']) ? intval($options['y']) : 
        (isset($options['annee']) ? intval($options['annee']) : date('Y'));

try {
    // Connexion à la base de données
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Validation des paramètres
    if ($trimestre < 1 || $trimestre > 4) {
        throw new Exception("Le trimestre doit être compris entre 1 et 4");
    }
    if ($annee < 2000 || $annee > 2100) {
        throw new Exception("L'année doit être valide");
    }

    echo "Génération du fichier XML pour le trimestre $trimestre de l'année $annee...\n";

    // Création du document XML
    $xml = new DOMDocument('1.0', 'UTF-8');
    $xml->formatOutput = true;

    // Création de l'élément racine
    $declaration = $xml->createElement('declaration');
    $declaration->setAttribute('xmlns', 'http://www.cafat.nc/declaration');
    $xml->appendChild($declaration);

    // En-tête de la déclaration
    $entete = $xml->createElement('entete');
    $declaration->appendChild($entete);

    // Ajout des informations de l'entreprise
    $entreprise = $xml->createElement('entreprise');
    $entete->appendChild($entreprise);

    // Récupération des informations de l'entreprise depuis la base de données
    $stmt = $pdo->query("SELECT * FROM entreprise LIMIT 1");
    $entrepriseData = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($entrepriseData) {
        $entreprise->appendChild($xml->createElement('siret', $entrepriseData['siret']));
        $entreprise->appendChild($xml->createElement('raisonSociale', $entrepriseData['raison_sociale']));
        $entreprise->appendChild($xml->createElement('adresse', $entrepriseData['adresse']));
        $entreprise->appendChild($xml->createElement('codePostal', $entrepriseData['code_postal']));
        $entreprise->appendChild($xml->createElement('ville', $entrepriseData['ville']));
    }

    // Ajout des informations de période
    $periode = $xml->createElement('periode');
    $entete->appendChild($periode);
    $periode->appendChild($xml->createElement('trimestre', $trimestre));
    $periode->appendChild($xml->createElement('annee', $annee));

    echo "Récupération des données des salariés...\n";

    // Récupération des salariés pour la période
    $stmt = $pdo->prepare("
        SELECT DISTINCT s.* 
        FROM salarie s 
        INNER JOIN bulletin b ON s.id = b.salarie_id 
        WHERE YEAR(b.date_paie) = :annee 
        AND QUARTER(b.date_paie) = :trimestre
    ");
    $stmt->execute(['annee' => $annee, 'trimestre' => $trimestre]);
    $salaries = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Création de la section salariés
    $salariesSection = $xml->createElement('salaries');
    $declaration->appendChild($salariesSection);

    foreach ($salaries as $salarie) {
        echo "Traitement du salarié {$salarie['nom']} {$salarie['prenom']}...\n";
        
        $salarieElement = $xml->createElement('salarie');
        $salariesSection->appendChild($salarieElement);

        // Informations du salarié
        $salarieElement->appendChild($xml->createElement('matricule', $salarie['matricule']));
        $salarieElement->appendChild($xml->createElement('nom', $salarie['nom']));
        $salarieElement->appendChild($xml->createElement('prenom', $salarie['prenom']));
        $salarieElement->appendChild($xml->createElement('dateNaissance', $salarie['date_naissance']));
        $salarieElement->appendChild($xml->createElement('numeroSecuriteSociale', $salarie['numero_secu']));

        // Récupération des rémunérations du salarié
        $stmt = $pdo->prepare("
            SELECT b.*, r.* 
            FROM bulletin b 
            INNER JOIN rubrique r ON b.rubrique_id = r.id 
            WHERE b.salarie_id = :salarie_id 
            AND YEAR(b.date_paie) = :annee 
            AND QUARTER(b.date_paie) = :trimestre
        ");
        $stmt->execute([
            'salarie_id' => $salarie['id'],
            'annee' => $annee,
            'trimestre' => $trimestre
        ]);
        $remunerations = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Création de la section rémunérations
        $remunerationsElement = $xml->createElement('remunerations');
        $salarieElement->appendChild($remunerationsElement);

        foreach ($remunerations as $remuneration) {
            $remunerationElement = $xml->createElement('remuneration');
            $remunerationsElement->appendChild($remunerationElement);

            $remunerationElement->appendChild($xml->createElement('code', $remuneration['code']));
            $remunerationElement->appendChild($xml->createElement('libelle', $remuneration['libelle']));
            $remunerationElement->appendChild($xml->createElement('base', $remuneration['base']));
            $remunerationElement->appendChild($xml->createElement('tauxSalarial', $remuneration['taux_salarial']));
            $remunerationElement->appendChild($xml->createElement('montantSalarial', $remuneration['montant_salarial']));
            $remunerationElement->appendChild($xml->createElement('tauxPatronal', $remuneration['taux_patronal']));
            $remunerationElement->appendChild($xml->createElement('montantPatronal', $remuneration['montant_patronal']));
        }
    }

    // Génération du fichier XML
    $filename = "declaration_cafat_{$annee}_T{$trimestre}.xml";
    $xml->save($filename);

    echo "Le fichier XML a été généré avec succès : $filename\n";

} catch (Exception $e) {
    echo "Erreur : " . $e->getMessage() . "\n";
    exit(1);
}
?> 