<?php

namespace App\Controller;

use App\Entity\Promotion;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Contrôleur pour la gestion des promotions.
 * Ce contrôleur permet de créer des promotions et de gérer les interactions avec la base de données.
 */
class PromotionController
{
    private EntityManagerInterface $entityManager;

    /**
     * Constructeur de la classe PromotionController.
     * Initialise l'EntityManager pour interagir avec la base de données.
     *
     * @param EntityManagerInterface $entityManager L'EntityManager de Doctrine.
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Action pour créer une nouvelle promotion.
     * Vérifie si l'utilisateur est connecté, traite les données du formulaire et enregistre la promotion.
     */
    public function createPromotion(): void
    {
        // Démarrer la session pour vérifier si l'utilisateur est connecté
        session_start();
        if (!isset($_SESSION['user'])) {
            // Si l'utilisateur n'est pas connecté, rediriger vers la page de connexion
            $_SESSION['error'] = "Vous devez être connecté pour accéder à cette page.";
            header('Location: /connexion');
            exit();
        }

        // Initialisation de la variable pour stocker les erreurs
        $erreurs = "";

        // Vérifier si le formulaire a été soumis
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Récupérer les données du formulaire
            $libelle = $_POST['libelle'] ?? null;
            $annee = $_POST['annee'] ?? null;

            // Debug : Log des données reçues
            error_log("Received data - Libelle: $libelle, Annee: $annee");

            // Vérifier si les champs sont remplis
            if (empty($libelle) || empty($annee)) {
                $erreurs = "Tous les champs sont obligatoires.";
            } else {
                // Vérifier si une promotion avec le même libellé et année existe déjà
                $existingPromotion = $this->entityManager->getRepository(Promotion::class)->findOneBy([
                    'libelle' => $libelle,
                    'annee' => $annee
                ]);

                if ($existingPromotion) {
                    // Si une promotion existe déjà, afficher une erreur
                    $erreurs = "Une promotion avec ce libellé et cette année existe déjà.";
                } else {
                    try {
                        // Créer une nouvelle promotion
                        $promotion = new Promotion();
                        $promotion->setLibelle($libelle);
                        $promotion->setAnnee($annee);

                        // Debug : Log avant d'enregistrer
                        error_log("Persisting promotion - Libelle: $libelle, Annee: $annee");

                        // Enregistrer la promotion dans la base de données
                        $this->entityManager->persist($promotion);
                        $this->entityManager->flush();

                        // Debug : Log après l'enregistrement
                        error_log("Promotion successfully persisted");

                        // Rediriger vers la page d'accueil ou tableau de bord
                        header('Location: /');
                        exit();
                    } catch (\Exception $e) {
                        // En cas d'erreur, afficher un message d'erreur
                        $erreurs = "La création de la promotion a échoué. Veuillez réessayer.";

                        // Debug : Log de l'exception
                        error_log("Exception: " . $e->getMessage());
                    }
                }
            }
        }

        // Rendre la vue pour afficher le formulaire de création de promotion
        $this->render('promotion/create', ['erreurs' => $erreurs]);
    }

    /**
     * Méthode pour rendre une vue.
     * Charge le fichier de template et affiche le contenu.
     *
     * @param string $template Le nom du fichier de template (sans extension).
     * @param array $data Les données à passer à la vue.
     */
    private function render(string $template, array $data = []): void
    {
        // Extraire les données pour les rendre disponibles dans la vue
        extract($data);

        // Démarrer la mise en tampon de sortie
        ob_start();

        // Construire le chemin du fichier de template
        $templatePath = realpath(__DIR__ . "/../../views/$template.php");

        // Inclure le fichier de template s'il existe
        if ($templatePath && file_exists($templatePath)) {
            include $templatePath;
        }

        // Récupérer le contenu mis en tampon
        $content = ob_get_clean();

        // Inclure le fichier de base pour afficher le contenu
        include realpath(__DIR__ . "/../../views/base.php");
    }
}