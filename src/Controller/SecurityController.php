<?php
// src/Controller/SecurityController.php
namespace App\Controller;

class SecurityController
{
    public function deconnexion()
    {
        // Vérifier si une session est déjà active
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // Logique de déconnexion
        session_destroy();

        // Rediriger vers la page de connexion ou d'accueil
        header('Location: /connexion');
        exit();
    }
}