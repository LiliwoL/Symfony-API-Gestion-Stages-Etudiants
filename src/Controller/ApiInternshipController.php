<?php

namespace App\Controller;

use App\Entity\Internship;
use App\Repository\CompanyRepository;
use App\Repository\InternshipRepository;
use App\Repository\StudentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class ApiInternshipController extends AbstractController
{
    /**
     * #[Route('/api/internship', name: 'api_internship_index')]
     *
     * @Route(
     *     "/api/internship",
     *     name="api_internship_index",
     *     methods={"GET"}
     * )
     */
    public function index( InternshipRepository $internshipRepository, NormalizerInterface $normalizer): JsonResponse
    {
        // Récupération de tous les stages
        $internships = $internshipRepository->findAll();

        // Sérialisation au format JSON
        $json = json_encode($internships);
        // Ne va pas fonctionner, car les attributs sont en private.
        // Il faut normaliser !

        /**
         * Gestion de la circular reference
         */
        // On ne peut laisser ceci, car sinon on obtient l'erreur circular reference
        //$internshipsNormalised = $normalizer->normalize($internships);

        // Il faut alors gérer un contexte de sérialisation
        $internshipsNormalised = $normalizer->normalize(
            $internships,
            'json',
            [
                'circular_reference_handler' => function ($object) {
                    return $object->getId();
                }
            ]
        );

        // Debug in PostMan
        dd($internships, $json, $internshipsNormalised);

        // Renvoi d'une réponse au format JSON
        // TODO: améliorer la réponse de cette action de controller
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/ApiStudentController.php',
        ]);
    }

    /**
     * @Route(
     *  "/api/internship",
     *  name="app_api_internship_add",
     *  methods={"POST"}
     * )
     */
    public function add( Request $request, EntityManagerInterface $entityManager, StudentRepository $studentRepository, CompanyRepository $companyRepository): JsonResponse
    {
        // On attend une requête au format json (Content-Type application/json)
        // TODO: Vérifier le Content-Type et le format
        // TODO: Vérifier que les données sont correctes et entières
        /*
            Payload a fournir dans la requête en POST
            {
                "idStudent": 101,
                "idCompany": 101,
                "startDate": "07-03-2023",
                "endDate": "31-08-2023"
            }
        */

        // Récupération du body (les informations) que l'on transforme depuis du JSON en tableau
        //dd($request->toArray());

        // On stocke le body de la requête dans $dataFromRequest
        // La méthode toArray() est possible, car on est sûr que le body de la requête est du JSON
        $dataFromRequest = $request->toArray();

        // Création des données
        $student = $studentRepository->find( $dataFromRequest['idStudent'] );
        $company = $companyRepository->find( $dataFromRequest['idCompany'] );
        $startDate = new \DateTime($dataFromRequest['startDate'] );
        $endDate = new \DateTime($dataFromRequest['endDate'] );


        // *******************************************************

        // Ici, les données ont été vérifiées, on peut créer une nouvelle instance de Internship
        // TODO: Vérifier que les valeurs sont correctes et valides
        $internship = new Internship();
        $internship->setIdStudent( $student );
        $internship->setIdCompany( $company );
        $internship->setStartDate( $startDate );
        $internship->setEndDate( $endDate );




        // Insertion en base de l'instance student
        $entityManager->persist($internship);
        $entityManager->flush();

        return $this->json([
            'status' => 'Ajout du nouveau stage OK'
        ]);
    }

    /**
     * @Route(
     *  "/api/internship/student/{id}",
     *  name="app_api_internship_show_student",
     *  methods={"GET"}
     * )
     * @return void
     */
    public function showInternshipsByStudent( InternshipRepository $internshipRepository, NormalizerInterface $normalizer, int $id )
    {
        // Recherche des stages pour un étudiant donné
        $internships = $internshipRepository->findBy(
            ['idStudent' => $id]
        );

        /**
         * Gestion de la circular reference
         */
        // On ne peut laisser ceci, car sinon on obtient l'erreur circular reference
        //$internshipsNormalised = $normalizer->normalize($internships);

        // Il faut alors gérer un contexte de sérialisation
        $internshipsNormalised = $normalizer->normalize(
            $internships,
            'json',
            [
                'circular_reference_handler' => function ($object) {
                    return $object->getId();
                }
            ]
        );

        // Debug in PostMan
        dd($internships, $internshipsNormalised);
    }
}
