<?php

namespace App\Http\Controllers;

abstract class Controller
{
    public function success(mixed $data, string $message = "okay", int $statusCode = 200)
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
        ], $statusCode);
    }


    public function error(string $message, int $statusCode = 400)
    {
        return response()->json([
            'data' => null,
            'success' => false,
            'message' => $message,
        ], $statusCode);
    }
}
