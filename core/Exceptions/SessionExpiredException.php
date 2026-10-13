<?php

namespace Core\Exceptions;

use Exception;

class SessionExpiredException extends HttpException
{
    public function __construct(string $message = 'Sesi Anda telah berakhir atau token keamanan tidak valid', ?Exception $previous = null, array $headers = [])
    {
        parent::__construct(419, $message, $previous, $headers);
    }
}
