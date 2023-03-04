<?php

namespace common\components\Mail\Message\Modifier\Tag\Link;

use frontend\helpers\UrlHelper;
use common\models\Message;
use common\components\Mail;

class Webversion extends Mail\Message\Modifier\Tag\Link
{
    /**
     * @var Message\Link
     */
    protected $link;

    /**
     * @var Message
     */
    protected $message;

    public function modify(Mail\MessageInterface $message)
    {
        $this->message = $message;
        parent::modify($message);
    }

    protected function getHtmlBodyTags($content)
    {
        return [
            [
                '<webversion',
                '</webversion>',
                '[webversion]',
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
                '[webversion]',
            ],
            [
                $this->getLink()->link,
            ]
        ];
    }

    /**
     * @return LinkModel
     */
    protected function getLink()
    {
        return $this->memoize->memoizeCallable(
            self::class . __METHOD__,
            function() {
                $link = $this->urlManager->createAbsoluteUrl([
                    'message/webversion',
                    UrlHelper::PARAM_MESSAGE_ID => $this->message->getMessage()->id,
                    UrlHelper::PARAM_BRAND_ID => $this->message->getMessage()->brand_id
                ]);
                /* @var $linkType Message\Link\Type */
                $linkType = $this->getLinkType(Message\Link\Type::NAME_WEBVERION);

                return $this->saveLink($this->message->getMessage(), $linkType, $link);
            }
        );
    }
}