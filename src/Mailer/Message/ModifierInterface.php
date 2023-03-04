<?php

namespace Mailery\Channel\Smtp\Mailer\Message;

interface MiddlewareInterface
{

    /**
     * @param WrappedTemplate
     */
    public function modify(WrappedTemplate $template);

}
