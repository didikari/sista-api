<?php

namespace App\Helpers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class ResponseHelper
{
    /**
     * Response for successful operation
     *
     * @param mixed $data
     * @param string $message
     * @param int $statusCode
     * @return \Illuminate\Http\JsonResponse
     */
    public static function success($data, string $message = "Berhasil", int $statusCode = Response::HTTP_OK): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'code'    => $statusCode,
            'message' => $message,
            'data'    => $data,
        ], $statusCode);
    }

    /**
     * Response for error operation
     *
     * @param string $message
     * @param int $statusCode
     * @param mixed $errors
     * @return \Illuminate\Http\JsonResponse
     */
    public static function error(string $message = "Terjadi kesalahan", int $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR, $errors = null): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'code'    => $statusCode,
            'message' => $message,
            'data'    => null,
            'errors'  => $errors,
        ], $statusCode);
    }

    /**
     * Response for exception handling
     *
     * @param Throwable $th
     * @return \Illuminate\Http\JsonResponse
     */
    public static function exception(Throwable $th): JsonResponse
    {

        if ($th instanceof \Illuminate\Validation\ValidationException) {
            return self::error('Validation failed', Response::HTTP_UNPROCESSABLE_ENTITY, $th->errors());
        }
        // Set the status code to 500 if it's invalid
        $code = $th->getCode() ?: Response::HTTP_INTERNAL_SERVER_ERROR;
        $code = $code < 100 || $code > 599 ? Response::HTTP_INTERNAL_SERVER_ERROR : $code;

        // Log the exception for debugging purposes
        Log::error('Exception Occurred', ['exception' => $th]);

        // Return response
        return response()->json([
            'code'    => $code,
            'message' => env('APP_DEBUG') ? $th->getMessage() : 'Terjadi kesalahan',
            'data'    => null,
        ], $code);
    }
}
