<?php
namespace App\Controller;

use App\UserStory\CreateAccount;
use App\Entity\User;
use App\Entity\Sanction;
use App\Entity\Promotion;
use App\Entity\Etudiant;
use Doctrine\ORM\EntityManagerInterface;
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
class SanctionsController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private array $sanctions = [];

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;

        // Simuler une base de données avec une session
        session_start();
        if (!isset($_SESSION['sanctions'])) {
            $_SESSION['sanctions'] = [];
        }
        $this->sanctions = &$_SESSION['sanctions'];

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
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom = $_POST['nom'];
            $prenom = $_POST['prenom'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $confirmPassword = $_POST['confirmPassword'];

            try {
                // Tenter de créer un compte
                $user = new CreateAccount($this->entityManager);
                $user->execute($nom, $prenom, $email, $password, $confirmPassword);
                $this->redirect('/connexion');
            } catch (\Exception $e) {
                $erreurs = $e->getMessage();
                error_log('Error in inscription: ' . $erreurs);
            }
        }
        $this->render('Sanctions/inscription', ['erreurs' => $erreurs]);
    }

    public function connexion(): void
    {
        error_log('SanctionsController::connexion called');
        $erreurs = ""; // Initialisez la variable $erreurs

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'];
            $password = $_POST['password'];

            try {
                // Rechercher l'utilisateur par email
                $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $email]);

                if (!$user) {
                    throw new \Exception("L'adresse email n'existe pas.");
                }

                // Vérifier le mot de passe
                if (!password_verify($password, $user->getPassword())) {
                    throw new \Exception("Le mot de passe est incorrect.");
                }

                // Connexion réussie, rediriger vers la page d'accueil ou tableau de bord
                session_start();
                $_SESSION['user'] = [
                    'id' => $user->getId(),
                    'prenom' => $user->getPrenom()
                ];
                $this->redirect('/');
            } catch (\Exception $e) {
                $erreurs = $e->getMessage();
                error_log('Error in connexion: ' . $erreurs);
            }
        }

        $this->render('Sanctions/connexion', ['erreurs' => $erreurs]);
    }
public function createSanction(): void {
    $errors = [];
    error_log('SanctionsController::createSanction called');
    
    // Vérifier si une session est déjà active
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
        error_log('Session started');
    }

    // Vérifier si l'utilisateur est connecté
    if (!isset($_SESSION['user']['id'])) {
        $errors[] = "Vous devez être connecté pour créer une sanction.";
        error_log('User not logged in');
        $this->render('sanctions/create', ['errors' => $errors]);
        return;
    }

    // Si le formulaire est soumis
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        try {
            error_log('Form submitted');
            // Récupérer les données du formulaire
            $eleve = $_POST['eleve'] ?? '';
            $professeur = $_POST['professeur'] ?? '';
            $motif = $_POST['motif'] ?? '';
            $description = $_POST['description'] ?? '';
            $date_incident = $_POST['date_incident'] ?? '';
            
            // Valider les données
            if (empty($eleve)) $errors[] = "L'élève est requis.";
            if (empty($professeur)) $errors[] = "Le professeur est requis.";
            if (empty($motif)) $errors[] = "Le motif est requis.";
            if (empty($description)) $errors[] = "La description est requise.";
            if (empty($date_incident)) $errors[] = "La date de l'incident est requise.";
            if (empty($errors)) {
                $date_creation = new \DateTime();
                $cree_par = $_SESSION['user']['id'];

                // Insérer la sanction dans la base de données
                $sanction = new Sanction();
                $sanction->setEleve($eleve);
                $sanction->setProfesseur($professeur);
                $sanction->setMotif($motif);
                $sanction->setDescription($description);
                $sanction->setDateIncident(new \DateTime($date_incident));
                $sanction->setDateCreation($date_creation);
                $sanction->setCreePar($cree_par);

                $this->entityManager->persist($sanction);
                $this->entityManager->flush();

                error_log('Sanction created successfully');
                $this->redirect('/');
                return;
            }
        } catch (\Exception $e) {
            $errors[] = "Erreur: " . $e->getMessage();
            error_log('Error in createSanction: ' . $e->getMessage());
        }
    }

    // Récupérer les promotions et les étudiants
    try {
        $promotions = $this->entityManager->getRepository(Promotion::class)->findAll();
        $etudiants = $this->entityManager->getRepository(Etudiant::class)->findAll();
    } catch (\Exception $e) {
        $errors[] = "Erreur lors de la récupération des données: " . $e->getMessage();
        error_log('Error fetching data: ' . $e->getMessage());
    }

    // Rendre la vue avec les erreurs
    $this->render('create_sanction', [
        'errors' => $errors,
        'promotions' => $promotions ?? [],
        'etudiants' => $etudiants ?? []
       
    ]);
   
}
}