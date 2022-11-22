<?php

namespace Mailery\Channel\Smtp\Mailer;

use Mailery\Channel\Entity\Channel;
use Symfony\Component\Mailer\Transport\Dsn;

interface DsnFactoryInterface
{

    public function create(Channel $channel): Dsn;

}
