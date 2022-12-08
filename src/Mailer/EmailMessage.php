<?php

namespace Mailery\Channel\Smtp\Mailer;

use Symfony\Component\Mime\Email;

class EmailMessage extends Email
{

    /**
     * @var array
     */
    private array $context;

    /**
     * @param array $context
     * @return self
     */
    public function context(array $context): self
    {
        $this->context = $context;
        return $this;
    }

    /**
     * @return array
     */
    public function getContext(): array
    {
        return $this->context;
    }

}
