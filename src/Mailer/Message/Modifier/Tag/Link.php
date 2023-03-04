<?php

namespace common\components\Mail\Message\Modifier\Tag;

use DominionEnterprises\Memoize\Memory;
use yii\web\UrlManager;
use common\models\Message;
use common\components\Mail;

abstract class Link extends Mail\Message\Modifier\Tag
{

    /**
     * @var Memory
     */
    protected $memoize;

    /**
     * @var UrlManager
     */
    protected $urlManager;

    /**
     * @var Message\Link\Type[]
     */
    protected static $linkTypes;

    /**
     * @param UrlManager $urlManager
     * @param array $config
     */
    public function __construct(Memory $memoize, UrlManager $urlManager, $config = [])
    {
        $this->memoize = $memoize;
        $this->urlManager = $urlManager;
        parent::__construct($config);
    }

    /**
     * @param \common\models\Message $message
     * @param Message\Link\Type $linkType
     * @param string $link
     * @return \common\models\Message\Link
     */
    protected function saveLink(Message $message, Message\Link\Type $linkType, $link)
    {
        $linkModel = Message\Link::findOne([
            'link' => $link,
            'link_crc' => sprintf('%u', crc32($link)),
            'message_id' => $message->id
        ]);

        if($linkModel === null) {
            $linkModel = new Message\Link([
                'link' => $link,
                'link_crc' => sprintf('%u', crc32($link)),
                'message_link_type_id' => $linkType->id
            ]);

            $linkModel->link(
                'message',
                $message,
                [
                    'created_at' => time(),
                    'updated_at' => time(),
                ]
            );
        }

        return $linkModel;
    }

    /**
     * @return Message\Link\Type
     */
    protected function getLinkType($type)
    {
        if (self::$linkTypes === null) {
            self::$linkTypes = Message\Link\Type::find()->indexBy('name')->all();
        }
        return isset(self::$linkTypes[$type]) ? self::$linkTypes[$type] : null;
    }

}
