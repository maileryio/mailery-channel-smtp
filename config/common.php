<?php

use Psr\Container\ContainerInterface;
use Mailery\Channel\Email\Model\EmailChannelType;
use Mailery\Campaign\Recipient\Model\RecipientIterator;
use Mailery\Channel\Email\Factory\RecipientFactory;
use Mailery\Channel\Email\Handler\EmailChannelHandler;

return [
    EmailChannelType::class =>  static function (ContainerInterface $container) {
        return new EmailChannelType(
            $container->get(EmailChannelHandler::class),
            new RecipientIterator(new RecipientFactory())
        );
    },
];
