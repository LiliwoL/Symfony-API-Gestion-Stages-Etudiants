<?php

namespace App\Controller;

use App\Repository\StudentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

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
    public function index( StudentRepository $studentRepository, NormalizerInterface $normalizer): JsonResponse
    {
        // Récupération de tous les étudiants
        $students = $studentRepository->findAll();

        // Sérialisation au format JSON
        $json = json_encode($students);
        // Ne va pas fonctionner car les attributs sont en private
        // Il faut normaliser!

        $studentsNormalised = $normalizer->normalize($students);

        // Debug in PostMan
        dd($students, $json, $studentsNormalised);

        // Renvoi d'une réponse au format JSON
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/ApiStudentController.php',
        ]);
    }
}
