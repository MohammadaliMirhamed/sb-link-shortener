<?php

declare(strict_types=1);

namespace App\Domain\Auth;

use App\Domain\DomainException\DomainRecordNotFoundException;

class AuthException extends DomainRecordNotFoundException
{
    public $message = 'The user you requested does not exist.';
}
