<?php
/*
 *  Copyright 2022.  Baks.dev <admin@baks.dev>
 *
 *  Licensed under the Apache License, Version 2.0 (the "License");
 *  you may not use this file except in compliance with the License.
 *  You may obtain a copy of the License at
 *
 *  http://www.apache.org/licenses/LICENSE-2.0
 *
 *  Unless required by applicable law or agreed to in writing, software
 *  distributed under the License is distributed on an "AS IS" BASIS,
 *  WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 *  See the License for the specific language governing permissions and
 *   limitations under the License.
 *
 */

namespace BaksDev\Avito\Board\Controller\Admin\Mapper\Tests;

use BaksDev\Avito\Board\Entity\AvitoBoard;
use BaksDev\Avito\Board\Entity\Event\AvitoBoardEvent;
use BaksDev\Avito\Board\Type\Doctrine\Event\AvitoBoardEventUid;
use BaksDev\Users\User\Tests\TestUserAccount;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\Attribute\When;

/**
 * @group avito-board
 * @group avito-board-controllers
 * @group avito-board-controllers-edit
 */
#[When(env: 'test')]
final class EditControllerTest extends WebTestCase
{
    private const string URL = '/admin/avito-board/mapper/edit/%s';

    private const string ROLE = 'ROLE_AVITO_BOARD_MAPPER_EDIT';

    private static ?AvitoBoardEventUid $eventId = null;

    public static function setUpBeforeClass(): void
    {
        $container = self::getContainer();

        /** @var EntityManagerInterface $em */
        $em = $container->get(EntityManagerInterface::class);

        $avitoBoard = $em->getRepository(AvitoBoard::class)->findOneBy([]);
        self::$eventId = $em->getRepository(AvitoBoardEvent::class)->find($avitoBoard->getEvent())->getId();

        $em->clear();
    }

    /** Доступ по роли */
    public function testRoleSuccessful(): void
    {
        // Получаем одно из событий
        $eventId = self::$eventId;

        if ($eventId)
        {
            self::ensureKernelShutdown();
            $client = static::createClient();

            foreach (TestUserAccount::getDevice() as $device)
            {
                $client->setServerParameter('HTTP_USER_AGENT', $device);

                $usr = TestUserAccount::getModer(self::ROLE);

                $client->loginUser($usr, 'user');
                $client->request('GET', sprintf(self::URL, $eventId));

                self::assertResponseIsSuccessful();
            }
        }

        self::assertTrue(true);
    }

    /** Доступ по роли ROLE_ADMIN */
    public function testRoleAdminSuccessful(): void
    {
        // Получаем одно из событий
        $eventId = self::$eventId;


        if ($eventId)
        {
            self::ensureKernelShutdown();
            $client = static::createClient();

            foreach (TestUserAccount::getDevice() as $device)
            {
                $client->setServerParameter('HTTP_USER_AGENT', $device);
                $usr = TestUserAccount::getAdmin();

                $client->loginUser($usr, 'user');
                $client->request('GET', sprintf(self::URL, $eventId));

                self::assertResponseIsSuccessful();
            }
        }

        self::assertTrue(true);
    }

    /** Доступ по роли ROLE_USER */
    public function testRoleUserDeny(): void
    {
        // Получаем одно из событий
        $eventId = self::$eventId;

        if ($eventId)
        {
            self::ensureKernelShutdown();
            $client = static::createClient();

            foreach (TestUserAccount::getDevice() as $device)
            {
                $client->setServerParameter('HTTP_USER_AGENT', $device);

                $usr = TestUserAccount::getUsr();
                $client->loginUser($usr, 'user');
                $client->request('GET', sprintf(self::URL, $eventId));

                self::assertResponseStatusCodeSame(403);
            }
        }

        self::assertTrue(true);
    }

    /** Доступ без роли */
    public function testGuestFiled(): void
    {
        // Получаем одно из событий
        $eventId = self::$eventId;

        if ($eventId)
        {
            self::ensureKernelShutdown();
            $client = static::createClient();

            foreach (TestUserAccount::getDevice() as $device)
            {
                $client->setServerParameter('HTTP_USER_AGENT', $device);

                $client->request('GET', sprintf(self::URL, $eventId));

                // Full authentication is required to access this resource
                self::assertResponseStatusCodeSame(401);
            }
        }

        self::assertTrue(true);
    }
}
