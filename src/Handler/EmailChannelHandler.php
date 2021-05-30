<?php

namespace Mailery\Channel\Email\Handler;

use Mailery\Campaign\Entity\Sendout;
use Mailery\Campaign\Entity\Recipient;
use Mailery\Channel\Handler\AbstractHandler;
use Mailery\Channel\Handler\HandlerInterface;
use Mailery\Channel\Email\Model\EmailChannelType;
use Cycle\ORM\ORMInterface;
use Yiisoft\Yii\Cycle\Data\Writer\EntityWriter;
use Yiisoft\Mailer\MailerInterface;
use Mailery\Sender\Email\Entity\EmailSender;
use Mailery\Template\Email\Entity\EmailTemplate;

class EmailChannelHandler extends AbstractHandler implements HandlerInterface
{

    /**
     * @var ORMInterface
     */
    private ORMInterface $orm;

    /**
     * @var MailerInterface
     */
    private MailerInterface $mailer;

    /**
     * @param ORMInterface $orm
     */
    public function __construct(ORMInterface $orm, MailerInterface $mailer)
    {
        $this->orm = $orm;
        $this->mailer = $mailer;
    }

    /**
     * @inheritdoc
     */
    public function handle(Sendout $sendout, Recipient $recipient): bool
    {
        if (!($this->getChannelType() instanceof EmailChannelType)) {
            return parent::handle($sendout, $recipient);
        }

        if ($recipient->hasSendout()) {
            throw new \RuntimeException('Recipient already have sendout.');
        }

        /** @var EmailSender $sender */
        $sender = $sendout->getCampaign()->getSender();
        /** @var EmailTemplate $template */
        $template = $sendout->getCampaign()->getTemplate();

        $message = $this->mailer->compose()
            ->withTo([$recipient->getIdentifier() => $recipient->getName()])
            ->withFrom([$sender->getEmail() => $sender->getName()])
            ->withReplyTo([$sender->getReplyEmail() => $sender->getReplyName()])
            ->withSubject($sendout->getCampaign()->getName())
            ->withTextBody($template->getTextContent())
            ->withHtmlBody($template->getHtmlContent())
        ;

        $recipient->setSendout($sendout);
        (new EntityWriter($this->orm))->write([$recipient]);

        $this->mailer->send($message);

        return true;
    }

}
