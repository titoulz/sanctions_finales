<?php

namespace App\UserStory;

use App\Entity\User;
use Doctrine\ORM\EntityManager;

require_once __DIR__.'/../../config/bootstrap.php';

class CreateAccount
{
    protected EntityManager $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function execute(string $nom, string $prenom, string $email, string $password, string $confirmPassword) : User
    {
        // Vérifier que des données sont présentes
        if (empty($nom) || empty($prenom) || empty($email) || empty($password) || empty($confirmPassword)) {
            throw new \Exception("Veuillez remplir tous les champs.");
        }

        // Vérifier si l'email est valide
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new \Exception("L'adresse email n'est pas valide.");
        }

        // Vérifier si le mot de passe est sécurisé
        if (strlen($password) < 8 ) {
            throw new \Exception("Le mot de passe doit comporter au moins 8 caractères, .");
        }

        // Vérifier l'unicité de l'email
        $existingUser = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $email]);
        if ($existingUser) {
            throw new \Exception("L'email est déjà utilisé.");
        }

        // Vérifier la correspondance des mots de passe
        if ($password !== $confirmPassword) {
            throw new \Exception("Les mots de passe ne correspondent pas.");
        }

        // Hasher le mot de passe
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        // Créer une instance de la classe User avec les données
        $user = new User();
        $user->setNom($nom);
        $user->setPrenom($prenom);
        $user->setEmail($email);
        $user->setPassword($hashedPassword);

        // Persister l'instance en utilisant l'EntityManager
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        // Retourner l'utilisateur créé
        return $user;
    }
}

// Traitement pour les requêtes POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $entityManager = require __DIR__.'/../../config/bootstrap.php';
    $createAccount = new CreateAccount($entityManager);

    try {
        $createAccount->execute(
            $_POST['nom'],
            $_POST['prenom'],
            $_POST['email'],
            $_POST['password'],
            $_POST['confirmPassword']
        );
        echo "Utilisateur créé avec succès.";
    } catch (\Exception $e) {
        echo "Erreur : " . $e->getMessage();
    }
}
