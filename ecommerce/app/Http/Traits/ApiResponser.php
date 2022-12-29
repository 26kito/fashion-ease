<?php

namespace App\Http\Traits;

trait ApiResponser
{
    public function successResponse($httpStatusCode = 200, $message = null, $data = null)
    {
        return response()->json([
            "success" => true,
            "message" => $message,
            "data" => $data
        ], $httpStatusCode);
    }

    public function failedResponse($httpStatusCode = 200, $message)
    {
        return response()->json([
            "success" => false,
            "message" => $message
        ], $httpStatusCode);
    }
}
