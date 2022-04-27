<?php

declare(strict_types=1);

namespace App\Application\Actions\Link;

use Psr\Http\Message\ResponseInterface as Response;

class CreateAction extends LinkAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        // get the form data
        $data = $this->getFormData();

        // check if the link is empty
        if(empty($data['link'])) {
            throw new \InvalidArgumentException('Link is empty');
        }

        // create the short link
        $short = $this->link->create($data['link']);
        
        // log the action
        $this->logger->info("Link created.");
        
        return $this->respondWithData(['short' => $short]);

    }
}
