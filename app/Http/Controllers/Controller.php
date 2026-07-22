<?php

namespace App\Http\Controllers;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ApiErrorResponse',
    properties: [
        new OA\Property(property: 'success', type: 'boolean', example: false),
        new OA\Property(property: 'message', type: 'string', example: 'Unauthorized'),
        new OA\Property(property: 'data', type: 'object', example: null, nullable: true),
    ],
)]
abstract class Controller
{
    public function success(mixed $data, string $message = 'okay', int $statusCode = 200)
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
