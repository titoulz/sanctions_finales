<?php
namespace App\Controller;

use App\UserStory\CreateAccount;
use App\Entity\User;
use App\Entity\Sanction;
use App\Entity\Promotion;
use App\Entity\Etudiant;
use Doctrine\ORM\EntityManagerInterface;

// Affiche les erreurs PHP
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

/**
 * Contrôleur pour la gestion des sanctions.
 * Ce contrôleur gère les actions liée aux sanction, (création, l'affichage, l'inscription et la connexion.)
 */
class SanctionsController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private array $sanctions = [];

    /**
     * Constructeur de la classe SanctionsController.
     * Initialise l'EntityManager(l'entitymanager est utilisé pour interagir avec la base de données) et la session pour la gestion des sanctions.
     *
     * @param EntityManagerInterface $entityManager L'EntityManager de Doctrine pour interagir avec la base de données.
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;

        // $session stocke les donnée de la session
        session_start();
        if (!isset($_SESSION['sanctions'])) {
            $_SESSION['sanctions'] = [];
        }
        $this->sanctions = &$_SESSION['sanctions']; // Référence pour modifier directement la session

        error_log('SanctionsController::__construct called');
    }


    public function index(): void
    {
        error_log('SanctionsController::index called');
        $this->render('sanctions/index', [
            'sanctions' => $this->sanctions
        ]);
    }

 
    public function inscription(): void
    {
        error_log('SanctionsController::inscription called');
        $erreurs = "";
        // Vérifie si le formulaire a été soumis
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Récupère les données du formulaire
            $nom = $_POST['nom'];
            $prenom = $_POST['prenom'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $confirmPassword = $_POST['confirmPassword'];

            try {
                // Tente de créer un compte en utilisant la classe CreateAccount
                $user = new CreateAccount($this->entityManager);
                $user->execute($nom, $prenom, $email, $password, $confirmPassword);
                // Redirige vers la page de connexion si l'inscription réussit
                $this->redirect('/connexion');
            } catch (\Exception $e) {
                // Capture les erreurs et les affiche
                $erreurs = $e->getMessage();
                error_log('Error in inscription: ' . $erreurs);
            }
        }
        // Affiche le formulaire d'inscription avec les erreurs éventuelles
        $this->render('Sanctions/inscription', ['erreurs' => $erreurs]);
    }

    /**
     * Action pour la connexion d'un utilisateur.
     * Gère le formulaire de connexion et la vérification des identifiants.
     */
    public function connexion(): void
    {
        error_log('SanctionsController::connexion called');
        $erreurs = ""; // Initialise la variable pour les erreurs

        // Vérifie si le formulaire a été soumis
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Récupère les données du formulaire
            $email = $_POST['email'];
            $password = $_POST['password'];

            try {
                // Recherche l'utilisateur par son email
                $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $email]);

                // Vérifie si l'utilisateur existe
                if (!$user) {
                    throw new \Exception("L'adresse email n'existe pas.");
                }

                // Vérifie le mot de passe
                if (!password_verify($password, $user->getPassword())) {
                    throw new \Exception("Le mot de passe est incorrect.");
                }

                // Connexion réussie, démarre la session et stocke les informations de l'utilisateur
                session_start();
                $_SESSION['user'] = [
                    'id' => $user->getId(),
                    'prenom' => $user->getPrenom()
                ];
                // Redirige vers la page d'accueil
                $this->redirect('/');
            } catch (\Exception $e) {
                // Capture les erreurs et les affiche
                $erreurs = $e->getMessage();
                error_log('Error in connexion: ' . $erreurs);
            }
        }

        // Affiche le formulaire de connexion avec les erreurs éventuelles
        $this->render('Sanctions/connexion', ['erreurs' => $erreurs]);
    }

    /**
     * Action pour la création d'une nouvelle sanction.
     * Gère le formulaire de création de sanction et l'enregistrement dans la base de données.
     */
    public function createSanction(): void
    {
        $errors = [];
        $selectedPromotionId = null;
        $etudiants = [];
        error_log('SanctionsController::createSanction called');

        // Vérifie si une session est déjà active
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
            error_log('Session started');
        }

        // Vérifie si l'utilisateur est connecté
        if (!isset($_SESSION['user']['id'])) {
            $errors[] = "Vous devez être connecté pour créer une sanction.";
            error_log('User not logged in');
            $this->render('sanctions/create', ['errors' => $errors]);
            return;
        }

        // Récupère toutes les promotions
        try {
            $promotions = $this->entityManager->getRepository(Promotion::class)->findAll();
        } catch (\Exception $e) {
            $errors[] = "Erreur lors de la récupération des promotions: " . $e->getMessage();
            error_log('Error fetching promotions: ' . $e->getMessage());
            $this->render('create_sanction', ['errors' => $errors, 'promotions' => []]);
            return;
        }

        // Si le formulaire est soumis
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Si une promotion a été sélectionnée
            if (isset($_POST['promotion_id']) && !empty($_POST['promotion_id'])) {
                $selectedPromotionId = $_POST['promotion_id'];
                try {
                    $promotion = $this->entityManager->getRepository(Promotion::class)->find($selectedPromotionId);
                    if ($promotion) {
                        $etudiants = $this->entityManager->getRepository(Etudiant::class)->findBy(['promotion' => $promotion]);
                    } else {
                        $errors[] = "Promotion non trouvée.";
                    }
                } catch (\Exception $e) {
                    $errors[] = "Erreur lors de la récupération des étudiants: " . $e->getMessage();
                    error_log('Error fetching students: ' . $e->getMessage());
                }
            }

            // Si le formulaire de création de sanction est soumis
            if (isset($_POST['submit_sanction'])) {
                try {
                    error_log('Form submitted');
                    // Récupère les données du formulaire
                    $eleveId = $_POST['eleve'] ?? '';
                    $professeur = $_POST['professeur'] ?? '';
                    $motif = $_POST['motif'] ?? '';
                    $description = $_POST['description'] ?? '';
                    $date_incident = $_POST['date_incident'] ?? '';

                    // Valide les données
                    if (empty($eleveId)) $errors[] = "L'élève est requis.";
                    if (empty($professeur)) $errors[] = "Le professeur est requis.";
                    if (empty($motif)) $errors[] = "Le motif est requis.";
                    if (empty($description)) $errors[] = "La description est requise.";
                    if (empty($date_incident)) $errors[] = "La date de l'incident est requise.";
                    if (empty($errors)) {
                        $date_creation = new \DateTime();
                        $cree_par = $_SESSION['user']['id'];

                        // Crée une nouvelle sanction et l'enregistre dans la base de données
                        $sanction = new Sanction();
                        $etudiant = $this->entityManager->getRepository(Etudiant::class)->find($eleveId);
                        $sanction->setEleve($etudiant->getNom().' '.$etudiant->getPrenom());
                        $sanction->setProfesseur($professeur);
                        $sanction->setMotif($motif);
                        $sanction->setDescription($description);
                        $sanction->setDateIncident(new \DateTime($date_incident));
                        $sanction->setDateCreation($date_creation);
                        $sanction->setCreePar($cree_par);

                        $this->entityManager->persist($sanction);
                        $this->entityManager->flush();

                        error_log('Sanction created successfully');
                        // Redirige vers la page d'accueil après la création
                        $this->redirect('/');
                        return;
                    }
                } catch (\Exception $e) {
                    // Capture les erreurs et les affiche
                    $errors[] = "Erreur: " . $e->getMessage();
                    error_log('Error in createSanction: ' . $e->getMessage());
                }
            }
        }

        // Affiche le formulaire de création de sanction avec les erreurs éventuelles et les données récupérées
        $this->render('create_sanction', [
            'errors' => $errors,
            'promotions' => $promotions ?? [],
            'etudiants' => $etudiants,
            'selectedPromotionId' => $selectedPromotionId
        ]);

    }
}
