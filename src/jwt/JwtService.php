<?php

namespace App\jwt;

use App\exception\JwtSecretNotFound;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;


class JwtService {

    private string $jwtSecret;


    /**
     *  Constructs the JWT secret from the environment variable "JWT_SECRET"
     */
    public function __construct() {
        // Sets the JWT secret to the value of the environment variable
        $this->jwtSecret = getenv('JWT_SECRET') ?: "TestJwtSecret_IamATestQ4vN8bR3tY6wL1zH5cD0sA";
    }

    /**
     *  Generates a new JWT with 7d exp time
     *
     * @return string the Token
     * @throws JwtSecretNotFound This Exception is throwed when the ENV "JWT_SECRET" not exists
     */
    public function generateToken(): string {

        if (empty($this->jwtSecret)) {
            throw new JwtSecretNotFound("Couldn't load JWT secret from environment");
        }

        // Sets the payload for the Token (initialized at, and expires at)
        $payload = [
            "iat" => time(),
            "exp" => time() + 604800,
        ];
        // Returns the Generated token with the payload, the secret and an algorithm
        return JWT::encode($payload, $this->jwtSecret, "HS256");
   }

    /**
     *  Verifies if a Token is valid
     *
     * @param string $token the token given by the request
     * @return bool if the token is valide
     * @throws JwtSecretNotFound if the env couldn't load
     */
   public function verifyToken(string $token): bool {

       // Checks if the secret is set
       if (empty($this->jwtSecret)) {
           throw new JwtSecretNotFound("Couldn't load JWT secret from environment");
       }

       // Decodes the token to get the payload
       // It is not possible to manipulate the token without the secret, else the payload wouldn't be guilty
        $payload = JWT::decode($token, new Key($this->jwtSecret, 'HS256'));
        // Checks if the payload expiration time is in the past
        if ($payload->exp < time()) {
            return false;
        }
        return true;
   }

}