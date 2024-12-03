<?php
return [
    '/' => ['AccueilController', 'index'],
    '/sanctions' => ['SanctionsController', 'index'],
    '/inscription' => ['SanctionsController', 'inscription'],
    '/connexion' => ['SanctionsController', 'connexion'],
    '/legal' => ['AccueilController', 'legal'],
    '/promotion' => ['PromotionController', 'createPromotion'],
    // Import des etudiant
    '/etudiant/import' => ['EtudiantController', 'import']
];