<?php

namespace common\components\Mail\Message\Modifier\Tag;

use common\components\Mail\Message;

class Date extends Message\Modifier\Tag
{

    protected function getCommonTags()
    {
        return [
            [
                '[currentdaynumber]',
                '[currentday]',
                '[currentmonthnumber]',
                '[currentmonth]',
                '[currentyear]'
            ],
            [
                strftime('%d', time()),
                strftime('%A', time()),
                strftime('%m', time()),
                strftime('%B', time()),
                strftime('%Y', time()),
            ]
        ];
    }
    
    protected function getSubjectTags($content)
    {
        return $this->getCommonTags();
    }
    
    protected function getHtmlBodyTags($content)
    {
        return $this->getCommonTags();
    }
    
    protected function getTextBodyTags($content)
    {
        return $this->getCommonTags();
    }

}
