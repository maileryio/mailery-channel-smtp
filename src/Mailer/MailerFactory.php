<?php

namespace Mailery\Channel\Smtp\Mailer;

use Mailery\Channel\Entity\Channel;
use Mailery\Channel\Smtp\Mailer\DsnFactoryInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mailer\Transport\TransportFactoryInterface;

class MailerFactory
{

    /**
     * @param DsnFactoryInterface $dsnFactory
     * @param TransportFactoryInterface $transportFactory
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(
        private DsnFactoryInterface $dsnFactory,
        private TransportFactoryInterface $transportFactory,
        private EventDispatcherInterface $eventDispatcher
    ) {}

    /**
     * @param Channel $channel
     * @return MailerInterface
     */
    public function create(Channel $channel): MailerInterface
    {
        return new Mailer(
            $this->transportFactory->create(
                $this->dsnFactory->create($channel)
            ),
            null,
            $this->eventDispatcher
        );
    }

}
