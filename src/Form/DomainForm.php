<?php

namespace Mailery\Channel\Email\Form;

use Yiisoft\Form\FormModel;

class DomainForm extends FormModel
{
    /**
     * @var string|null
     */
    private ?string $domain = null;

    /**
     * @return array
     */
    public function attributeLabels(): array
    {
        return [
            'domain' => 'Domain',
        ];
    }

    /**
     * @return array
     */
    public function attributeHints(): array
    {
        return [
            'domain' => 'Add a sending domain you wish to validate. A few simple DNS configurations are required in order for your emails to be sent directly from your custom domains.',
        ];
    }

    /**
     * @return string
     */
    public function formName(): string
    {
        return 'DomainForm';
    }
}
