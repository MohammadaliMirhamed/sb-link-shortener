<?php

declare(strict_types=1);

namespace App\Domain\Auth;

interface AuthRepository
{
    /**
     * @param array $data
     * @return array
     * @throws AuthException
     */
    public function login(array $data): array;

    /**
     * @param array $data
     * @return array
     */
    public function register(array $data): array;
}
