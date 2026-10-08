<?php

namespace App\exception;
use Exception;
use Throwable;

class JwtSecretNotFound extends Exception {

    /**
     *  Constructs the Excpetion with the code and message
     *
     * @param $message
     * @param $code
     * @param Throwable|null $previous
     */
    public function __construct($message, $code = 0, ?Throwable $previous = null) {
        // Calls the parent in this case "Exception" to construct
        parent::__construct($message, $code, $previous);
    }

    /**
     *  This method returns the Exception message
     *
     * @return string the reason for the exception
     */
    public function __toString(): string {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}
