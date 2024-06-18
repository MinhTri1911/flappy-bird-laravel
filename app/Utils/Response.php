<?php

namespace App\Utils;

use Illuminate\Pagination\LengthAwarePaginator;

class Response
{
    public static function success(string $message = null, array|LengthAwarePaginator $data = []): array
    {
        return [
            'success' => true,
            'message' => $message,
            'data' => $data,
        ];
    }

    public static function error(string $message, int $code = 400, array $errors = []): array
    {
        return [
            'success' => false,
            'message' => $message,
            'code' => $code,
            'errors' => $errors,
        ];
    }
}
