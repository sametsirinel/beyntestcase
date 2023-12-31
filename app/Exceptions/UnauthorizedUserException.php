<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Client\Request;
use Symfony\Component\HttpFoundation\Response;

class UnauthorizedUserException extends Exception
{
    public function render(): Response
    {
        return response()->json([
            "status" => false,
            "message" => "Unauthorized",
            "statusCode" => 401,
        ]);
    }
}
