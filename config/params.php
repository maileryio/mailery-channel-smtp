<?php

use Mailery\Channel\Email\Model\EmailChannelType;
use Yiisoft\Factory\Definitions\Reference;

return [
    'maileryio/mailery-channel' => [
        'types' => [
            Reference::to(EmailChannelType::class),
        ],
    ],

    'yiisoft/yii-cycle' => [
        'annotated-entity-paths' => [
            '@vendor/maileryio/mailery-channel-email/src/Entity',
        ],
    ],
];
