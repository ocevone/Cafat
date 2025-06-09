<?php
/**
 * Script de génération d'un fichier XML simple
 * Ce script crée un fichier XML avec une balise racine <doc>
 */

// Création d'un nouveau document XML
$xml = new DOMDocument('1.0', 'UTF-8');

// Configuration pour un affichage formaté (indentation)
$xml->formatOutput = true;

// Création de la balise racine <doc>
$root = $xml->createElement('doc');
$xml->appendChild($root);

// Sauvegarde du fichier XML
$xml->save('doc.xml');

echo "Le fichier XML a été créé avec succès !\n";
?> 