<?php

declare(strict_types=1);

namespace App\Domain\Token;

use App\Domain\DomainAbstraction;
use PDO;


class Token extends DomainAbstraction implements TokenRepository
{
    /**
     * @param string $token
     * @return bool
     */
    public function isTokenValid(string $token): array
    {
        // Current date
        $date = date('Y-m-d H:i:s');

        // Check if token exists
        $tokenModel = $this->db->prepare("SELECT * FROM `tokens` where `token` = :token and expired_at > :expired_at limit 1");
        $tokenModel->bindParam(':token', $token);
        $tokenModel->bindParam(':expired_at', $date);
        $tokenModel->execute();
        
        $token = $tokenModel->fetchAll(PDO::FETCH_ASSOC);

        // If token exists, return true
        if (count($token) > 0 ) {
            return ['status' => true, 'data' => $token[0]];
        }
        
        return ['status' => false, 'data' => []];
    }

    /**
     * @param int $userId
     * @return string
     */
    public function createToken(int $userId): array
    {
        // create token hash
        $token = bin2hex(random_bytes(64));

        // currnent date add 30 days
        $expired_at = date('Y-m-d H:i:s', strtotime('+30 days'));

        // insert token to database
        $tokenModel = $this->db->prepare("INSERT INTO `tokens` (`token`, `user_id`, `expired_at`) VALUES (:token, :user_id, :expired_at)");
        $tokenModel->bindParam(':token', $token);
        $tokenModel->bindParam(':user_id', $userId);
        $tokenModel->bindParam(':expired_at', $expired_at);
        $tokenModel->execute();

        return ['token' => $token, 'expired_at' => $expired_at];
    }
}