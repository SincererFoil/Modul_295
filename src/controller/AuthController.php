<?php
namespace App\controller;

use App\service\AuthService;
use OpenApi\Attributes as OAT;


class AuthController {

    private AuthService $authenticationService;

    public function __construct(AuthService $authenticationService) {
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
        setcookie("token", $loginresponse["token"]);
        $response->withStatus(200)->withHeader('Content-Type', 'application/json');
        return $response;
    }


    /**
     *  Removes a JWT token from cookies
     *
     * @param $request
     * @param $response
     * @param $args
     * @return mixed
     */
    public function unAuthenticate($request, $response, $args) {
        // Removes the token cookie
        setcookie("token", "", 0);
        $response->withHeader('Content-Type', 'application/json');
        $response = $response->withStatus(204);
        return $response;
    }
}
