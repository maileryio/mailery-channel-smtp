<?php

namespace Mailery\Channel\Smtp\Service;

use Cycle\ORM\ORMInterface;
use Mailery\Channel\Smtp\Entity\SmtpChannel;
use Mailery\Channel\Smtp\ValueObject\ChannelValueObject;
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
     * @return SmtpChannel
     */
    public function create(ChannelValueObject $valueObject): SmtpChannel
    {
        $channel = (new SmtpChannel())
            ->setName($valueObject->getName())
        ;

        (new EntityWriter($this->orm))->write([$channel]);

        return $channel;
    }

    /**
     * @param SmtpChannel $channel
     * @param ChannelValueObject $valueObject
     * @return SmtpChannel
     */
    public function update(SmtpChannel $channel, ChannelValueObject $valueObject): SmtpChannel
    {
        $channel = $channel
            ->setName($valueObject->getName())
        ;

        (new EntityWriter($this->orm))->write([$channel]);

        return $channel;
    }

    /**
     * @param SmtpChannel $channel
     * @return bool
     */
    public function delete(SmtpChannel $channel): bool
    {
        (new EntityWriter($this->orm))->delete([$channel]);

        return true;
    }
}
