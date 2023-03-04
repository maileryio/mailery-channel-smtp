<?php

namespace common\components\Mail\Message\Modifier;

use common\components\Mail;

class Header extends Mail\Message\Modifier
{

    /**
     * @var array
     */
    public $textHeaders = [];
    
    /**
     * @inheritdoc
     */
    public function modify(Mail\MessageInterface $message)
    {
        foreach($this->textHeaders as $name => $value) {
            $message->getHeaders()->addTextHeader($name, $value);
        }
    }

}
