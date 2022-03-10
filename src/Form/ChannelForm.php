<?php

namespace Mailery\Channel\Email\Form;

use Yiisoft\Form\FormModel;
use Mailery\Channel\Email\Entity\EmailChannel;
use Yiisoft\Validator\Rule\Required;
use Yiisoft\Validator\Rule\HasLength;

class ChannelForm extends FormModel
{
    /**
     * @var string|null
     */
    private ?string $name = null;

    /**
     * @param EmailChannel $channel
     * @return self
     */
    public function withEntity(EmailChannel $channel): self
    {
        $new = clone $this;
        $new->name = $channel->getName();

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
     * @return array
     */
    public function getAttributeLabels(): array
    {
        return [
            'name' => 'Name',
        ];
    }

    /**
     * @return array
     */
    public function getRules(): array
    {
        return [
            'name' => [
                Required::rule(),
                HasLength::rule()->min(3)->max(255),
            ],
        ];
    }
}
