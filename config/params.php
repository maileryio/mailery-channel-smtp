<?php

use Yiisoft\Definitions\Reference;
use Mailery\Channel\Smtp\Model\ChannelType;
use Mailery\Channel\Smtp\Entity\SmtpChannel;

return [
    'maileryio/mailery-activity-log' => [
        'entity-groups' => [
            'channel' => [
                'entities' => [
                    SmtpChannel::class,
                ],
            ],
        ],
    ],

    'maileryio/mailery-channel' => [
        'types' => [
            Reference::to(ChannelType::class),
        ],
    ],

    'yiisoft/yii-cycle' => [
        'entity-paths' => [
            '@vendor/maileryio/mailery-channel-smtp/src/Entity',
        ],
    ],
];
