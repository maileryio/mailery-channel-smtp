<?php

use Psr\Container\ContainerInterface;
use Mailery\Channel\Smtp\Model\SmtpChannelType;
use Mailery\Campaign\Recipient\Model\RecipientIterator;
use Mailery\Campaign\Recipient\Factory\RecipientFactory;
use Mailery\Channel\Smtp\Factory\IdentificatorFactory;
use Mailery\Channel\Smtp\Handler\ChannelHandler;
use Mailery\Subscriber\Repository\SubscriberRepository;
use Mailery\Channel\Amazon\Ses\Messenger\Middleware\ChannelTransportMiddleware as AmazonSesChannelTransportMiddleware;
use Pheanstalk\Contract\PheanstalkInterface;
use Symfony\Component\Mailer\Messenger\SendEmailMessage;
use Symfony\Component\Messenger\MessageBus;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Middleware\SendMessageMiddleware;
use Symfony\Component\Messenger\Middleware\HandleMessageMiddleware;
use Symfony\Component\Messenger\Transport\Sender\SendersLocator;
use Symfony\Component\Messenger\Transport\Sender\SendersLocatorInterface;
use Symfony\Component\Messenger\Handler\HandlersLocator;
use Symfony\Component\Messenger\Handler\HandlersLocatorInterface;
use Symfony\Component\Messenger\Bridge\Beanstalkd\Transport\BeanstalkdSender;
use Symfony\Component\Messenger\Bridge\Beanstalkd\Transport\Connection;

return [
    SmtpChannelType::class =>  static function (ContainerInterface $container) {
        return new SmtpChannelType(
            $container->get(ChannelHandler::class),
            new RecipientIterator(
                new RecipientFactory(),
                $container->get(SubscriberRepository::class)
            ),
            new IdentificatorFactory()
        );
    },

    BeanstalkdSender::class => static function (ContainerInterface $container) {
        return new BeanstalkdSender(
            new Connection(
                [],
                $container->get(PheanstalkInterface::class)
            )
        );
    },

    MessageBusInterface::class => static function (ContainerInterface $container) {
        return new MessageBus([
            $container->get(AmazonSesChannelTransportMiddleware::class),
            $container->get(HandleMessageMiddleware::class),
            $container->get(SendMessageMiddleware::class),
        ]);
    },

    SendersLocatorInterface::class => static function (ContainerInterface $container) {
        return new SendersLocator(
            [
                SendEmailMessage::class => [BeanstalkdSender::class],
            ],
            $container
        );
    },

    HandlersLocatorInterface::class => static function (ContainerInterface $container) {
        return new HandlersLocator(
            [
                SendEmailMessage::class => [
                    function (SendEmailMessage $message) {
//                        var_dump('SendEmailMessage handler');
                    }
                ],
            ]
        );
    },
];
