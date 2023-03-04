<?php

namespace Mailery\Channel\Smtp\Handler;

use Cycle\ORM\EntityManagerInterface;
use Mailery\Campaign\Entity\Campaign;
use Mailery\Campaign\Entity\Sendout;
use Mailery\Campaign\Entity\Recipient;
use Mailery\Campaign\Renderer\WrappedUrlGenerator;
use Mailery\Campaign\Tracking\Mailer\Message\Middleware\ShortUrlLinker;
use Mailery\Campaign\Tracking\Mailer\Message\Middleware\TrackingPixel;
use Mailery\Channel\Handler\HandlerInterface;
use Mailery\Sender\Email\Entity\EmailSender;
use Mailery\Channel\Smtp\Mailer\MailerFactory;
use Mailery\Channel\Smtp\Mailer\MessageFactory;
use Mailery\Channel\Smtp\Mailer\Message\WrappedTemplate;
use Mailery\Template\Renderer\Context;
use Yiisoft\Yii\Cycle\Data\Writer\EntityWriter;

class ChannelHandler implements HandlerInterface
{

    /**
     * @var bool
     */
    private bool $suppressErrors = false;

    /**
     * @param MailerFactory $mailerFactory
     * @param MessageFactory $messageFactory
     * @param EntityManagerInterface $entityManager
     * @param WrappedUrlGenerator $wrappedUrlGenerator
     */
    public function __construct(
        private MailerFactory $mailerFactory,
        private MessageFactory $messageFactory,
        private EntityManagerInterface $entityManager,
        private WrappedUrlGenerator $wrappedUrlGenerator
    ) {}

    /**
     * @inheritdoc
     */
    public function withSuppressErrors(bool $suppressErrors): self
    {
        $new = clone $this;
        $new->suppressErrors = $suppressErrors;

        return $new;
    }

    /**
     * @inheritdoc
     */
    public function handle(Sendout $sendout, \Iterator|array $recipients): bool
    {
        /** @var Campaign $campaign */
        $campaign = $sendout->getCampaign();

        /** @var EmailSender $sender */
        $sender = $campaign->getSender();

        $template = (new WrappedTemplate($campaign->getTemplate()))
            ->withSubject($campaign->getName());

        $messageFactory = $this->messageFactory
            ->withMiddlewares([
                new ShortUrlLinker($campaign),
                new TrackingPixel($campaign)
            ]);

        foreach ($recipients as $recipient) {
            /** @var Recipient $recipient */
            if (!$recipient->canBeSend()) {
                continue;
            }

            $recipient->setSendout($sendout);
            (new EntityWriter($this->entityManager))->write([$recipient]);

            $message = $messageFactory
                ->withContext($this->createContext($recipient))
                ->create($template, $sender, $recipient);

            $recipient->setMessageContext($message->getContext());

            try {
                $sentMessage = $this->mailerFactory
                    ->createTransport($sender->getChannel())
                    ->send($message);

                $recipient->setSent(true);
                $recipient->setMessageId($sentMessage->getMessageId());
            } catch (\Exception $e) {
                $recipient->setError($e->getMessage());

                if (!$this->suppressErrors) {
                    throw $e;
                }
            } finally {
                (new EntityWriter($this->entityManager))->write([$recipient]);
            }
        }

        return true;
    }

    /**
     * @param Recipient $recipient
     * @return Context
     */
    private function createContext(Recipient $recipient): Context
    {
        $wrappedUrlGenerator = $this->wrappedUrlGenerator->withRecipient($recipient);
        if (($subscriber = $recipient->getSubscriber()) !== null) {
            $wrappedUrlGenerator = $wrappedUrlGenerator->withSubscriber($subscriber);
        }

        return new Context([
            'url' => $wrappedUrlGenerator,
        ]);
    }

}
