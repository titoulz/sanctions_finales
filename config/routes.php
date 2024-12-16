<?php
return [
    // Route pour la page d'accueil
    '/' => ['AccueilController', 'index'],
    // Route pour la page des sanctions
    '/sanctions' => ['SanctionsController', 'index'],
    // Route pour l'inscription
    '/inscription' => ['SanctionsController', 'inscription'],
    // Route pour la connexion
    '/connexion' => ['SanctionsController', 'connexion'],
    // Route pour la page des mentions légales
    '/legal' => ['AccueilController', 'legal'],
    // Route pour la création d'une promotion
    '/promotion' => ['PromotionController', 'createPromotion'],
    // Import des etudiant
    '/etudiant/import' => ['EtudiantController', 'import'],
     // Route pour la déconnexion
     '/deconnexion' => ['SecurityController', 'deconnexion'],
     //Route pour l'ajout d'une sanction
     '/sanction/create' => ['SanctionsController', 'createSanction']

];