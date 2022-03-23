<?php

use Psr\Container\ContainerInterface;
use Mailery\Channel\Email\Model\ChannelType;
use Mailery\Campaign\Recipient\Model\RecipientIterator;
use Mailery\Channel\Email\Factory\RecipientFactory;
use Mailery\Channel\Email\Handler\ChannelHandler;

return [
    ChannelType::class =>  static function (ContainerInterface $container) {
        return new ChannelType(
            $container->get(ChannelHandler::class),
            new RecipientIterator(new RecipientFactory())
        );
    },
];
