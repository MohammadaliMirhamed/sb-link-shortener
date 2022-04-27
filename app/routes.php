<?php

declare(strict_types=1);

use App\Application\Actions\Auth\LoginAction;
use App\Application\Actions\Auth\RegisterAction;
use App\Application\Actions\Link\ListAction;
use App\Application\Actions\Link\ViewAction;
use App\Application\Actions\Link\UpdateAction;
use App\Application\Actions\Link\CreateAction;
use App\Application\Actions\Link\DeleteAction;
use App\Application\Middleware\AuthenticationMiddelware;
use App\Domain\Link\Link;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;


return function (App $app) {
    $app->options('/{routes:.*}', function (Request $request, Response $response) {
        // CORS Pre-Flight OPTIONS Request Handler
        return $response;
    });

    // Go to link route
    $app->get('/go/{link}', function (Request $request, Response $response) {
        $link = new Link();
        $url = $link->gotoLink($request->getAttribute('link'));
        return $response->withHeader('Location', $url)->withStatus(302);
    });

    // Auth routes
    $app->group('/auth', function (Group $group) {
        $group->post('/login', LoginAction::class);
        $group->post('/register', RegisterAction::class);
    });

    // Link routes
    $app->group('/links', function (Group $group) {
        $group->get('', ListAction::class);
        $group->post('', CreateAction::class);
        $group->get('/{id}', ViewAction::class);
        $group->put('/{id}', UpdateAction::class);
        $group->delete('/{id}', DeleteAction::class);
    })->add(new AuthenticationMiddelware());

};
