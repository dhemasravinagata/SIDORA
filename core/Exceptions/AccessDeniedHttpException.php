<?php

namespace Core\Exceptions;

use Exception;

class AccessDeniedHttpException extends HttpException
{
    public function __construct(string $message = 'Akses ditolak: Anda tidak memiliki izin untuk membuka halaman ini', ?Exception $previous = null, array $headers = [])
    {
        parent::__construct(403, $message, $previous, $headers);
    }
}
