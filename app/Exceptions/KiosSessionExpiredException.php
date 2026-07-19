<?php

namespace App\Exceptions;

use Exception;

class KiosSessionExpiredException extends Exception
{
    public function toResponse()
    {
        return response()->json([
            'status'  => false,
            'message' => $this->getMessage(),
            'expired' => true,
        ], 401);
    }
}