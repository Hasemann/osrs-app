<?php declare(strict_types=1);

namespace App\Controller;

use App\ApiResource\OsrsApiService;
use Exception;
use JsonException;
use Saloon\Exceptions\Request\FatalRequestException;
use Saloon\Exceptions\Request\RequestException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    public function __construct(protected OsrsApiService $osrsApiService)
    {

    }

    /**
     * @return Response
     */
    #[Route('/', name: 'home')]
    public function index(): Response
    {
        return $this->render('/base.html.twig');
    }

    /**
     * @throws FatalRequestException
     * @throws RequestException
     * @throws JsonException
     */
    #[Route(
        '/api/osrs/{username}',
        name: 'api_osrs_stats',
        methods: ['GET']
    )]
    public function stats(string $username): JsonResponse
    {
        try {
            $playerStats = $this->osrsApiService->getFormattedPlayerStats($username);
        }catch (Exception $exception){
            return new JsonResponse(['errorMessage' => 'user not found'], Response::HTTP_NOT_FOUND);
        }

        return $this->json($playerStats);
    }
}
