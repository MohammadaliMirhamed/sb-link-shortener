<?php

declare(strict_types=1);

namespace App\Application\Actions\Auth;

use Psr\Http\Message\ResponseInterface as Response;

class RegisterAction extends AuthAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        // get the form data
        $data = $this->getFormData();

        // check if the name and email and password are empty
        if(empty($data['name']) || empty($data['email']) || empty($data['password'])) {
            throw new \InvalidArgumentException('Email or password or name is empty');
        }

        // register the user
        $auth = $this->auth->register($data);

        // log the action
        $this->logger->info("User  registered.");

        return $this->respondWithData($auth);        
    }
}
