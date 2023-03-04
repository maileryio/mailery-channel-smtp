<?php

namespace common\components\Mail\Message\Modifier;

use yii\web\UrlManager;
use common\models\Message as MessageModel;
use common\components\Mail;
use frontend\helpers\UrlHelper;

class Linker extends Mail\Message\Modifier
{

    /**
     * @var array
     */
    public $linkTypeRouteMap = [
        MessageModel\Link\Type::NAME_GATE => 'guest/gate',
        MessageModel\Link\Type::NAME_OPEN => 'guest/open',
        MessageModel\Link\Type::NAME_WEBVERION => 'guest/webversion',
        MessageModel\Link\Type::NAME_UNSUBSCRIBE => 'guest/unsubscribe',
    ];

    /**
     * @var UrlManager
     */
    protected $urlManager;

    /**
     * @param UrlManager $urlManager
     * @param array $config
     */
    public function __construct(UrlManager $urlManager, $config = [])
    {
        $this->urlManager = $urlManager;
        parent::__construct($config);
    }

    /**
     * @inheritdoc
     */
    public function modify(Mail\MessageInterface $message)
    {
        list($search, $replace) = $this->getReplaceLinks($message->getRecipient());

        $htmlBody = $message->getHtmlBody();
        if (!empty($htmlBody)) {
            $message->setHtmlBody(str_replace($search, $replace, $htmlBody));
        }

        $textBody = $message->getTextBody();
        if (!empty($textBody)) {
            $message->setTextBody(str_replace($search, $replace, $textBody));
        }

        $headers = $message->getHeaders();

        /* @var $header \Swift_Mime_Headers_UnstructuredHeader */
        if (($header = $headers->get('List-Unsubscribe')) !== null) {
            $header->setValue(str_replace($search, $replace, $header->getValue()));
        }
    }

    protected function getReplaceLinks(MessageModel\Recipient $recipient)
    {
        $links = $recipient->message->getLinks()
            ->joinWith(['type'])
            ->all();

        $result = [];
        foreach ($links as $link) {
            $maskedLink = $this->getMaskedLink($link, $recipient);

            if ($link->type->name === MessageModel\Link\Type::NAME_GATE) {
                $result['href="' . $link->link . '"'] = 'href="' . $maskedLink . '"';
                $result['href=\'' . $link->link . '\''] = 'href="' . $maskedLink . '"';
            } else {// handling self generating links
                $result[$link->link] = $maskedLink;
            }
        }

        return [array_keys($result), array_values($result)];
    }

    protected function getMaskedLink(MessageModel\Link $link, MessageModel\Recipient $recipient)
    {
        if (!isset($this->linkTypeRouteMap[$link->type->name])) {
            throw new \yii\base\Exception('Unknown link type.');
        }

        $route = $this->linkTypeRouteMap[$link->type->name];
        if ($route === false) {
            $maskedLink = $link->link;
        } else {
            $maskedLink = $this->urlManager->createAbsoluteUrl([
                $route,
                UrlHelper::PARAM_MESSAGE_LINK_ID => $link->id,
                UrlHelper::PARAM_MESSAGE_RECIPIENT_ID => $recipient->id,
            ]);
        }
        return $maskedLink;
    }

}
