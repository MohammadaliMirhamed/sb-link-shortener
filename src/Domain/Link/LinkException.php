<?php

declare(strict_types=1);

namespace App\Domain\Link;

use App\Domain\DomainException\DomainRecordNotFoundException;

class LinkException extends DomainRecordNotFoundException
{
    public $message = 'The link you requested does not exist.';
}
