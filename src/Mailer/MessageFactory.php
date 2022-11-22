<?php

namespace Mailery\Channel\Smtp\Mailer;

use Mailery\Campaign\Entity\Campaign;
use Mailery\Campaign\Entity\Recipient;
use Mailery\Sender\Email\Entity\EmailSender;
use Mailery\Template\Email\Entity\EmailTemplate;
use Mailery\Template\Email\Renderer\WrappedMessage;
use Mailery\Template\Email\Renderer\WrappedUrlGenerator;
use Mailery\Template\Renderer\Context;
use Mailery\Template\Renderer\BodyRendererInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Yiisoft\Router\UrlGeneratorInterface;

class MessageFactory
{

    /**
     * @param Email $message
     * @param BodyRendererInterface $renderer
     * @param UrlGeneratorInterface $urlGenerator
     */
    public function __construct(
        private Email $message,
        private BodyRendererInterface $renderer,
        private UrlGeneratorInterface $urlGenerator
    ) {}

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

        $this->renderer
            ->withContext(new Context([
                'url' => new WrappedUrlGenerator($this->urlGenerator, $campaign, $recipient),
                'message' => new WrappedMessage($message),
            ]))
            ->render($message);

        return $message;
    }

}