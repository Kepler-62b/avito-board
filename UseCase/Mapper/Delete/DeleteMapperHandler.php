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

declare(strict_types=1);

namespace BaksDev\Avito\Board\UseCase\Mapper\Delete;

use BaksDev\Avito\Board\Entity\AvitoBoard;
use BaksDev\Avito\Board\Entity\Event\AvitoBoardEvent;
use BaksDev\Avito\Board\Messenger\Mapper\AvitoBoardMessage;
use BaksDev\Core\Entity\AbstractHandler;
use DomainException;

final class DeleteMapperHandler extends AbstractHandler
{
    public function handle(DeleteMapperDTO $command): string|AvitoBoard
    {
        /**
         * Валидация DTO
         */
        $this->validatorCollection->add($command);

        $this->main = new AvitoBoard($command->getCategory());
        $this->event = new AvitoBoardEvent();

        try
        {
            $this->preRemove($command);
        }
        catch (DomainException $errorUniqid)
        {
            return $errorUniqid->getMessage();
        }

        /** Валидация всех объектов */
        if ($this->validatorCollection->isInvalid())
        {
            return $this->validatorCollection->getErrorUniqid();
        }

        $this->entityManager->flush();

        /** Отправляем сообщение в шину */
        $this->messageDispatch->dispatch(
            message: new AvitoBoardMessage($this->main->getId(), $this->main->getEvent(), $command->getEvent()),
            transport: 'avito-board'
        );

        return $this->main;
    }
}
