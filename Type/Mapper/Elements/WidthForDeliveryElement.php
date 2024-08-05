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

use BaksDev\Avito\Board\Type\Mapper\Products\AvitoProductInterface;

/**
 * Ширина товара (см), может использоваться для доставки.
 */
class WidthForDeliveryElement implements AvitoBoardElementInterface
{
    private const string WIDTH_FOR_DELIVERY_ELEMENT = 'WidthForDelivery';

    private const string WIDTH_FOR_DELIVERY_LABEL = 'Ширина товара (см)';

    public function __construct(
        private readonly ?AvitoProductInterface $product = null,
        protected null|string|false $data = false,
    ) {}

    public function isMapping(): false
    {
        return false;
    }

    public function isRequired(): false
    {
        return false;
    }

    public function isChoices(): false
    {
        return false;
    }

    public function getDefault(): null
    {
        return null;
    }

    public function getHelp(): null
    {
        return null;
    }

    public function setData(string|array $product): void
    {
        $this->data = $product['product_width_delivery'];
    }

    public function fetchData(): ?string
    {
        if (false === $this->data)
        {
            throw new \Exception('Не вызван метод setData');
        }

        return $this->data;
    }

    public function element(): string
    {
        return self::WIDTH_FOR_DELIVERY_ELEMENT;
    }

    public function label(): string
    {
        return self::WIDTH_FOR_DELIVERY_LABEL;
    }

    public function getProduct(): null
    {
        return $this->product;
    }
}
