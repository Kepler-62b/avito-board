<?php
/*
 *  Copyright 2023.  Baks.dev <admin@baks.dev>
 *
 *  Permission is hereby granted, free of charge, to any person obtaining a copy
 *  of this software and associated documentation files (the "Software"), to deal
 *  in the Software without restriction, including without limitation the rights
 *  to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 *  copies of the Software, and to permit persons to whom the Software is furnished
 *  to do so, subject to the following conditions:
 *
 *  The above copyright notice and this permission notice shall be included in all
 *  copies or substantial portions of the Software.
 *
 *  THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 *  IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 *  FITNESS FOR A PARTICULAR PURPOSE AND NON INFRINGEMENT. IN NO EVENT SHALL THE
 *  AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 *  LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 *  OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 *  THE SOFTWARE.
 */

namespace BaksDev\Avito\Board\Controller\Admin\Mapper;

use BaksDev\Avito\Board\Entity\AvitoBoard;
use BaksDev\Avito\Board\UseCase\Mapper\BeforeNew\CategoryMapperDTO;
use BaksDev\Avito\Board\UseCase\Mapper\BeforeNew\CategoryMapperForm;
use BaksDev\Avito\Board\UseCase\Mapper\NewEdit\MapperDTO;
use BaksDev\Avito\Board\UseCase\Mapper\NewEdit\MapperForm;
use BaksDev\Avito\Board\UseCase\Mapper\NewEdit\MapperHandler;
use BaksDev\Core\Controller\AbstractController;
use BaksDev\Core\Listeners\Event\Security\RoleSecurity;
use BaksDev\Products\Category\Entity\CategoryProduct;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;

#[AsController]
#[RoleSecurity('ROLE_AVITO_BOARD_MAPPER_NEW')]
final class NewController extends AbstractController
{
    /**
     * Маппим локальную категорию с категорией Авито для создания формы сопоставления
     */
    #[Route('/admin/avito-board/mapper/before_new', name: 'admin.mapper.beforenew', methods: ['POST', 'GET'])]
    public function beforeNew(Request $request): Response
    {
        $categoryMapperDTO = new CategoryMapperDTO();

        $form = $this->createForm(CategoryMapperForm::class, $categoryMapperDTO, [
            'action' => $this->generateUrl('avito-board:admin.mapper.beforenew'),
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid() && $form->has('mapper_before_new'))
        {
            $this->refreshTokenForm($form);

            return $this->redirectToRoute(
                'avito-board:admin.mapper.new',
                [
                    'localCategory' => $categoryMapperDTO->localCategory,
                    'avitoCategory' => $categoryMapperDTO->avitoCategory->getProduct(),
                ]
            );
        }

        return $this->render(['form' => $form->createView()]);
    }

    /**
     * Создание формы сопоставления элементов категорий
     */
    #[Route(
        '/admin/avito-board/mapper/new/{localCategory}/{avitoCategory}',
        name: 'admin.mapper.new',
        requirements: ['localCategory' => '^[0-9a-f]{8}(?:-[0-9a-f]{4}){3}-[0-9a-f]{12}$'],
        methods: ['GET', 'POST',]
    )]
    public function new(
        Request                      $request,
        MapperHandler                $handler,
        #[MapEntity] CategoryProduct $localCategory,
        string                       $avitoCategory
    ): Response {
        $mapperDTO = new MapperDTO();
        $mapperDTO->setCategory($localCategory);
        $mapperDTO->setAvito($avitoCategory);

        $form = $this->createForm(MapperForm::class, $mapperDTO);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid() && $form->has('mapper_new'))
        {
            $this->refreshTokenForm($form);

            $result = $handler->handle($mapperDTO);

            if ($result instanceof AvitoBoard)
            {
                $this->addFlash('page.new', 'success.new', 'avito-board.admin');

                return $this->redirectToRoute('avito-board:admin.mapper.index');
            }

            $this->addFlash('page.new', 'danger.new', 'avito-board.admin', $result);

            return $this->redirectToReferer();
        }

        return $this->render(['form' => $form->createView()]);
    }
}
