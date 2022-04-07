<?php

namespace Mailery\Channel\Smtp\ValueObject;

use Mailery\Channel\Smtp\Form\ChannelForm;

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
