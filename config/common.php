<?php

use Mailery\Channel\Email\Repository\DomainRepository;
use Psr\Container\ContainerInterface;
use Cycle\ORM\ORMInterface;
use Mailery\Channel\Email\Entity\Domain;

return [
    DomainRepository::class => static function (ContainerInterface $container) {
        return $container
            ->get(ORMInterface::class)
            ->getRepository(Domain::class);
    },
];
