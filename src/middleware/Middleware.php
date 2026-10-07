<?php

namespace App\middleware;

use App\service\JwtService;
use Firebase\JWT\SignatureInvalidException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Psr7\Response;
use UnexpectedValueException;

class Middleware implements \Psr\Http\Server\MiddlewareInterface
{

    private JwtService $jwtService;


    public function __construct(JwtService $jwtService) {
        $this->jwtService = $jwtService;
    }


    public function process(ServerRequestInterface $request,  RequestHandlerInterface  $handler): ResponseInterface   {
        $authorization = $request->getHeader('Authorization');

        if (empty($authorization) || !str_starts_with($authorization[0], "Bearer ")) {
           return $this->sendUnauthorized(new Response(), "No authorization header");
        }

        $token = substr($authorization[0], 7);
        if (empty($token)) {
            return $this->sendUnauthorized(new Response(), "Missing token");
        }

        try {
            if (!$this->jwtService->verifyToken($token)) {
                return $this->sendUnauthorized(new Response(), "Invalid token");
            }
        } catch (\Firebase\JWT\ExpiredException $exception) {
            return $this->sendUnauthorized(new Response(), "Token expired");
        } catch (\Firebase\JWT\BeforeValidException $exception) {
            return $this->sendUnauthorized(new Response(), "Token not yet valid");
        } catch (SignatureInvalidException $exception) {
            return $this->sendUnauthorized(new Response(), "Invalid signature");
        } catch (UnexpectedValueException $exception) {
            return $this->sendUnauthorized(new Response(), "Malformed token");
        } catch (\Throwable $exception) {
            return $this->sendUnauthorized(new Response(), "Invalid token value");
        }

        $response = $handler->handle($request);
        return $response;

    }

    private function sendUnauthorized(Response $response, string $message) : ResponseInterface {

        $response = $response->withStatus(401);
        $response->getBody()->write(json_encode(["error" => $message]));
        return $response;
    }

}
