<?php

namespace App\Http\Controllers\Response;

class SuccessResponse
{
    public static function ok($data=null, $message=null, $code)
    {
        $response = [
            'success' => true,
            'response' => [
                'code' => $code,
            ],
        ];
        if (!empty($message))
            $response['response']['message'] = $message;

        if (!empty($data))
            $response['response']['data'] = $data;


        return response()
            ->json($response, ($code) ? $code : 500);
    }
}
