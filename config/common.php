<?php

use Psr\Container\ContainerInterface;
use Mailery\Channel\Smtp\Model\ChannelType;
use Mailery\Campaign\Recipient\Model\RecipientIterator;
use Mailery\Channel\Smtp\Factory\RecipientFactory;
use Mailery\Channel\Smtp\Handler\ChannelHandler;

return [
    ChannelType::class =>  static function (ContainerInterface $container) {
        return new ChannelType(
            $container->get(ChannelHandler::class),
            new RecipientIterator(new RecipientFactory())
        );
    },
];
