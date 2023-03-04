<?php

namespace common\components\Mail\Message\Modifier;

use common\components\Mail;

class Recipient extends Mail\Message\Modifier
{

    /**
     * @param \common\components\Mail\MessageInterface $message
     */
    public function modify(Mail\MessageInterface $message)
    {
        /* @var $recipient \common\models\Message\Recipient */
        $recipient = $message->getRecipient();

        if (empty($recipient->subscriber->attributeStorage->name)) {
            $message->setTo([$recipient->subscriber->email]);
        } else {
            $message->setTo([$recipient->subscriber->email => $recipient->subscriber->attributeStorage->name]);
        }
    }

}
