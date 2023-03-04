<?php

namespace common\components\Mail\Message\Modifier\Tag;

use common\components\Mail;

class Fallback extends Mail\Message\Modifier\Tag
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
    protected function getTags($content)
    {
        preg_match_all('/(\[[a-zA-Z0-9!#%^&*()+=$@._\-\:|\/?<>~`"\'\s]+,\s*fallback=[a-zA-Z0-9!,#%^&*()+=$@._\-\:|\/?<>~`"\'\s]*\])/i', $content, $matchesTag, PREG_PATTERN_ORDER);
        preg_match_all('/\[([a-zA-Z0-9!#%^&*()+=$@._\-\:|\/?<>~`"\'\s]+),\s*fallback=/i', $content, $matchesField, PREG_PATTERN_ORDER);
        preg_match_all('/,\s*fallback=([a-zA-Z0-9!,#%^&*()+=$@._\-\:|\/?<>~`"\'\s]*)\]/i', $content, $matchesFallback, PREG_PATTERN_ORDER);

        $tags = [];
        for ($i = 0; $i < count($matchesTag[1]); $i++) {
            $tags[] = [
                'tag' => $matchesTag[1][$i],
                'field' => $matchesField[1][$i],
                'fallback' => $matchesFallback[1][$i]
            ];
        }

        return $tags;
    }

    /**
     * @param string $content
     * @return array
     */
    protected function resolveReplaces($content)
    {
        $tags = $this->getTags($content);
        return [
            array_column($tags, 'tag'),
            array_column($tags, 'fallback'),
        ];
    }

}
