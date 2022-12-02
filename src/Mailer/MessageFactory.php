<?php

namespace Mailery\Channel\Smtp\Mailer;

use Mailery\Campaign\Entity\Campaign;
use Mailery\Campaign\Entity\Recipient;
use Mailery\Sender\Email\Entity\EmailSender;
use Mailery\Template\Email\Entity\EmailTemplate;
use Mailery\Template\Renderer\Context;
use Mailery\Template\Renderer\ContextInterface;
use Mailery\Template\Renderer\BodyRendererInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;

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

        $this->renderer
            ->withContext($context)
            ->render($message);

        return $message;
    }

}