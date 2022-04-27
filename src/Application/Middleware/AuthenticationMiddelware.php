<?php

declare(strict_types=1);

namespace App\Application\Middleware;

use App\Database\DbConnection;
use App\Domain\Token\Token;
use App\Helpers\Authenticated;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface as Middleware;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

class AuthenticationMiddelware implements Middleware
{
    public function process(Request $request, RequestHandler $handler,): Response
    {
        // Has request parameter 'Authorization'
        if(!$request->hasHeader('Authorization')) {
            throw new \Slim\Exception\HttpUnauthorizedException($request);
        }
        
        // Get token from request
        $tokenEntry = explode(" ", $request->getHeader('Authorization')[0]);
        
        // Is token strachture correct
        if(count($tokenEntry) < 2) {
            throw new \Slim\Exception\HttpUnauthorizedException($request);
        }
        
        // Get token from database and compare it with token from request
        $tokenOBJ = new Token();
        if(!$tokenOBJ->isTokenValid($tokenEntry[1])['status']) {
            throw new \Slim\Exception\HttpUnauthorizedException($request);
        }
        
        // set user data to Authenticated class
        Authenticated::init($tokenOBJ->isTokenValid($tokenEntry[1])['data']);

        return $handler->handle($request);
    }
    
}
