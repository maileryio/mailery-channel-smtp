<?php

declare(strict_types=1);

namespace Mailery\Channel\Email\Repository;

use Cycle\ORM\Select\Repository;
use Mailery\Channel\Email\Entity\Domain;

class DnsRecordRepository extends Repository
{
    /**
     * @param Domain $domain
     * @return self
     */
    public function withDomain(Domain $domain): self
    {
        $repo = clone $this;
        $repo->select
            ->andWhere([
                'domain_id' => $domain->getId(),
            ]);

        return $repo;
    }
}
