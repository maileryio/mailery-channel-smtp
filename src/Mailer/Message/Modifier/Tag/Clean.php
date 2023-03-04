<?php

namespace common\components\Mail\Message\Modifier\Tag;

use common\components\Mail;

class Clean extends Mail\Message\Modifier\Tag
{

    protected function getSubjectTags($content)
    {
        return $this->resolveReplaces($content);
    }

    protected function getHtmlBodyTags($content)
    {
        return $this->resolveReplaces($content);
    }

    protected function getTextBodyTags($content)
    {
        return $this->resolveReplaces($content);
    }

    /**
     * @param string $content
     * @return array
     */
    protected function resolveReplaces($content)
    {
        $tags = [];

        preg_match_all('/<webversion(.*)<\/webversion>|\[webversion\]|<unsubscribe(.*)<\/unsubscribe>|\[unsubscribe\]|\[Email\]/i', $content, $matches, PREG_PATTERN_ORDER);
        for ($i = 0; $i < count($matches[0]); $i++) {
            $tags[] = [
                'tag' => $matches[0][$i],
                'fallback' => ''
            ];
        }

        return [
            array_column($tags, 'tag'),
            array_column($tags, 'fallback'),
        ];
    }

}
