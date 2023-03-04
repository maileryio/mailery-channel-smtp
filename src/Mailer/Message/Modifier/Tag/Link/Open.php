<?php

namespace common\components\Mail\Message\Modifier\Tag\Link;

use frontend\helpers\UrlHelper;
use common\models\Message;
use common\components\Mail;

class Open extends Mail\Message\Modifier\Tag\Link
{

    /**
     * @var Message\Link
     */
    protected $link;

    /**
     * @var Mail\MessageInterface
     */
    protected $message;

    /**
     * @inheritdoc
     */
    public function modify(Mail\MessageInterface $message)
    {
        $this->message = $message;
        parent::modify($message);
    }

    protected function getHtmlBodyTags($content)
    {
        return [
            [
                '</body>',
            ],
            [
                '<img width="1px" height="1px" src="' . $this->getLink()->link . '" alt=""/></body>',
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
                    'message/open',
                    UrlHelper::PARAM_MESSAGE_ID => $this->message->getMessage()->id,
                    UrlHelper::PARAM_BRAND_ID => $this->message->getMessage()->brand_id
                ]);
                /* @var $linkType Message\Link\Type */
                $linkType = $this->getLinkType(Message\Link\Type::NAME_OPEN);

                return $this->saveLink($this->message->getMessage(), $linkType, $link);
            }
        );
    }

}
