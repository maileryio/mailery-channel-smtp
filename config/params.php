<?php

use Yiisoft\Definitions\Reference;
use Mailery\Channel\Email\Model\ChannelType;
use Mailery\Channel\Email\Entity\EmailChannel;

return [
    'maileryio/mailery-activity-log' => [
        'entity-groups' => [
            'channel' => [
                'entities' => [
                    EmailChannel::class,
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
            '@vendor/maileryio/mailery-channel-email/src/Entity',
        ],
    ],
];
