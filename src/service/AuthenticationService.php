<?php

namespace App\service;

use App\exception\JwtSecretNotFound;

class AuthenticationService {

    private JwtService $jwtService;

    private string $username = "admin";
    private string $password = "admin";

    public function __construct(JwtService $jwtService) {
        $this->jwtService = $jwtService;
    }

    public function loginRequest($username, $password): array {

    if ($this->password === $password && $this->username === $username) {
        try {
            $token = $this->jwtService->generateToken();

            return ["token" => $token];

        } catch (JwtSecretNotFound $e) {
            return ["fatal_error" => "Internal Server Error"];
        }
    }

    return ["error" => "Wrong username or password"];
}
}