<?php
namespace App\controller;

use App\service\AuthenticationService;

class AuthController {

    private AuthenticationService $authenticationService;

    public function __construct(AuthenticationService $authenticationService) {
        $this->authenticationService = $authenticationService;
    }


    public function authenticate($request, $response, $args) {

        $body = $request->getParsedBody();

        $username = $body['username'] ?? null;
        $password = $body['password'] ?? null;

        $loginresponse = $this->authenticationService->loginRequest($username, $password);

        if (isset($loginresponse["error"])) {
            $response->getBody()->write(json_encode($loginresponse));
            $response = $response->withStatus(401)->withHeader('Content-Type', 'application/json');
            return $response;
        }
        if (isset($loginresponse["fatal_error"])) {
            $response->getBody()->write(json_encode($loginresponse));
            $response = $response->withStatus(500)->withHeader('Content-Type', 'application/json');
            return $response;
        }
        $response->getBody()->write(json_encode($loginresponse));
        $response = $response->withStatus(200)->withHeader('Content-Type', 'application/json');
        return $response;
    }
}
