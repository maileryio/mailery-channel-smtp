<?php

namespace Mailery\Channel\Smtp\Model;

use Mailery\Channel\Model\ChannelTypeInterface;
use Mailery\Campaign\Recipient\Model\RecipientIterator;
use Mailery\Channel\Handler\HandlerInterface;
use Mailery\Channel\Entity\Channel;

class SmtpChannelType implements ChannelTypeInterface
{
    /**
     * @param HandlerInterface $handler
     * @param RecipientIterator $recipientIterator
     */
    public function __construct(
        private HandlerInterface $handler,
        private RecipientIterator $recipientIterator
    ) {}

    /**
     * @inheritdoc
     */
    public function getName(): string
    {
        return self::class;
    }

    /**
     * @inheritdoc
     */
    public function getLabel(): string
    {
        return 'SMTP';
    }

    /**
     * @inheritdoc
     */
    public function getCreateLabel(): string
    {
        return 'SMTP';
    }

    /**
     * @inheritdoc
     */
    public function getCreateRouteName(): ?string
    {
        return '/channel/smtp/create';
    }

    /**
     * @inheritdoc
     */
    public function getCreateRouteParams(): array
    {
        return [];
    }

    /**
     * @inheritdoc
     */
    public function getHandler(): HandlerInterface
    {
        return $this->handler;
    }

    /**
     * @inheritdoc
     */
    public function getRecipientIterator(): RecipientIterator
    {
        return $this->recipientIterator;
    }

    /**
     * @inheritdoc
     */
    public function isEntitySameType(Channel $entity): bool
    {
        return $entity->getType() === $this->getName();
    }
}
