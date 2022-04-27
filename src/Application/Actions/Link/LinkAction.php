<?php

declare(strict_types=1);

namespace App\Application\Actions\Link;

use App\Application\Actions\Action;
use App\Domain\Link\Link;
use Psr\Log\LoggerInterface;

abstract class LinkAction extends Action
{
    protected Link $link;

    public function __construct(LoggerInterface $logger, Link $link)
    {
        parent::__construct($logger);
        $this->link = $link;
    }
}
