<?php

namespace Mailery\Channel\Smtp\Model;

use Mailery\Campaign\Recipient\Model\IdentificatorInterface as Identificator;

class EmailIdentificator implements Identificator
{

    /**
     * @param string $email
     * @param string|null $name
     */
    public function __construct(
        private string $email,
        private ?string $name = null
    ) {}

    /**
     * @return string
     */
    public function __toString()
    {
        if (empty($this->name)) {
            return $this->email;
        }

        return sprintf('%s <%s>', $this->name, $this->email);
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getIdentificator(): string
    {
        return $this->email;
    }

}
