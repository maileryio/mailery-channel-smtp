<?php

use Mailery\Channel\Email\EmailChannel;
use Yiisoft\Factory\Definitions\Reference;
use Yiisoft\Router\UrlGeneratorInterface;

return [
    'yiisoft/yii-cycle' => [
        'annotated-entity-paths' => [
            '@vendor/maileryio/mailery-channel-email/src/Entity',
        ],
    ],

    'maileryio/mailery-channel' => [
        'channels' => [
            Reference::to(EmailChannel::class),
        ],
    ],

    'maileryio/mailery-menu-sidebar' => [
        'items' => [
            'settings' => [
                'activeRouteNames' => [
                    '/brand/settings/domain',
                ],
            ],
        ],
    ],

    'maileryio/mailery-brand' => [
        'settings-menu' => [
            'items' => [
                'domain' => [
                    'label' => static function () {
                        return 'Domain verification';
                    },
                    'url' => static function (UrlGeneratorInterface $urlGenerator) {
                        return $urlGenerator->generate('/brand/settings/domain');
                    },
                ],
            ],
        ],
    ],
];
