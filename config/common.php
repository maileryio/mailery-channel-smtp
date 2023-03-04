<?php

use Mailery\Channel\Smtp\Model\SmtpChannelType;
use Mailery\Campaign\Recipient\Model\RecipientIterator;
use Mailery\Campaign\Recipient\Factory\RecipientFactory;
use Mailery\Channel\Smtp\Factory\IdentificatorFactory;
use Mailery\Channel\Smtp\Handler\ChannelHandler;
use Mailery\Subscriber\Repository\SubscriberRepository;
use Mailery\Channel\Smtp\Mailer\MailerFactory;
use Mailery\Channel\Smtp\Mailer\MessageFactory;
use Mailery\Channel\Smtp\Mailer\SmtpDsnFactory;
use Mailery\Channel\Smtp\Mailer\Message\EmailMessage;
use Yiisoft\Definitions\DynamicReference;
use Yiisoft\Definitions\Reference;
use Symfony\Component\Mailer\Transport\Smtp\EsmtpTransportFactory;

return [
    SmtpChannelType::class => static function (ChannelHandler $handler, SubscriberRepository $subscriberRepo) {
        return new SmtpChannelType(
            $handler,
            new RecipientIterator(new RecipientFactory(), $subscriberRepo),
            new IdentificatorFactory()
        );
    },

    ChannelHandler::class => [
        'class' => ChannelHandler::class,
        '__construct()' => [
            'mailerFactory' => DynamicReference::to([
                'class' => MailerFactory::class,
                '__construct()' => [
                    'dsnFactory' => Reference::to(SmtpDsnFactory::class),
                    'transportFactory' => Reference::to(EsmtpTransportFactory::class),
                ],
            ]),
            'messageFactory' => DynamicReference::to([
                'class' => MessageFactory::class,
                '__construct()' => [
                    'message' => Reference::to(EmailMessage::class),
                ],
            ]),
        ],
    ],
];
