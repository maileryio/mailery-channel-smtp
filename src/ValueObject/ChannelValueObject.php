<?php

namespace Mailery\Channel\Email\ValueObject;

use Mailery\Channel\Email\Form\ChannelForm;

class ChannelValueObject
{
    /**
     * @var string|null
     */
    private ?string $name;

    /**
     * @param ChannelForm $form
     * @return self
     */
    public static function fromForm(ChannelForm $form): self
    {
        $new = new self();
        $new->name = $form->getName();

        return $new;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }
}
