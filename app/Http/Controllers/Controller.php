<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;

abstract class Controller
{
    use AuthorizesRequests, ValidatesRequests;

    public function response($data): JsonResponse
    {
        return response()->json([
            'data' => $data,
        ]);
    }

    public function error(string $message, $data = null, int $code = 400): JsonResponse
    {
        return response()->json([
            'success' => false,
            'status' => 'error',
            'message' => $message ?? 'error occured',
            'data' => $data,
        ],$code);
    }

    public function success(string $message, $data = null, int $code = 200): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message ?? 'operation successfull',
            'data' => $data,
        ], $code);
    }
}
