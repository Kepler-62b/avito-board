<?php

declare(strict_types=1);

namespace BaksDev\Avito\Board\Entity\Modify;

use BaksDev\Avito\Board\Entity\Event\AvitoBoardCategoriesEvent;
use BaksDev\Core\Entity\EntityEvent;
use BaksDev\Core\Type\Ip\IpAddress;
use BaksDev\Core\Type\Modify\Modify\ModifyActionNew;
use BaksDev\Core\Type\Modify\Modify\ModifyActionUpdate;
use BaksDev\Core\Type\Modify\ModifyAction;
use BaksDev\Users\User\Entity\User;
use BaksDev\Users\User\Type\Id\UserUid;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use InvalidArgumentException;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
#[ORM\Table(name: 'avito_product_setting_modify')]
#[ORM\Index(columns: ['action'])]
class AvitoBoardCategoriesModify extends EntityEvent
{
    /** ID события */
    #[Assert\NotBlank]
    #[Assert\Uuid]
    #[ORM\Id]
    #[ORM\OneToOne(targetEntity: AvitoBoardCategoriesEvent::class, inversedBy: 'modify')]
    #[ORM\JoinColumn(name: 'event', referencedColumnName: 'id')]
    private AvitoBoardCategoriesEvent $event;

    /** Модификатор */
    #[Assert\NotBlank]
    #[ORM\Column(type: ModifyAction::TYPE)]
    private ModifyAction $action;

    /** Дата */
    #[Assert\NotBlank]
    #[ORM\Column(name: 'mod_date', type: Types::DATETIME_IMMUTABLE)]
    private DateTimeImmutable $modDate;

    /** ID пользователя  */
    #[ORM\Column(type: UserUid::TYPE, nullable: true)]
    private ?UserUid $user = null;

    /** Ip адрес */
    #[Assert\NotBlank]
    #[ORM\Column(type: IpAddress::TYPE)]
    private IpAddress $ipAddress;

    /** User-agent */
    #[Assert\NotBlank]
    #[ORM\Column(type: Types::TEXT)]
    private string $userAgent;


    public function __construct(AvitoBoardCategoriesEvent $event)
    {
        $this->event = $event;
        $this->modDate = new DateTimeImmutable();
        $this->ipAddress = new IpAddress('127.0.0.1');
        $this->userAgent = 'console';
        $this->action = new ModifyAction(ModifyActionNew::class);
    }

    public function __clone(): void
    {
        $this->modDate = new DateTimeImmutable();
        $this->action = new ModifyAction(ModifyActionUpdate::class);
        $this->ipAddress = new IpAddress('127.0.0.1');
        $this->userAgent = 'console';
    }

    public function __toString(): string
    {
        return (string)$this->event;
    }

    public function getDto($dto): mixed
    {
        if ($dto instanceof AvitoBoardCategoriesModifyInterface)
        {
            return parent::getDto($dto);
        }

        throw new InvalidArgumentException(sprintf('Class %s interface error', $dto::class));
    }

    public function setEntity($dto): mixed
    {
        if ($dto instanceof AvitoBoardCategoriesModifyInterface)
        {
            return parent::setEntity($dto);
        }

        throw new InvalidArgumentException(sprintf('Class %s interface error', $dto::class));
    }


    public function upModifyAgent(IpAddress $ip, string $agent): void
    {
        $this->ipAddress = $ip;
        $this->userAgent = $agent;
        $this->modDate = new DateTimeImmutable();
    }

    public function setUser(UserUid|User|null $user): void
    {
        $this->user = $user instanceof User ? $user->getId() : $user;
    }

    public function equals(mixed $action): bool
    {
        return $this->action->equals($action);
    }
}
