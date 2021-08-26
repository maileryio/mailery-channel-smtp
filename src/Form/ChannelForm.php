<?php

namespace Mailery\Channel\Email\Form;

use Yiisoft\Form\FormModel;
use Mailery\Channel\Email\Entity\EmailChannel;
use Yiisoft\Form\HtmlOptions\RequiredHtmlOptions;
use Yiisoft\Validator\Rule\Required;
use Yiisoft\Form\HtmlOptions\HasLengthHtmlOptions;
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
                new RequiredHtmlOptions(Required::rule()),
                new HasLengthHtmlOptions(HasLength::rule()->max(255)),
            ],
        ];
    }
}
