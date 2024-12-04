<?php

namespace App\Controller;

use App\Entity\Promotion;
use Doctrine\ORM\EntityManagerInterface;

class PromotionController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function createPromotion(): void
    {
        session_start();
        if (!isset($_SESSION['user'])) {
            $_SESSION['error'] = "Vous devez être connecté pour accéder à cette page.";
            header('Location: /connexion');
            exit();
        }

        $erreurs = ""; // Initialisez la variable $erreurs

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $libelle = $_POST['libelle'] ?? null;
            $annee = $_POST['annee'] ?? null;

            // Debug: Log received data
            error_log("Received data - Libelle: $libelle, Annee: $annee");

            if (empty($libelle) || empty($annee)) {
                $erreurs = "Tous les champs sont obligatoires.";
            } else {
                // Vérifier l'unicité du libellé et de l'année
                $existingPromotion = $this->entityManager->getRepository(Promotion::class)->findOneBy(['libelle' => $libelle, 'annee' => $annee]);
                if ($existingPromotion) {
                    $erreurs = "Une promotion avec ce libellé et cette année existe déjà.";
                } else {
                    try {
                        // Créer une nouvelle promotion
                        $promotion = new Promotion();
                        $promotion->setLibelle($libelle);
                        $promotion->setAnnee($annee);

                        // Debug: Log before persisting
                        error_log("Persisting promotion - Libelle: $libelle, Annee: $annee");

                        // Enregistrer la promotion dans la base de données
                        $this->entityManager->persist($promotion);
                        $this->entityManager->flush();

                        // Debug: Log after flushing
                        error_log("Promotion successfully persisted");

                        // Rediriger vers la page d'accueil ou tableau de bord
                        header('Location: /');
                        exit();
                    } catch (\Exception $e) {
                        $erreurs = "La création de la promotion a échoué. Veuillez réessayer.";

                        // Debug: Log exception
                        error_log("Exception: " . $e->getMessage());
                    }
                }
            }
        }

        $this->render('promotion/create', ['erreurs' => $erreurs]);
    }


private function render(string $template, array $data = []): void
{
    extract($data);
    ob_start();
    $templatePath = realpath(__DIR__ . "/../../views/$template.php");
    if ($templatePath && file_exists($templatePath)) {
        include $templatePath;
    }
    $content = ob_get_clean();
    include realpath(__DIR__ . "/../../views/base.php");
}
}
