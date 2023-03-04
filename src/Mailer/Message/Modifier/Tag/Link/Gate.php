<?php

namespace common\components\Mail\Message\Modifier\Tag\Link;

use Yii;
use yii\validators\UrlValidator;
use yii\helpers\ArrayHelper;
use common\models\Message;
use common\components\Mail;

class Gate extends Mail\Message\Modifier\Tag\Link
{

    /**
     * @var Message\Link
     */
    protected $link;

    /**
     * @var Message\Link\Type
     */
    protected $linkType;

    /**
     * @inheritdoc
     */
    public function modify(Mail\MessageInterface $message)
    {
        $this->memoize->memoizeCallable(
            self::class . __METHOD__,
            function() use($message) {
                return $this->doModify($message);
            }
        );
    }

    /**
     * @param \common\components\Mail\MessageInterface $message
     */
    protected function doModify(Mail\MessageInterface $message)
    {
        /* @var $validator UrlValidator */
        $validator = Yii::createObject(UrlValidator::class);
        /* @var $messageModel Message */
        $messageModel = $message->getMessage();
        /* @var $linkType Message\Link\Type */
        $linkType = $this->getLinkType(Message\Link\Type::NAME_GATE);

        $links = [];
        $replaces = [];
        $htmlBody = $message->getHtmlBody();
        $existing = ArrayHelper::getColumn($messageModel->links, 'link');

        //extract all links from HTML
        preg_match_all('/<a\s[^>]*href=(\"??)([^\" >]*?)\\1[^>]*>(.*)<\/a>/siU', $htmlBody, $matches, PREG_PATTERN_ORDER);
        $matches = array_unique($matches[2]);

        foreach ($matches as $match) {
            $matchLink = html_entity_decode($match);
            if ($matchLink !== $match) {
                $replaces['href="' . $match . '"'] = 'href="' . $matchLink . '"';
                $replaces['href=\'' . $match . '\''] = 'href="' . $matchLink . '"';
            }

            if (!in_array($matchLink, $existing) && $validator->validate($matchLink)) {
                array_push($links, [$messageModel->id, $linkType->id, $matchLink, sprintf('%u', crc32($matchLink)), time(), time()]);
                array_push($existing, $matchLink);
            }
        }

        if (!empty($replaces)) {
            $message->setHtmlBody(str_replace(array_keys($replaces), array_values($replaces), $htmlBody));
        }

        if (!empty($links)) {
            Message\Link::getDb()->createCommand()
                ->batchInsert(Message\Link::tableName(), ['message_id', 'message_link_type_id', 'link', 'link_crc', 'created_at', 'updated_at'], $links)
                ->execute();
            $messageModel->refresh();
        }
    }

}
