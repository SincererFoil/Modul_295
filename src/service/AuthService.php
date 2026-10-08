<?php

namespace App\service;

use App\exception\JwtSecretNotFound;
use App\jwt\JwtService;

class AuthService {

    // The Jwt service
    private JwtService $jwtService;

    // The registered username
    private string $username = "admin";

    // The ultra secret password
    private string $password = "sec!ReT423*&";

    /**
     *  Initializes the JWT-Service
     *
     * @param JwtService $jwtService The instance of an JWT-Service
     */
    public function __construct(JwtService $jwtService) {
        $this->jwtService = $jwtService;
    }

    /**
     *  Handles a Login request
     * It validates the Input and returns an array with the result
     *
     * @param string $username
     * @param string $password
     * @return array The final result containing an error or the generated token
     */
    public function loginRequest(string $username, string $password): array {

        // Checks if the Username and Password are correct
        if ($this->password === $password && $this->username === $username) {
            try {
                // Generates a new JWT signed Token
                $token = $this->jwtService->generateToken();

                // Returns the Token
                return ["token" => $token];

                // In case of an exception ti will return a 500 Internal Server Error
            } catch (JwtSecretNotFound $e) {
                return ["fatal_error" => "Internal Server Error"];
            }
        }

        // If the password or username wasn't correct, it will return an error
        return ["error" => "Wrong username or password"];
    }
}