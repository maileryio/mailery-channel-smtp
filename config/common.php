<?php

use Psr\Container\ContainerInterface;
use Mailery\Channel\Smtp\Model\SmtpChannelType;
use Mailery\Campaign\Recipient\Model\RecipientIterator;
use Mailery\Campaign\Recipient\Factory\RecipientFactory;
use Mailery\Channel\Smtp\Factory\IdentificatorFactory;
use Mailery\Channel\Smtp\Handler\ChannelHandler;

return [
    SmtpChannelType::class =>  static function (ContainerInterface $container) {
        return new SmtpChannelType(
            $container->get(ChannelHandler::class),
            new RecipientIterator(new RecipientFactory()),
            new IdentificatorFactory()
        );
    },
];
