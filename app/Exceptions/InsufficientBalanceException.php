<?php

namespace App\Exceptions;

class InsufficientBalanceException extends ApiException
{
    public function __construct(string $message = 'Insufficient coin balance to complete this action.')
    {
        parent::__construct($message, 402, [
            'code' => 'INSUFFICIENT_COINS',
        ]);
    }
}
