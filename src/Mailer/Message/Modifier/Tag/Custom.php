<?php

namespace common\components\Mail\Message\Modifier\Tag;

use common\models\Group;
use common\models\Message;
use common\components\Mail;

class Custom extends Mail\Message\Modifier\Tag
{

    /**
     * @var array
     */
    public $params = [];

    /**
     * @var array
     */
    protected $fields;

    /**
     * @var Message\Recipient
     */
    protected $recipient;

    /**
     * @param \common\components\Mail\MessageInterface $message
     */
    public function modify(Mail\MessageInterface $message)
    {
        /* @var $recipient Message\Recipient */
        $this->recipient = $message->getRecipient();
        parent::modify($message);
    }

    /**
     * @param string $content
     * @return array
     */
    protected function getSubjectTags($content)
    {
        return $this->resolveReplaces($content);
    }

    /**
     * @param string $content
     * @return array
     */
    protected function getHtmlBodyTags($content)
    {
        return $this->resolveReplaces($content);
    }

    /**
     * @param string $content
     * @return array
     */
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
        preg_match_all('/(\[[a-zA-Z0-9!#%^&*()+=$@._\-\:|\/?<>~`"\'\s]+,\s*fallback=[a-zA-Z0-9!,#%^&*()+=$@._\-\:|\/?<>~`"\'\s]*\])/i', $content, $matches_tag, PREG_PATTERN_ORDER);
        preg_match_all('/\[([a-zA-Z0-9!#%^&*()+=$@._\-\:|\/?<>~`"\'\s]+),\s*fallback=/i', $content, $matches_field, PREG_PATTERN_ORDER);
        preg_match_all('/,\s*fallback=([a-zA-Z0-9!,#%^&*()+=$@._\-\:|\/?<>~`"\'\s]*)\]/i', $content, $matches_fallback, PREG_PATTERN_ORDER);

        $tags = [];
        for ($i = 0; $i < count($matches_tag[1]); $i++) {
            $tags[] = [
                'tag' => $matches_tag[1][$i],
                'field' => $matches_field[1][$i],
                'fallback' => $matches_fallback[1][$i]
            ];
        }
        return $tags;
    }

    /**
     * @return array
     */
    protected function getFields()
    {
        if($this->fields !== null) {
            return $this->fields;
        }

        $this->fields = [];

        foreach($this->recipient->subscriber->group->fields as $field) {
            $value = null;
            $attributeStorage = $this->recipient->subscriber->attributeStorage;

            if($this->recipient->subscriber->hasAttribute($field->name)) {
                $value = $this->recipient->subscriber->getAttribute($field->name);
            } else if($attributeStorage->hasAttribute($field->name)) {
                $value = $attributeStorage->getAttribute($field->name);
            }

            if($value !== null) {
                $this->fields[$field->name] = $this->resolveValue($value, $field->type->name);
            }
        }

        $params = [];
        foreach($this->params as $key => $value) {
            $type = null;
            if(strpos($key, ':') !== false) {
                list($key, $type) = explode(':', $key);
            }
            $params[$key] = $this->resolveValue($value, $type);
        }

        $this->fields = array_merge($this->fields, $params);

        return $this->fields;
    }

    /**
     * @param string $content
     * @return array
     */
    protected function resolveReplaces($content)
    {
        $replaces = [];
        foreach ($this->getTags($content) as $tag) {
            $value = null;

            if ($tag['field'] === 'Name') {
                $value = !empty($this->recipient->subscriber->attributeStorage->name) ? strtolower($this->recipient->subscriber->attributeStorage->name) : '';
            } else {
                foreach ($this->getFields() as $fieldKey => $fieldValue) {
                    if ($tag['field'] === $fieldKey) {
                        $value = $fieldValue;
                        continue;
                    }
                }
            }
            $replaces[$tag['tag']] = $value !== null ? $value : $tag['fallback'];
        }
        return [array_keys($replaces), array_values($replaces)];
    }

    /**
     * @param string $value
     * @param string $fieldType
     * @return string
     */
    protected function resolveValue($value, $fieldType = null)
    {
        return ($fieldType === Group\Field\Type::TYPE_DATE) ? strftime('%a, %b %d, %Y', $value) : $value;
    }

}
