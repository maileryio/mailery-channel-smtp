<?php

namespace Mailery\Channel\Email\Service;

use Cycle\ORM\ORMInterface;
use Mailery\Channel\Email\Entity\Domain;
use Mailery\Channel\Email\ValueObject\DomainValueObject;
use Yiisoft\Yii\Cycle\Data\Writer\EntityWriter;

class DomainCrudService
{
    /**
     * @var ORMInterface
     */
    private ORMInterface $orm;

    /**
     * @param ORMInterface $orm
     */
    public function __construct(ORMInterface $orm)
    {
        $this->orm = $orm;
    }

    /**
     * @param DomainValueObject $valueObject
     * @return Domain
     */
    public function create(DomainValueObject $valueObject): Domain
    {
        $domain = (new Domain())
            ->setDomain($valueObject->getDomain())
            ->setBrand($valueObject->getBrand())
        ;

        (new EntityWriter($this->orm))->write([$domain]);

        return $domain;
    }

    /**
     * @param Domain $domain
     * @param DomainValueObject $valueObject
     * @return Domain
     */
    public function update(Domain $domain, DomainValueObject $valueObject): Domain
    {
        $domain = $domain
            ->setDomain($valueObject->getDomain())
        ;

        (new EntityWriter($this->orm))->write([$domain]);

        return $domain;
    }

    /**
     * @param Domain $domain
     * @return void
     */
    public function delete(Domain $domain): void
    {
        (new EntityWriter($this->orm))->delete([$domain]);
    }
}
