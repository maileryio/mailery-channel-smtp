<?php

namespace Mailery\Channel\Smtp\Form;

use Yiisoft\Form\FormModel;
use Yiisoft\Validator\Rule\Required;
use Yiisoft\Validator\Rule\HasLength;
use Mailery\Channel\Entity\Channel;

class ChannelForm extends FormModel
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
     * @var Channel|null
     */
    private ?Channel $entity = null;

    /**
     * @param Channel $entity
     * @return self
     */
    public function withEntity(Channel $entity): self
    {
        $new = clone $this;
        $new->entity = $entity;
        $new->name = $entity->getName();
        $new->description = $entity->getDescription();

        return $new;
    }

    /**
     * @return bool
     */
    public function hasEntity(): bool
    {
        return $this->entity !== null;
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

    /**
     * @return array
     */
    public function getAttributeLabels(): array
    {
        return [
            'name' => 'Name',
            'description' => 'Description (optional)',
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
