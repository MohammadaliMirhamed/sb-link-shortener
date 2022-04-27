<?php

namespace App\Domain\Auth;

use App\Domain\DomainAbstraction;
use App\Domain\Token\Token;
use App\Helpers\Functions;
use PDO;

class Auth extends DomainAbstraction implements AuthRepository
{

    /**
     * @param array $data
     * @return array
     */
    public function login(array $data): array
    {
        // get the user
        $authModel = $this->db->prepare("SELECT * FROM users WHERE email = :email AND password = :password");

        // hash the password
        $password = Functions::bcrypt($data['password']);
       
        // execute the query and bind the parameters
        $authModel->execute([
            'email' => $data['email'],
            'password' => $password
        ]);

        $user = $authModel->fetchAll(PDO::FETCH_ASSOC);
        
        // if user exists
        if (count($user) > 0) {
            // create token
            $token = new Token();
            return $token->createToken($user[0]['id']);
        }
        
        return ['error' => 'Invalid email or password'];
    }
    
    /**
     * @param array $data
     * @return array
     */
    public function register(array $data): array
    {
        // get the user
        $authModel = $this->db->prepare("SELECT * FROM users WHERE email = :email");

        // bind the parameters
        $authModel->bindParam(':email', $data['email']);

        // execute the query
        $authModel->execute();

        $user = $authModel->fetchAll(PDO::FETCH_ASSOC);
        // if user exists
        if (count($user) > 0) {
            return ['status' => 'false', 'message' => 'Email already exists'];
        }
        
        // hash the password
        $password = Functions::bcrypt($data['password']);

        // insert user to database
        $authModel = $this->db->prepare("INSERT INTO users (name, email, password, role) VALUES (:name, :email, :password, 'user')");
        
        // execute the query and bind parameters
        $authModel->execute([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $password
        ]);
        
        // create token
        $tokenModel = new Token();
        $token = $tokenModel->createToken($this->db->lastInsertId());

        return $token;
    }
}