<?php

namespace Mailery\Channel\Email\Factory;

use Mailery\Campaign\Entity\Recipient;
use Mailery\Channel\Factory\RecipientFactoryInterface;
use Mailery\Sender\Email\Model\SenderLabel;
use Mailery\Subscriber\Entity\Subscriber;

class RecipientFactory implements RecipientFactoryInterface
{

    /**
     * @inheritdoc
     */
    public function fromSubscriber(Subscriber $subscriber): Recipient
    {
        return (new Recipient())
            ->setSubscriber($subscriber)
            ->setName($subscriber->getName())
            ->setIdentifier($subscriber->getEmail());
    }

    /**
     * @inheritdoc
     */
    public function fromIdentificator(string $identificator): array
    {
        $recipients = [];
        foreach (SenderLabel::fromString($identificator) as $senderLabel) {
            /** @var SenderLabel $senderLabel */
            $recipients[] = (new Recipient())
                 ->setName($senderLabel->getName())
                 ->setIdentifier($senderLabel->getEmail());
        }

        return $recipients;
    }

}
