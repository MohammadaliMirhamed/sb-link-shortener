<?php

declare(strict_types=1);

namespace App\Application\Actions\Auth;

use Psr\Http\Message\ResponseInterface as Response;

class LoginAction extends AuthAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        // get the form data
        $data = $this->getFormData();
        
        // check if the email and password are empty
        if(empty($data['email']) || empty($data['password'])) {
            throw new \InvalidArgumentException('Email or password is empty');
        }

        // check if the user is authenticated
        $token = $this->auth->login($data);

        // log the action
        $this->logger->info("User logged in.");
        
        return $this->respondWithData($token);    
    }
}
