<?php declare(strict_types=1);

namespace App\Controller;

use App\ApiResource\OsrsApiService;
use JsonException;
use Saloon\Exceptions\Request\FatalRequestException;
use Saloon\Exceptions\Request\RequestException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    public function __construct(protected OsrsApiService $osrsApiService)
    {

    }

    /**
     * @throws FatalRequestException
     * @throws RequestException
     * @throws JsonException
     */
    #[Route('/', name: 'home')]
    public function index(): Response
    {
        $playerStats = $this->osrsApiService->getFormattedPlayerStats('brokedevelop');
        return $this->render('/base.html.twig', ['playerStats' => $playerStats]);
    }
}
