<?php

declare(strict_types=1);

namespace App\Application\Actions\Link;

use Psr\Http\Message\ResponseInterface as Response;

class DeleteAction extends LinkAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        // get the form data
        $linkId = (int) $this->resolveArg('id');
        
        // delete the link
        $this->link->delete($linkId);

        // log the action
        $this->logger->info("link of id `${linkId}` has deleted.");

        return $this->respondWithData(['status' => 'success']);
    }
}
