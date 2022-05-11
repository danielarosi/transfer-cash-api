<?php

namespace App\Http\Controllers\Response;

class ErrorResponse
{

    public static function fails($message, $code)
    {

        $response = [
            'success' => false,
            'response' => [
                'code' => $code,
                'message' => $message
            ],
        ];

        return response()
            ->json($response, ($code) ? $code : 500);
    }

    public static function failsValidator($errors, $code)
    {

        $response = [
            'success' => false,
            'response' => [
                'code' => $code,
                'message' => $errors
            ],
        ];

        return response()
            ->json($response, ($code) ? $code : 500);
    }
}
