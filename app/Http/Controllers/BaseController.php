<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class BaseController extends Controller
{
    /**
     * @param $response
     * @return JsonResponse
     */
    public function sendResponse($response): JsonResponse
    {
        return response()->json($response, Response::HTTP_OK);
    }

    /**
     * @param string $errorMessage
     * @param int $code
     * @return JsonResponse
     */
    public function sendError(string $errorMessage, int $code): JsonResponse
    {
        return response()->json(
            [
                'error' => $errorMessage,
            ],
            $code
        );
    }
}
