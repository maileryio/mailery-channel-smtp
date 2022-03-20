<?php

namespace Mailery\Channel\Email\Service;

use Cycle\ORM\ORMInterface;
use Mailery\Channel\Email\Entity\EmailChannel;
use Mailery\Channel\Email\ValueObject\ChannelValueObject;
use Yiisoft\Yii\Cycle\Data\Writer\EntityWriter;

class ChannelCrudService
{
    /**
     * @param ORMInterface $orm
     */
    public function __construct(
        private ORMInterface $orm
    ) {}

    /**
     * @param ChannelValueObject $valueObject
     * @return EmailChannel
     */
    public function create(ChannelValueObject $valueObject): EmailChannel
    {
        $channel = (new EmailChannel())
            ->setName($valueObject->getName())
        ;

        (new EntityWriter($this->orm))->write([$channel]);

        return $channel;
    }

    /**
     * @param EmailChannel $channel
     * @param ChannelValueObject $valueObject
     * @return EmailChannel
     */
    public function update(EmailChannel $channel, ChannelValueObject $valueObject): EmailChannel
    {
        $channel = $channel
            ->setName($valueObject->getName())
        ;

        (new EntityWriter($this->orm))->write([$channel]);

        return $channel;
    }

    /**
     * @param EmailChannel $channel
     * @return bool
     */
    public function delete(EmailChannel $channel): bool
    {
        (new EntityWriter($this->orm))->delete([$channel]);

        return true;
    }
}
