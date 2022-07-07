<?php

namespace Mailery\Channel\Smtp\Handler;

use Mailery\Campaign\Entity\Sendout;
use Mailery\Campaign\Entity\Recipient;
use Mailery\Channel\Handler\HandlerInterface;

class ChannelHandler implements HandlerInterface
{

    /**
     * @inheritdoc
     */
    public function handle(Sendout $sendout, Recipient $recipient): bool
    {
        throw new \Exception('Not implemented');
    }

}
