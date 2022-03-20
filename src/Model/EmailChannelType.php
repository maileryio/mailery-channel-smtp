<?php

namespace Mailery\Channel\Email\Model;

use Mailery\Channel\Model\ChannelTypeInterface;
use Mailery\Channel\Email\Entity\EmailChannel;
use Mailery\Campaign\Recipient\Model\RecipientIterator;
use Mailery\Channel\Handler\HandlerInterface;
use Mailery\Channel\Entity\Channel;

class EmailChannelType implements ChannelTypeInterface
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
    public function getLabel(): string
    {
        return 'Email messaging';
    }

    /**
     * @inheritdoc
     */
    public function getCreateLabel(): string
    {
        return 'Email messaging';
    }

    /**
     * @inheritdoc
     */
    public function getCreateRouteName(): ?string
    {
        return '/channel/email/create';
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
    public function isEntitySameType(Channel $entity): bool
    {
        return $entity instanceof EmailChannel;
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
}
