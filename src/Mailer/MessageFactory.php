<?php

namespace Mailery\Channel\Smtp\Mailer;

use Mailery\Campaign\Entity\Campaign;
use Mailery\Campaign\Entity\Recipient;
use Mailery\Sender\Email\Entity\EmailSender;
use Mailery\Template\Email\Entity\EmailTemplate;
use Mailery\Template\Renderer\Context;
use Mailery\Template\Renderer\ContextInterface;
use Mailery\Template\Renderer\BodyRendererInterface;
use Mailery\Template\Twig\NodeVisitor\TemplateVariablesVisitor;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Yiisoft\Strings\StringHelper;

class MessageFactory
{

    /**
     * @var ContextInterface|null
     */
    private ?ContextInterface $context;

    /**
     * @param Email $message
     * @param BodyRendererInterface $renderer
     */
    public function __construct(
        private Email $message,
        private BodyRendererInterface $renderer
    ) {}

    /**
     * @param ContextInterface $context
     * @return self
     */
    public function withContext(ContextInterface $context): self
    {
        $new = clone $this;
        $new->context = $context;

        return $new;
    }

    /**
     * @param Campaign $campaign
     * @param Recipient $recipient
     * @return Email
     */
    public function create(Campaign $campaign, Recipient $recipient): Email
    {
        /** @var EmailSender $sender */
        $sender = $campaign->getSender();

        /** @var EmailTemplate $template */
        $template = $campaign->getTemplate();

        $message = (clone $this->message)
            ->from(new Address($sender->getEmail(), $sender->getName()))
            ->to(new Address($recipient->getIdentifier(), $recipient->getName()))
            ->replyTo(new Address($sender->getReplyEmail(), $sender->getReplyName()))
            ->subject($campaign->getName())
            ->text($template->getTextContent())
            ->html($template->getHtmlContent());

        if (($context = $this->context) === null) {
            $context = new Context();
        }

        $visitor = new TemplateVariablesVisitor();

        $this->renderer
            ->withContext($context)
            ->withNodeVisitor($visitor)
            ->render($message);

        $variables = [];
        foreach ($visitor->getVariables() as $variable) {
            $variables[$variable] = $context->get($variable);
        }

        $recipient->setMessageContext(
            $this->convertVariablesToArray($variables)
        );

        return $message;
    }

    /**
     * @param array $variables
     * @return array
     */
    private function convertVariablesToArray(array $variables): array
    {
        $array = [];

        foreach ($variables as $key => $value) {
            $keys =  StringHelper::parsePath($key);
            $c = &$array[array_shift($keys)];
            foreach ($keys as $key) {
                if (isset($c[$key]) && $c[$key] === true) {
                    $c[$key] = [];
                }
                $c = &$c[$key];
            }
            if ($c === null) {
                $c = $value;
            }
        }
        return $array;
    }

}