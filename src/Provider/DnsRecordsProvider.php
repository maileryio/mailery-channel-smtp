<?php

namespace Mailery\Channel\Email\Provider;

use Mailery\Channel\Email\Entity\Domain;
use Mesour\DnsChecker\DnsRecord;
use Mesour\DnsChecker\DnsRecordSet;
use Mesour\DnsChecker\DnsRecordType;

class DnsRecordsProvider
{
    /**
     * @param Domain $domain
     * @return array
     */
    public function getExpected(Domain $domain): DnsRecordSet
    {
        return new DnsRecordSet([
            'SPF' => $this->getSpfRecord($domain),
            'DKIM' => $this->getDkimRecord($domain),
            'DMARC' => $this->getDmarcRecord($domain),
            'MX' => $this->getMxRecord($domain),
        ]);
    }

    /**
     * @param Domain $domain
     * @return DnsRecordSet
     */
    public function getSpfRecord(Domain $domain): DnsRecord
    {
        return new DnsRecord(
            DnsRecordType::TXT,
            $domain->getDomain(),
            'v=spf1 mx ip4:194.9.70.184 ~all'
        );
    }

    /**
     * @param Domain $domain
     * @return DnsRecordSet
     */
    public function getDkimRecord(Domain $domain): DnsRecord
    {
        return new DnsRecord(
            DnsRecordType::TXT,
            sprintf('mail._domainkey.%s', $domain->getDomain()),
            'v=DKIM1; k=rsa; p=MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCreWy3Di4fujHhYwopg+nOTEJ+6bG2hbtoIz7oP9EF6l1pzJg8CzdpFKUXMBTnKcgWML38z+XcXBRw5wjuv+eYcV0NfTMQfkmFyGE3GykTTmqwiWasTyUAoVXNlNmnfoK3nGfP5wOFU7+IT2LK+pY7ooz5tzJZiwZsOR6C0hgnzQIDAQAB'
        );
    }

    /**
     * @param Domain $domain
     * @return DnsRecordSet
     */
    public function getDmarcRecord(Domain $domain): DnsRecord
    {
        return new DnsRecord(
            DnsRecordType::TXT,
            sprintf('_dmarc.%s', $domain->getDomain()),
            'v=DMARC1; p=none; rua=mailto:support@automotolife.com'
        );
    }

    /**
     * @param Domain $domain
     * @return DnsRecordSet
     */
    public function getMxRecord(Domain $domain): DnsRecord
    {
        return new DnsRecord(
            DnsRecordType::MX,
            $domain->getDomain(),
            ''
        );
    }
}
