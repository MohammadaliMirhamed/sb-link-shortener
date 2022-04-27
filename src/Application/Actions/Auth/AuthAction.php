<?php

declare(strict_types=1);

namespace App\Application\Actions\Auth;

use App\Application\Actions\Action;
use App\Database\DbConnection;
use App\Domain\Auth\Auth;
use Psr\Log\LoggerInterface;

abstract class AuthAction extends Action
{

    /**
     * @var Auth
     */
    protected $auth;

    /**
     * AuthAction constructor.
     */
    public function __construct(LoggerInterface $logger, Auth $auth)
    {
        parent::__construct($logger);
        $this->auth = $auth;
    }
}
