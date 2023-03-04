<?php

namespace common\components\Mail\Message\Modifier;

use common\components\Mail;

abstract class Tag extends Mail\Message\Modifier
{
    public function modify(Mail\MessageInterface $message)
    {
        $subject = $message->getSubject();
        list($search, $replace) = $this->getSubjectTags($subject);
        $message->setSubject(str_replace($search, $replace, $subject));
        
        $htmlBody = $message->getHtmlBody();
        if(!empty($htmlBody)) {
            list($search, $replace) = $this->getHtmlBodyTags($htmlBody);
            $message->setHtmlBody(str_replace($search, $replace, $htmlBody));
        }
        
        $textBody = $message->getTextBody();
        if(!empty($textBody)) {
            list($search, $replace) = $this->getTextBodyTags($textBody);
            $message->setTextBody(str_replace($search, $replace, $textBody));
        }
    }
    
    protected function getSubjectTags($content)
    {
        return [[], []];
    }
    
    protected function getHtmlBodyTags($content)
    {
        return [[], []];
    }
    
    protected function getTextBodyTags($content)
    {
        return [[], []];
    }

}
