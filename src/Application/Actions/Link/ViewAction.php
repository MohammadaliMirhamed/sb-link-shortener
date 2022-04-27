<?php

declare(strict_types=1);

namespace App\Application\Actions\Link;

use Psr\Http\Message\ResponseInterface as Response;

class ViewAction extends LinkAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        // get the form data
        $linkId = (int) $this->resolveArg('id');
        $link = $this->link->view($linkId);

        // log the action
        $this->logger->info("link of id `${linkId}` was viewed.");

        return $this->respondWithData($link);
    }
}
