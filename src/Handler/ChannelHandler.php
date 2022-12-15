<?php

namespace Mailery\Channel\Smtp\Handler;

use Cycle\ORM\EntityManagerInterface;
use Mailery\Campaign\Entity\Campaign;
use Mailery\Campaign\Entity\Sendout;
use Mailery\Campaign\Entity\Recipient;
use Mailery\Campaign\Renderer\WrappedUrlGenerator;
use Mailery\Channel\Handler\HandlerInterface;
use Mailery\Sender\Email\Entity\EmailSender;
use Mailery\Channel\Smtp\Mailer\MailerFactory;
use Mailery\Channel\Smtp\Mailer\MessageFactory;
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
    public function handle(Sendout $sendout, Recipient $recipient): bool
    {
        /** @var Campaign $campaign */
        $campaign = $sendout->getCampaign();

        /** @var EmailSender $sender */
        $sender = $campaign->getSender();

        $recipient->setSendout($sendout);
        (new EntityWriter($this->entityManager))->write([$recipient]);

        $message = $this->messageFactory
            ->withContext($this->createContext($recipient))
            ->create($campaign, $recipient);

        $recipient->setMessageContext($message->getContext());

        try {
            $sentMessage = $this->mailerFactory
                ->createTransport($sender->getChannel())
                ->send($message);

            $recipient->setSent(true);
            $recipient->setMessageId($sentMessage->getMessageId());
        } catch (\Exception $e) {
            $recipient->setError($e->getMessage());
            throw $e;
        } finally {
            (new EntityWriter($this->entityManager))->write([$recipient]);
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
