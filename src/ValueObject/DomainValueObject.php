<?php

namespace Mailery\Channel\Email\ValueObject;

use Mailery\Brand\Entity\Brand;
use Mailery\Channel\Email\Form\DomainForm;

class DomainValueObject
{
    /**
     * @var string
     */
    private string $name;

    /**
     * @var Brand
     */
    private Brand $brand;

    /**
     * @param DomainForm $form
     * @return self
     */
    public static function fromForm(DomainForm $form): self
    {
        $new = new self();
        $new->name = $form->getAttributeValue('domain');

        return $new;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return Brand
     */
    public function getBrand(): Brand
    {
        return $this->brand;
    }

    /**
     * @param Brand $brand
     * @return self
     */
    public function withBrand(Brand $brand): self
    {
        $new = clone $this;
        $new->brand = $brand;

        return $new;
    }
}
