<?php
// src/Controller/SecurityController.php
namespace App\Controller;

class SecurityController
{
    public function deconnexion()
    {
        // Logique de déconnexion
        session_start();
        session_destroy();

        // Rediriger vers la page de connexion ou d'accueil
        header('Location: /connexion');
        exit();
    }
}