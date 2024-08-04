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

namespace BaksDev\Avito\Board\Type\Mapper\Elements\PassengerTire;

use BaksDev\Avito\Board\Type\Mapper\Elements\AvitoBoardElementInterface;
use BaksDev\Avito\Board\Type\Mapper\Elements\AvitoBoardExtendElementInterface;

final class SpikesElement extends TireTypeElement implements AvitoBoardExtendElementInterface
{
    private const string ELEMENT = 'Spikes';

    private const string ELEMENT_LABEL = 'Шипы';

    protected ?string $base = null;

    public function setBaseData(AvitoBoardElementInterface $base): void
    {
        $this->base = $base->fetchData();
    }

    public function setData(string|array $mapper): void
    {
        $this->data = $mapper;
    }

    public function fetchData(): string
    {
        if(null === $this->base)
        {
            throw new \Exception('Не вызван метод setBaseData');
        }

        $extendData = match ($this->data)
        {
            'true' => 'шипованные',
            'false' => 'не шипованные',
            default => $this->data
        };

        if ($this->base === 'Летние' || $this->base === 'Всесезонные')
        {
            return $this->base;
        }
        else
        {
            return sprintf('%s %s', $this->base, $extendData);
        }
    }

    public function element(): string
    {
        return self::ELEMENT;
    }

    public function label(): string
    {
        return self::ELEMENT_LABEL;
    }
}