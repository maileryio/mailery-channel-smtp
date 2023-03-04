<?php

namespace Mailery\Channel\Smtp\Mailer;

use Mailery\Campaign\Entity\Recipient;
use Mailery\Sender\Email\Entity\EmailSender;
use Mailery\Template\Renderer\Context;
use Mailery\Template\Renderer\ContextInterface;
use Mailery\Template\Renderer\BodyRendererInterface;
use Mailery\Template\Twig\NodeVisitor\VariablesVisitor;
use Symfony\Component\Mime\Address;
use Yiisoft\Strings\StringHelper;
use Mailery\Channel\Smtp\Mailer\Message\EmailMessage;
use Mailery\Channel\Smtp\Mailer\Message\WrappedTemplate;

class MessageFactory
{

    /**
     * @var ContextInterface|null
     */
    private ?ContextInterface $context;

    /**
     * @var array
     */
    private array $middlewares = [];

    /**
     * @param EmailMessage $message
     * @param BodyRendererInterface $renderer
     */
    public function __construct(
        private EmailMessage $message,
        private BodyRendererInterface $renderer
    ) {
        $this->context = new Context();
    }

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
     * @param array $middlewares
     * @return self
     */
    public function withMiddlewares(array $middlewares): self
    {
        $new = clone $this;
        $new->middlewares = $middlewares;

        return $new;
    }

    /**
     * @param WrappedTemplate $template
     * @param EmailSender $sender
     * @param Recipient $recipient
     * @return EmailMessage
     */
    public function create(WrappedTemplate $template, EmailSender $sender, Recipient $recipient): EmailMessage
    {
        $message = (clone $this->message)
            ->from(new Address($sender->getEmail(), $sender->getName()))
            ->to(new Address($recipient->getIdentifier(), $recipient->getName()))
            ->replyTo(new Address($sender->getReplyEmail(), $sender->getReplyName()))
            ->subject($template->getSubject())
            ->text($template->getTextContent())
            ->html($template->getHtmlContent());

        foreach ($this->middlewares as $middleware) {
            $message = $middleware($message);
        }

        $variablesVisitor = new VariablesVisitor();

        $this->renderer
            ->addNodeVisitor($variablesVisitor)
            ->withContext($this->context)
            ->render($message);

        $context = [];
        foreach ($variablesVisitor->getVariables() as $variable) {
            $context[$variable] = $this->context->get($variable);
        }

        $message->context($this->convertDotToArray($context));

        return $message;
    }

    /**
     * @param array $list
     * @return array
     */
    private function convertDotToArray(array $list): array
    {
        $array = [];

        foreach ($list as $key => $value) {
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