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

declare(strict_types=1);

namespace BaksDev\Avito\Board\Type\Mapper\Elements;

/**
 * Доступные способы доставки.
 *
 * Подробнее о работе с доставкой https://support.avito.ru/sections/62?articleId=2271
 *
 * Одно или несколько значений
 * @TODO тестирую - пока без implements AvitoBoardElementInterface
 */
class DeliveryElement
{
    public const string ELEMENT = 'Delivery';

    private const string LABEL = 'Доступные способы доставки';

    public function isMapping(): bool
    {
        return true;
    }

    public function isRequired(): bool
    {
        return true;
    }

    public function isChoices(): bool
    {
        return true;
    }

    public function getDefault(): array
    {
        return [
            'Выключена',
            'ПВЗ',
            'Курьер',
            'Постамат',
            'Свой курьер',
            'Свой партнер СДЭК',
            'Свой партнер Деловые Линии',
            'Свой партнер DPD',
            'Свой партнер ПЭК',
            'Свой партнер Почта России',
            'Свой партнер Boxberry',
            'Свой партнер СДЭК курьер',
        ];
    }

    public function getHelp(): null
    {
        return null;
    }

    public function getProduct(): null
    {
        return null;
    }

    /** Одно из дефолтных значений либо пользовательский ввод */
    public function fetchData(string|array $data = null): string
    {
        // @TODO пока не понимаю, откуда брать информацию о доставке
        return sprintf('<Option>%s</Option>', $data['product_delivery']);
    }

    public function element(): string
    {
        return self::ELEMENT;
    }

    public function label(): string
    {
        return self::LABEL;
    }
}
