<?php

namespace Mailery\Channel\Email\Model;

use Mailery\Channel\Model\ChannelTypeInterface;
use Mailery\Channel\Email\Entity\EmailChannel;
use Mailery\Campaign\Recipient\Model\RecipientIterator;
use Mailery\Channel\Handler\HandlerInterface;

class EmailChannelType implements ChannelTypeInterface
{
    /**
     * @var HandlerInterface
     */
    private HandlerInterface $handler;

    /**
     * @var RecipientIterator $recipientIterator
     */
    private RecipientIterator $recipientIterator;

    /**
     * @param HandlerInterface $handler
     * @param RecipientIterator $recipientIterator
     */
    public function __construct(HandlerInterface $handler, RecipientIterator $recipientIterator)
    {
        $this->handler = $handler->withChannelType($this);
        $this->recipientIterator = $recipientIterator;
    }

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
    public function isEntitySameType(object $entity): bool
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
