<?php
/*
 *  Copyright 2024.  Baks.dev <admin@baks.dev>
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

namespace BaksDev\Avito\Board\Type\Mapper\Elements;

use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag('baks.avito.board.elements')]
interface AvitoBoardElementInterface
{
    /**
     * @return true если элемент будет участвовать в маппинге и дынные будут браться из БД (маппинга)
     * @return false если элемент не участвует в маппинге и его не нужно показывать в форме
     */
    public function isMapping(): bool;

    public function isRequired(): bool;

    public function isChoices(): bool;

    /** Получает название элемента */
    public function element(): string;

    /** Получает описание элемента */
    public function label(): string;

    /**
     * Для сохранения в базу
     *
     * @return null если данные берутся не из класса,
     * а из БД (поля продукта) по соответствующему ключу методом ->productData(string|array $data)
     *
     * @return string|array если данные берутся статически, из описания класса
     */
    public function getDefault(): null|int|string|array;

    /**
     * Для выдачи в фиде
     */
    public function getData(string|array $data = null): null|int|string|array;

    public function help(): null|string;

    public function product(): mixed;
}