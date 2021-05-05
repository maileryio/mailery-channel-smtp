<?php

namespace Mailery\Channel\Email\Model;

use Mailery\Channel\Model\ChannelTypeInterface;
use Mailery\Channel\Email\Entity\EmailChannel;

class EmailChannelType implements ChannelTypeInterface
{
    /**
     * @return string
     */
    public function getLabel(): string
    {
        return 'Email messaging';
    }

    public function getCreateLabel(): string
    {
        return 'Email messaging';
    }

    public function getCreateRouteName(): ?string
    {
        return '/channel/email/create';
    }

    public function getCreateRouteParams(): array
    {
        return [];
    }

    /**
     * @param object $entity
     * @return bool
     */
    public function isEntitySameType(object $entity): bool
    {
        return $entity instanceof EmailChannel;
    }
}
