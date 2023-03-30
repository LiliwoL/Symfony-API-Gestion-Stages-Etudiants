<?php

namespace App\Controller;

use App\Entity\Company;
use App\Repository\CompanyRepository;
use App\Service\ApiKeyService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class ApiCompanyController extends AbstractController
{
    /**
     * #[Route('/api/company', name: 'api_company_index')]
     *
     * @Route(
     *     "/api/company",
     *     name="api_company_index",
     *     methods={"GET"}
     * )
     * @throws ExceptionInterface
     */
    public function index( CompanyRepository $companyRepository, NormalizerInterface $normalizer, ApiKeyService $apiKeyService, Request $request): JsonResponse
    {
        // 1. Vérification de la sécurité
        $authorized = $apiKeyService->checkApiKey($request);

        // 2. Si autorisé, on continue
        if ($authorized)
        {
            // Récupération de toutes les entreprises
            $companies = $companyRepository->findAll();

            // Sérialisation au format JSON
            $json = json_encode($companies);
            // Ne va pas fonctionner, car les attributs sont en private.
            // Il faut normaliser !

            /**
             * Gestion de la circular reference
             */
            // On ne peut laisser ceci, car sinon on obtient l'erreur circular reference
            //$companiesNormalized = $normalizer->normalize($companies);

            // Il faut alors gérer un contexte de sérialisation
            $companiesNormalized = $normalizer->normalize(
                $companies,
                'json',
                [
                    'circular_reference_handler' => function ($object) {
                        return $object->getId();
                    }
                ]
            );

            // Debug in PostMan
            //dd($companies, $json, $companiesNormalized);

            $output = $companiesNormalized;

        }else{

            $output = [ "Error" => "Unauthorized"];

        }

        // Renvoi d'une réponse au format JSON
        return $this->json(
            $output
        );
    }

    /**
     * @Route(
     *  "/api/company",
     *  name="app_api_company_add",
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
                "name":"Lycée Fénelon",
                "street":"rue Massiou",
                "zipcode": "17000",
                "city": "La Rochelle",
                "website": "http://www.fenelon-notredame.com"
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
        $company = new Company();
        $company->setName( $dataFromRequest['name'] );
        $company->setStreet( $dataFromRequest['street'] );
        $company->setZipcode( $dataFromRequest['zipcode'] );
        $company->setCity( $dataFromRequest['city'] );
        $company->setWebsite( $dataFromRequest['website'] );



        // Insertion en base de l'instance student
        $entityManager->persist($company);
        $entityManager->flush();

        return $this->json([
            'status' => 'Ajout de la nouvelle entreprise OK'
        ]);
    }
}
