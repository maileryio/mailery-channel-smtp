<?php

namespace common\components\Mail\Message\Modifier\Tag\Link;

use frontend\helpers\UrlHelper;
use common\models\Message;
use common\components\Mail;

class Unsubscribe extends Mail\Message\Modifier\Tag\Link
{

    /**
     * @var Message\Link
     */
    protected $link;

    /**
     * @var Mail\MessageInterface
     */
    protected $message;

    public function modify(Mail\MessageInterface $message)
    {
        $this->message = $message;
        parent::modify($message);

        $headers = $message->getHeaders();
        $headers->addTextHeader('List-Unsubscribe', '<' . $this->getLink()->link . '>');
    }

    protected function getHtmlBodyTags($content)
    {
        return [
            [
                '<unsubscribe',
                '</unsubscribe>',
                '[unsubscribe]',
            ],
            [
                '<a href="' . $this->getLink()->link . '" ',
                '</a>',
                $this->getLink()->link,
            ]
        ];
    }

    protected function getTextBodyTags($content)
    {
        return [
            [
                '[unsubscribe]',
            ],
            [
                $this->getLink()->link,
            ]
        ];
    }

    /**
     * @return Message\Link
     */
    protected function getLink()
    {
        return $this->memoize->memoizeCallable(
            self::class . __METHOD__,
            function() {
                $link = $this->urlManager->createAbsoluteUrl([
                    'message/unsubscribe',
                    UrlHelper::PARAM_MESSAGE_ID => $this->message->getMessage()->id,
                    UrlHelper::PARAM_BRAND_ID => $this->message->getMessage()->brand_id
                ]);
                /* @var $linkType Message\Link\Type */
                $linkType = $this->getLinkType(Message\Link\Type::NAME_UNSUBSCRIBE);

                return $this->saveLink($this->message->getMessage(), $linkType, $link);
            }
        );
    }

}
