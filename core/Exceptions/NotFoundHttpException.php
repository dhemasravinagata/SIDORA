<?php

namespace Core\Exceptions;

use Exception;

class NotFoundHttpException extends HttpException
{
    public function __construct(string $message = 'Halaman tidak ditemukan', ?Exception $previous = null, array $headers = [])
    {
        parent::__construct(404, $message, $previous, $headers);
    }
}
