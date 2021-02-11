<?php

namespace Mailery\Channel\Email\Mapper;

use Mailery\Activity\Log\Mapper\LoggableMapper;
use Mailery\Cycle\Mapper\ChainItemList;
use Mailery\Cycle\Mapper\ChainItem\SoftDeleted;
use Mailery\Channel\Email\Module;

/**
 * @Cycle\Annotated\Annotation\Table(
 *      columns = {
 *          "created_at": @Cycle\Annotated\Annotation\Column(type = "datetime"),
 *          "updated_at": @Cycle\Annotated\Annotation\Column(type = "datetime")
 *      }
 * )
 */
class DefaultMapper extends LoggableMapper
{
    /**
     * {@inheritdoc}
     */
    protected function getModule(): string
    {
        return Module::NAME;
    }
}