<?php

namespace Mailery\Channel\Smtp\Mailer;

use Mailery\Channel\Entity\Channel;
use Mailery\Channel\Smtp\Mailer\DsnFactoryInterface;
use Symfony\Component\Mailer\Transport\Dsn;

class SmtpDsnFactory implements DsnFactoryInterface
{

    /**
     * @param Channel $channel
     * @return Dsn
     */
    public function create(Channel $channel): Dsn
    {
        throw new \Exception('Not implemented');
    }

}
