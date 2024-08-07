<?php

declare(strict_types=1);

namespace BaksDev\Avito\Board\Controller\Admin\Mapper;

use BaksDev\Avito\Board\Repository\Mapper\AllMapperSettings\AllMapperSettingsInterface;
use BaksDev\Core\Controller\AbstractController;
use BaksDev\Core\Form\Search\SearchDTO;
use BaksDev\Core\Form\Search\SearchForm;
use BaksDev\Core\Listeners\Event\Security\RoleSecurity;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;

#[AsController]
#[RoleSecurity('ROLE_AVITO_BOARD_MAPPER_INDEX')]
final class IndexController extends AbstractController
{
    #[Route('/admin/avito-board/mapper/categories/{page<\d+>}', name: 'admin.mapper.index', methods: ['GET', 'POST'])]
    public function index(Request $request, AllMapperSettingsInterface $allMapperSettings, int $page = 0): Response
    {

        $search = new SearchDTO();

        $searchForm = $this->createForm(SearchForm::class, $search, [
            'action' => $this->generateUrl('avito-board:admin.mapper.index')
        ]);

        $searchForm->handleRequest($request);

        $mapperSetting = $allMapperSettings->findAll();

        return $this->render(
            [
                'query' => $mapperSetting,
                'search' => $searchForm->createView(),
            ],
        );
    }
}
