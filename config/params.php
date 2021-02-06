<?php

use Mailery\Channel\Email\EmailChannel;
use Yiisoft\Factory\Definitions\Reference;
use Yiisoft\Router\UrlGeneratorInterface;

return [
    'maileryio/mailery-channel' => [
        'channels' => [
            Reference::to(EmailChannel::class),
        ],
    ],

    'maileryio/mailery-brand' => [
        'settings-menu' => [
            'items' => [
                'aws-settings' => [
                    'label' => static function () {
                        return 'AWS Credentials';
                    },
                    'url' => static function (UrlGeneratorInterface $urlGenerator) {
                        return $urlGenerator->generate('/brand/default/index');
                    },
                ],
            ],
        ],
    ],
];
