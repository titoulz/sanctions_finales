<?php

namespace App\Controller;

use App\Entity\Etudiant;
use App\Entity\Promotion;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use League\Csv\Reader;
use League\Csv\Statement;

session_start();

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
            // Vérifie si une promotion a été sélectionnée
            if (empty($_POST['promotion_id'])) {
                $errors[] = 'Veuillez sélectionner une promotion.';
            }

            // Vérifie si un fichier a été téléchargé
            if (empty($_FILES['file']['tmp_name'])) {
                $errors[] = 'Veuillez télécharger un fichier CSV.';
            }

            if (empty($errors)) {
                $promotionId = $_POST['promotion_id'];
                $promotion = $this->entityManager->getRepository(Promotion::class)->find($promotionId);

                if ($promotion) {
                    try {
                        // Lire le fichier CSV
                        $csv = Reader::createFromPath($_FILES['file']['tmp_name'], 'r');
                        $csv->setHeaderOffset(0);

                        $stmt = (new Statement())->process($csv);

                        foreach ($stmt as $record) {
                            // Valider les données
                            if (empty($record['Prénom']) || empty($record['Nom'])) {
                                throw new \Exception('Chaque ligne du CSV doit contenir des valeurs pour "Prénom" et "Nom".');
                            }

                            $etudiant = new Etudiant();
                            $etudiant->setPrenom($record['Prénom']);
                            $etudiant->setNom($record['Nom']);
                            $etudiant->setPromotion($promotion);

                            $this->entityManager->persist($etudiant);
                        }

                        $this->entityManager->flush();
                        $success = 'Les étudiants ont été importés avec succès.';
                    } catch (\Exception $e) {
                        $errors[] = 'Erreur lors du traitement du fichier CSV : ' . $e->getMessage();
                    }
                } else {
                    $errors[] = 'Promotion non trouvée.';
                }
            }
        }

        $this->render('import', [
            'promotions' => $promotions,
            'errors' => $errors,
            'success' => $success,
        ]);
    }
}
