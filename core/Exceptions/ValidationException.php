<?php

namespace Core\Exceptions;

use Exception;

class ValidationException extends Exception
{
    protected array $errors = [];
    protected ?string $redirectTo = null;

    public function __construct(array $errors, ?string $redirectTo = null, string $message = 'Data yang diberikan tidak valid')
    {
        $this->errors = $errors;
        $this->redirectTo = $redirectTo;
        parent::__construct($message, 422);
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function getRedirectTo(): ?string
    {
        return $this->redirectTo;
    }
}
