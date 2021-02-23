<?php

namespace Mailery\Channel\Email\Entity;

use Mailery\Activity\Log\Entity\LoggableEntityInterface;
use Mailery\Activity\Log\Entity\LoggableEntityTrait;
use Mailery\Channel\Email\Entity\Domain;
use Mailery\Common\Entity\RoutableEntityInterface;

/**
 * @Cycle\Annotated\Annotation\Entity(
 *      table = "brand_domain_dns_records",
 *      repository = "Mailery\Channel\Email\Repository\DnsRecordRepository",
 *      mapper = "Mailery\Channel\Email\Mapper\DefaultMapper"
 * )
 */
class DnsRecord implements RoutableEntityInterface, LoggableEntityInterface
{
    use LoggableEntityTrait;

    const STATUS_PENDING = 'pending';
    const STATUS_COMPLETED = 'completed';

    /**
     * @Cycle\Annotated\Annotation\Column(type = "primary")
     * @var int|null
     */
    private $id;

    /**
     * @Cycle\Annotated\Annotation\Relation\BelongsTo(target = "Mailery\Channel\Email\Entity\Domain", nullable = false)
     * @var Domain
     */
    private $domain;

    /**
     * @Cycle\Annotated\Annotation\Column(type = "string(255)")
     * @var string
     */
    private $type;

    /**
     * @Cycle\Annotated\Annotation\Column(type = "string(255)")
     * @var string
     */
    private $subtype;

    /**
     * @Cycle\Annotated\Annotation\Column(type = "string(255)", nullable = true)
     * @var string
     */
    private $name;

    /**
     * @Cycle\Annotated\Annotation\Column(type = "text", nullable = true)
     * @var string
     */
    private $content;

    /**
     * @Cycle\Annotated\Annotation\Column(type = "enum(pending, completed)")
     */
    private $status;

    /**
     * @return string
     */
    public function __toString(): string
    {
        return 'DNS Record #' . $this->getId();
    }

    /**
     * @return string|null
     */
    public function getId(): ?string
    {
        return $this->id ? (string) $this->id : null;
    }

    /**
     * @param int $id
     * @return self
     */
    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return Domain
     */
    public function getDomain(): Domain
    {
        return $this->domain;
    }

    /**
     * @param Domain $domain
     * @return self
     */
    public function setDomain(Domain $domain): self
    {
        $this->domain = $domain;

        return $this;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return self
     */
    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return string
     */
    public function getSubtype(): string
    {
        return $this->subtype;
    }

    /**
     * @param string $subtype
     * @return self
     */
    public function setSubtype(string $subtype): self
    {
        $this->subtype = $subtype;

        return $this;
    }


    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return self
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getContent(): ?string
    {
        return $this->content;
    }

    /**
     * @param string $content
     * @return self
     */
    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @param string $status
     * @return self
     */
    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getEditRouteName(): ?string
    {
        return '/brand/settings/domain';
    }

    /**
     * {@inheritdoc}
     */
    public function getEditRouteParams(): array
    {
        return ['brandId' => $this->getDomain()->getBrand()->getId()];
    }

    /**
     * {@inheritdoc}
     */
    public function getViewRouteName(): ?string
    {
        return '/brand/settings/domain';
    }

    /**
     * {@inheritdoc}
     */
    public function getViewRouteParams(): array
    {
        return ['brandId' => $this->getDomain()->getBrand()->getId()];
    }
}
