<?php

namespace Mailery\Channel\Smtp\Form;

use Yiisoft\Form\FormModel;
use Mailery\Channel\Smtp\Entity\SmtpChannel;
use Yiisoft\Validator\Rule\Required;
use Yiisoft\Validator\Rule\HasLength;

class ChannelForm extends FormModel
{
    /**
     * @var string|null
     */
    private ?string $name = null;

    /**
     * @param SmtpChannel $channel
     * @return self
     */
    public function withEntity(SmtpChannel $channel): self
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
