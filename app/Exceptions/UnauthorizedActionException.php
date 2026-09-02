<?php

namespace App\Exceptions;

class UnauthorizedActionException extends ApiException
{
    public function __construct(string $message = 'You are not authorized to perform this action.')
    {
        parent::__construct($message, 403);
    }
}
