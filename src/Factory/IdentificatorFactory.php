<?php

namespace Mailery\Channel\Smtp\Factory;

use Mailery\Campaign\Recipient\Factory\IdentificatorFactoryInterface;
use Mailery\Channel\Smtp\Model\EmailIdentificator;
use Yiisoft\Validator\DataSet\ArrayDataSet;
use Yiisoft\Validator\Result;
use Yiisoft\Validator\RuleInterface;
use Yiisoft\Validator\Rule\Callback;
use Yiisoft\Validator\Rule\Required;
use Yiisoft\Validator\Rule\Email;
use Yiisoft\Validator\Validator;

class IdentificatorFactory implements IdentificatorFactoryInterface
{

    /**
     * @inheritdoc
     */
    public function getValidationRule(): RuleInterface
    {
        return Callback::rule(function (string $value): Result {
            $result = new Result();
            $validator = new Validator();

            foreach ($this->fromString($value) as $identificator) {
                $itemResult = $validator->validate(
                    new ArrayDataSet([
                        'email' => $identificator->getIdentificator(),
                    ]),
                    [
                        'email' => [
                            Required::rule(),
                            Email::rule(),
                        ],
                    ]
                );

                if (!$itemResult->isValid()) {
                    $messages = $itemResult->getErrorMessages();
                    $result->addError(reset($messages));
                    return $result;
                }
            }

            return $result;
        });
    }

    /**
     * @inheritdoc
     */
    public function fromString(string $string): array
    {
        $emails = [];

        if(preg_match_all('/\s*"?([^><,"]+)"?\s*((?:<[^><,]+>)?)\s*/', $string, $matches, PREG_SET_ORDER) > 0) {
            foreach($matches as $m) {
                if(!empty($m[2])) {
                    $emails[trim($m[2], '<>')] = $m[1];
                } else {
                    $emails[$m[1]] = '';
                }
            }
        }

        $results = [];

        foreach ($emails as $email => $name) {
            $results[] = new EmailIdentificator($email, $name);
        }

        return $results;
    }

}
