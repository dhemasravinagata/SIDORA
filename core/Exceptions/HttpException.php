<?php

namespace Core\Exceptions;

use Exception;

class HttpException extends Exception
{
    protected int $statusCode;
    protected array $headers;

    public function __construct(int $statusCode = 500, string $message = '', ?Exception $previous = null, array $headers = [])
    {
        $this->statusCode = $statusCode;
        $this->headers = $headers;
        parent::__construct($message, $statusCode, $previous);
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }
}
