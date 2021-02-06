<?php

namespace Mailery\Channel\Email;

use Mailery\Channel\ChannelInterface;

class EmailChannel implements ChannelInterface
{
    /**
     * @return string
     */
    public function getKey(): string
    {
        return 'email';
    }

    /**
     * @return string
     */
    public function getLabel(): string
    {
        return 'Email messaging';
    }
}
