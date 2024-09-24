<?php

namespace App\Exceptions;

use Exception;

class NotEnoughInStockException extends Exception
{
    protected int $statusCode;

    public function __construct(string $message = '', int $statusCode = 400, ?\Throwable $previous = null)
    {
        $this->statusCode = $statusCode;
        parent::__construct($message, 0, $previous);
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }
}
