<?php

namespace Mailery\Channel\Smtp\Mailer\Message;

use Mailery\Template\Email\Entity\EmailTemplate;

class WrappedTemplate
{
    /**
     * @var string
     */
    private string $subject;

    /**
     * @var string
     */
    private string $textContent;

    /**
     * @var string
     */
    private string $htmlContent;

    /**
     * @param EmailTemplate $template
     */
    public function __construct(
        private EmailTemplate $template
    ) {}

    /**
     * @param string $subject
     * @return self
     */
    public function withSubject(string $subject): self
    {
        $new = clone $this;
        $new->subject = $subject;

        return $new;
    }

    /**
     * @param string $textContent
     * @return self
     */
    public function withTextContent(string $textContent): self
    {
        $new = clone $this;
        $new->textContent = $textContent;

        return $new;
    }

    /**
     * @param string $htmlContent
     * @return self
     */
    public function withHtmlContent(string $htmlContent): self
    {
        $new = clone $this;
        $new->htmlContent = $htmlContent;

        return $new;
    }

    /**
     * @return string
     */
    public function getSubject(): string
    {
        return $this->subject ?? $this->template->getName();
    }

    /**
     * @return string
     */
    public function getTextContent(): string
    {
        return $this->textContent ?? $this->template->getTextContent();
    }

    /**
     * @return string
     */
    public function getHtmlContent(): string
    {
        return $this->htmlContent ?? $this->template->getHtmlContent();
    }

}
