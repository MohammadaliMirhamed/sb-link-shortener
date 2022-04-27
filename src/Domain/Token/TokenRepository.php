<?php

declare(strict_types=1);

namespace App\Domain\Token;

interface TokenRepository
{
    /**
     * @param string $token
     * @return bool
     */
    public function isTokenValid(string $token): array;

    /**
     * @param int $token
     * @return bool
     */
    public function createToken(int $userId): array;
}
