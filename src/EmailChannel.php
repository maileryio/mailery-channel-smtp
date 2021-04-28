<?php

namespace Mailery\Channel\Email;

use Mailery\Channel\ChannelInterface;

class EmailChannel implements ChannelInterface
{
    /**
     * @return string
     */
    public function getName(): string
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
