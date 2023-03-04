<?php

namespace common\components\Mail\Message\Modifier\Tag;

use common\components\Mail;

class Common extends Mail\Message\Modifier\Tag
{

    /**
     * @var \common\models\Message\Recipient
     */
    protected $recipient;

    /**
     * @param \common\components\Mail\MessageInterface $message
     */
    public function modify(Mail\MessageInterface $message)
    {
        /* @var $recipient \common\models\Message\Recipient */
        $this->recipient = $message->getRecipient();
        parent::modify($message);
    }

    /**
     * @return array
     */
    protected function getCommonTags()
    {
        return ['[Email]', $this->recipient->subscriber->email];
    }

    /**
     * @return array
     */
    protected function getSubjectTags($content)
    {
        return $this->getCommonTags();
    }

    /**
     * @return array
     */
    protected function getHtmlBodyTags($content)
    {
        return $this->getCommonTags();
    }

    /**
     * @return array
     */
    protected function getTextBodyTags($content)
    {
        return $this->getCommonTags();
    }

}
