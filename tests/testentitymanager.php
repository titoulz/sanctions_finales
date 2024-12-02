<?
require_once __DIR__ . '/config/bootstrap.php';

$entityManager = require __DIR__ . '/config/bootstrap.php';

if ($entityManager instanceof \Doctrine\ORM\EntityManager) {
    echo "EntityManager créé avec succès.";
} else {
    echo "Échec de la création de l'EntityManager.";
}