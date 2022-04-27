<?php

declare(strict_types=1);

namespace App\Application\Actions\Link;

use Psr\Http\Message\ResponseInterface as Response;

class ListAction extends LinkAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        // get list of links
        $links = $this->link->list();
        
        // log the action
        $this->logger->info("links list was viewed.");
        
        return $this->respondWithData($links);
    }
}
