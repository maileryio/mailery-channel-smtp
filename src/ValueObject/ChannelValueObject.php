<?php

namespace Mailery\Channel\Smtp\ValueObject;

use Mailery\Channel\Smtp\Form\ChannelForm;

class ChannelValueObject
{
    /**
     * @var string|null
     */
    private ?string $name = null;

    /**
     * @var string|null
     */
    private ?string $description = null;

    /**
     * @param ChannelForm $form
     * @return self
     */
    public static function fromForm(ChannelForm $form): self
    {
        $new = new self();
        $new->name = $form->getName();
        $new->description = $form->getDescription();

        return $new;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }
}
