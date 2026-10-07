<?php

namespace App\service;

use App\exception\JwtSecretNotFound;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;


class JwtService {

    private string $jwtSecret;


    /**
     * @throws JwtSecretNotFound
     */
    public function __construct() {
        $this->jwtSecret = getenv('JWT_SECRET');
    }

    public function generateToken(): string {

        if (empty($this->jwtSecret)) {
            throw new JwtSecretNotFound("Couldn't load JWT secret from environment");
        }

        $payload = [
            "iat" => time(),
            "exp" => time() + 604800,
        ];

        return JWT::encode($payload, $this->jwtSecret, "HS256");
   }

   public function verifyToken(string $token): bool {

       if (empty($this->jwtSecret)) {
           throw new JwtSecretNotFound("Couldn't load JWT secret from environment");
       }

        $payload = JWT::decode($token, new Key($this->jwtSecret, 'HS256'));

        if ($payload->exp < time()) {
            return false;
        }

        return true;
   }

}