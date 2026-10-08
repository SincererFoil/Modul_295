<?php

namespace App\middleware;

use App\exception\JwtSecretNotFound;
use App\jwt\JwtService;
use Firebase\JWT\SignatureInvalidException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Psr7\Response;
use UnexpectedValueException;

class Middleware implements \Psr\Http\Server\MiddlewareInterface
{

    private JwtService $jwtService;


    /**
     *  Constructor Initializes the JWT service
     *
     * @param JwtService $jwtService an instance of a JWT-Service
     */
    public function __construct(JwtService $jwtService) {
        $this->jwtService = $jwtService;
    }


    /**
     *  Checks if the request has a valid token
     *  It returns an error on failure.
     *  On Success, it calls the handler to handle the request
     *
     * @param ServerRequestInterface $request Thr request
     * @param RequestHandlerInterface $handler The handler
     * @return ResponseInterface The response
     */
    public function process(ServerRequestInterface $request,  RequestHandlerInterface  $handler): ResponseInterface   {
        // Sets the token value to the clients cookie called "token" or else it will set it to null
        $token = $_COOKIE["token"] ?? null;

        // Checks if a token exists
        if ($token == null) {
            return $this->sendUnauthorized(new Response(), "Missing token", 401);
        }

        try {
            // Checks if the token is not valid
            if (!$this->jwtService->verifyToken($token)) {
                return $this->sendUnauthorized(new Response(), "Invalid token", 401);
            }
        } catch (\Firebase\JWT\ExpiredException $exception) {
            // Checks for Expired Tokens
            return $this->sendUnauthorized(new Response(), "Token expired", 401);
            // Catches Wrong initialized at values
        } catch (\Firebase\JWT\BeforeValidException $exception) {
            return $this->sendUnauthorized(new Response(), "Token not yet valid", 401);
            // Catches the Invalid Signature exception
            // This exception is called when the client sends
            // a token with an invalid signature
        } catch (SignatureInvalidException $exception) {
            return $this->sendUnauthorized(new Response(), "Invalid signature", 401);
            // Checks for not normal values
        } catch (UnexpectedValueException $exception) {
            return $this->sendUnauthorized(new Response(), "Malformed token", 401);
            // Every other Exception
        } catch (JwtSecretNotFound $exception) {
            return $this->sendUnauthorized(new Response(), "JWT secret not found", 500);
        } catch (\Throwable $exception) {
            return $this->sendUnauthorized(new Response(), "Invalid token value", 401);
        }
        // On success, it calls the handler to handle the incoming request
        $response = $handler->handle($request);
        return $response;

    }


    /**
     *  Sends a response the client with a specific code and reason
     *
     * @param ResponseInterface $response Response
     * @param string $message The specifig reason
     * @param int $code The specific status code
     * @return ResponseInterface Returns a Response with the code and reason
     */
    private function sendUnauthorized(ResponseInterface $response, string $message, int $code) : ResponseInterface {
        $response = $response->withHeader('Content-Type', 'application/json')->withStatus($code);
        if ($code == 500) {
            $response->getBody()->write(json_encode(["fatal_error" => $message]));
        } else {
            $response->getBody()->write(json_encode(["error" => $message]));
        }
        // Returns the response
        return $response;
    }

}
