<?php

namespace App\Trait;

trait ApiResponseTrait
{
    public function apiResponseSuccess ($data, $message = 'success', $code = 200){
        return response()->json([
            'data' => $data,
            'message' => $message,
        ], $code);
    }

    public function apiResponseError ($message, $code = 500){
        return response()->json([
            'message' => $message,
        ], $code);
    }

    public function apiNotFound ($message = 'Not Found'){
        return response()->json([
            'message' => $message,
        ],404);
    }
}
