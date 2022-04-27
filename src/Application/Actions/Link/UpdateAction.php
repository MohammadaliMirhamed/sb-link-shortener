<?php

declare(strict_types=1);

namespace App\Application\Actions\Link;

use Psr\Http\Message\ResponseInterface as Response;

class UpdateAction extends LinkAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        // get the form data
        $data = $this->getFormData();
        $linkId = (int) $this->resolveArg('id');

        // check if the link is empty
        if(empty($data['link'])) {
            throw new \InvalidArgumentException('Link is empty');
        }
        
        // update the link
        $short = $this->link->update($linkId, $data['link']);
        
        // log the action
        $this->logger->info("Link has updated.");
        
        return $this->respondWithData(['short' => $short]);
    }
}
