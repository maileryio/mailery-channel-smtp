<?php

namespace Mailery\Channel\Smtp\Service;

use Cycle\ORM\EntityManagerInterface;
use Mailery\Channel\Smtp\Entity\SmtpChannel;
use Mailery\Channel\Smtp\ValueObject\ChannelValueObject;
use Yiisoft\Yii\Cycle\Data\Writer\EntityWriter;

class ChannelCrudService
{
    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {}

    /**
     * @param ChannelValueObject $valueObject
     * @return SmtpChannel
     */
    public function create(ChannelValueObject $valueObject): SmtpChannel
    {
        $channel = (new SmtpChannel())
            ->setName($valueObject->getName())
            ->setDescription($valueObject->getDescription())
        ;

        (new EntityWriter($this->entityManager))->write([$channel]);

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
            ->setDescription($valueObject->getDescription())
        ;

        (new EntityWriter($this->entityManager))->write([$channel]);

        return $channel;
    }

    /**
     * @param SmtpChannel $channel
     * @return bool
     */
    public function delete(SmtpChannel $channel): bool
    {
        (new EntityWriter($this->entityManager))->delete([$channel]);

        return true;
    }
}
