Déclaration Nominative XML
Échéance :19 mai 2025 17:00
C6 - Développer des composants d’accès aux données SQL
Instructions
🎯 Contexte du projet :
Dans votre futur métier de développeur web, vous aurez régulièrement à générer des fichiers structurés selon les exigences précises d'organismes publics. Ce projet vise à évaluer votre capacité à modéliser, exploiter une base de données relationnelle et développer des composants d'accès aux données.

Dans ce devoir, vous devrez importer, comprendre, et interroger une base de données SQL inconnue, puis générer automatiquement, grâce à un script PHP, un fichier XML conforme au cahier des charges technique officiel de la CAFAT.

Ce devoir constitue un complément à l'ECF de l'Activité Type 2, qui aura lieu en semaine 21. Il peut vous permettre de valider les compétences C5 et C6.
🎓 Compétences évaluées :
C5 : Mettre en place une base de données relationnelle.

C6 : Développer les composants d'accès aux données.

📂 Documents fournis :
Base de données SQL : comptagest.sql

Cahier des charges CAFAT (pages 15 à 27 pour le détail des balises XML)

Schéma XML (XSD) : app-validator-2.0.xsd

📌 Déroulement détaillé :
Étape 1 : Importation et analyse des données

Importez la base fournie (comptagest.sql) dans votre environnement MariaDB ou MySQL avec Workbench.

Analysez attentivement la structure de cette base de données.

Étape 2 : Compréhension du cahier des charges

Lisez le cahier des charges de la CAFAT en entier.

Étudiez les pages 15 à 27.

Repérez précisément les données à extraire et la structuration attendue dans le fichier XML.

Étape 3 : Création des requêtes SQL

Écrivez les requêtes SQL nécessaires pour extraire les données (salariés, rémunérations, assiettes, cotisations…) pour un trimestre et une année donnés.

Étape 4 : Développement du script PHP dynamique

Créez un script PHP qui :

Se connecte à votre base de données.

Demande en entrée un trimestre (1, 2, 3, ou 4) et une année (ex : 2025).

Exécute vos requêtes SQL afin de récupérer dynamiquement les données pour la période saisie.

Génère automatiquement un fichier XML valide et conforme au schéma XSD fourni.

Étape 5 : Validation technique XML

Vérifiez la conformité de votre fichier XML avec le schéma XSD fourni.

⚠️ Format du fichier XML à respecter :
Le nom du fichier XML généré par votre script PHP doit suivre strictement la règle de nommage du cahier des charges CAFAT :

DN-[AAAA]T[TT]-[CompteEmployeur][SuffixeEmployeur]-[IdentifiantUnique].xml
Exemple concret :

Pour le premier trimestre 2025, compte employeur 1234567, suffixe 001, identifiant unique 001, vous devez obtenir :

DN-2025T01-1234567001-001.xml
Référez-vous au cahier des charges pour plus de détails sur cette règle.

✅ Livrable attendu :
Votre script PHP, structuré et commenté, prêt à l'emploi pour générer le fichier XML demandé.

⚠️ Important :

Le 19 mai, votre formatrice vous donnera le trimestre et l'année à générer en direct. Vous devrez être capable d'exécuter immédiatement votre script PHP pour fournir le fichier XML conforme à la demande.

🏅 Évaluation (Acquis / Non acquis) :
Votre devoir sera évalué immédiatement à partir du fichier XML généré par votre script :

✅ Acquis si :

Le fichier XML généré est parfaitement conforme au schéma XSD.

Les données extraites correspondent précisément aux données attendues pour le trimestre et l'année indiqués.

Le nom du fichier respecte rigoureusement la règle de nommage imposée.

❌ Non acquis dans tous les autres cas.

Bonne réalisation à toutes et à tous ! 🚀✨
