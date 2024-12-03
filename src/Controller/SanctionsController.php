<?php

namespace App\Controller;

use App\UserStory\CreateAccount;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

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
    }

    public function index(): void
    {
        $this->render('sanctions/index', [
            'sanctions' => $this->sanctions
        ]);
    }

    public function inscription(): void
    {
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
            }
        }
        $this->render('Sanctions/inscription', ['erreurs' => $erreurs]);
    }

    public function connexion(): void
    {
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
            }
        }

        $this->render('Sanctions/connexion', ['erreurs' => $erreurs]);
    }

    public function deconnexion(): void
    {
        // Détruire la session pour déconnecter l'utilisateur
        session_start();
        session_destroy();
        $this->redirect('/');
    }
}
