<?php

namespace App\Controller;

use App\Entity\Etudiant;
use App\Entity\Promotion;
use Doctrine\ORM\EntityManager;

class EtudiantController extends AbstractController
{
    private EntityManager $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function import(): void
    {
        // Vérifier si l'utilisateur est connecté
        $this->requireAuth();

        $errors = [];
        $success = null;

        // Récupérer toutes les promotions pour le select
        $promotions = $this->entityManager
            ->getRepository(Promotion::class)
            ->findAll();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Vérifie si une promotion a été sélectionné
            if (empty($_POST['promotion_id'])) {
                $errors['promotion'] = "Veuillez sélectionner une promotion";
            }

            // Vérifie si un fichier a été mise
            if (empty($_FILES['csv_file']) || $_FILES['csv_file']['error'] !== UPLOAD_ERR_OK) {
                $errors['file'] = "Veuillez sélectionner un fichier CSV valide";
            }

            if (empty($errors)) {
                try {
                    // Récupere la promotion sélectionné
                    $promotion = $this->entityManager
                        ->getRepository(Promotion::class)
                        ->find($_POST['promotion_id']);

                    if (!$promotion) {
                        throw new \Exception("La promotion sélectionnée n'existe pas");
                    }

                    // Lire le fichier
                    $handle = fopen($_FILES['csv_file']['tmp_name'], 'r');
                    $importCount = 0;

                    // Ignorer la première ligne celle en haut ou il y a Nom et prenom
                    fgetcsv($handle);

                    // Lire chaque ligne
                    while (($data = fgetcsv($handle)) !== false) {
                        if (count($data) >= 2) {
                            $etudiant = new Etudiant();
                            $etudiant->setPrenom($data[0]);
                            $etudiant->setNom($data[1]);
                            $etudiant->setPromotion($promotion);

                            $this->entityManager->persist($etudiant);
                            $importCount++;
                        }
                    }

                    fclose($handle);
                    $this->entityManager->flush();

                    $success = sprintf("%d étudiants ont été importés avec succès", $importCount);
                } catch (\Exception $e) {
                    $errors['general'] = "L'importation n'a pas fonctionné : " . $e->getMessage();
                }
            }
        }

        $this->render('etudiant/import', [
            'promotions' => $promotions,
            'errors' => $errors,
            'success' => $success
        ]);
    }
}