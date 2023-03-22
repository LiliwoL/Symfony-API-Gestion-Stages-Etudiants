<?php

namespace App\Controller;


use App\Service\ApiKeyService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Student;
use App\Repository\StudentRepository;
use DateTime;

class ApiStudentController extends AbstractController
{
    /**
     * #[Route('/api/student', name: 'api_student_index')]
     *
     * @Route(
     *     "/api/student",
     *     name="api_student_index",
     *     methods={"GET"}
     * )
     */
    public function index( StudentRepository $studentRepository, NormalizerInterface $normalizer, ApiKeyService $apiKeyService, Request $request): JsonResponse
    {
        // 1. Vérification de la sécurité
        $authorized = $apiKeyService->checkApiKey($request);

        // 2. Si autorisé, on continue
        if ($authorized){

            // Récupération de tous les étudiants
            $students = $studentRepository->findAll();

            // Sérialisation au format JSON
            $json = json_encode($students);
            // Ne va pas fonctionner car les attributs sont en private
            // Il faut normaliser!

            /**
             * Gestion de la circular reference
             */
            // On ne peut laisser ceci, car sinon on obtient l'erreur circular reference
            //$studentsNormalised = $normalizer->normalize($students);

            // Il faut alors gérer un contexte de sérialisation
            $studentsNormalised = $normalizer->normalize(
                $students,
                'json',
                [
                    'circular_reference_handler' => function ($object) {
                        return $object->getId();
                    }
                ]
            );

            // Debug in PostMan
            //dd($students, $json, $studentsNormalised);

            $output = $studentsNormalised;

        }else{

            $output = [ "Error" => "Unauthorized"];

        }


        // Renvoi d'une réponse au format JSON
        // TODO: améliorer la réponse de cette action de controller
        return $this->json(
            $output
        );
    }

    /**
     * @Route(
     *  "/api/student",
     *  name="app_api_student_add",
     *  methods={"POST"}
     * )
     */
    public function add( Request $request, EntityManagerInterface $entityManager ): JsonResponse
    {
        // On attend une requête au format json (Content-Type application/json)
        // TODO: Vérifier le Content-Type et le format
        // TODO: Vérifier que les données sont correctes et entières
        /*
            Payload a fournir dans la requête en POST
            {
                "name":"didier",
                "firstname":"favreau",
                "picture": "TEST image vide",
                "date_of_birth": "15-05-1981",
                "grade": "PROF"
            }
        */

        // Récupération du body (les informations) que l'on transforme depuis du JSON en tableau
        //dd($request->toArray());

        // On stocke le body de la requête dans $dataFromRequest
        // La méthode toArray() est possible car on est sûr que le body de la requête est du JSON
        $dataFromRequest = $request->toArray();


        // *******************************************************

        // Ici, les données ont été vérifiées, on peut créer une nouvelle instance de Student
        // TODO: Vérifier que les valeurs sont correctes et valides
        $student = new Student();
        $student->setName( $dataFromRequest['name'] );
        $student->setFirstname( $dataFromRequest['firstname'] );
        $student->setPicture ($dataFromRequest['picture'] );
        $student->setDateOfBirth( new DateTime($dataFromRequest['date_of_birth']) );
        $student->setGrade( $dataFromRequest['grade'] );
        

        // Insertion en base de l'instance student
        $entityManager->persist( $student );
        $entityManager->flush();

        return $this->json([
            'status' => 'Ajout OK'
        ]);
    }
}
