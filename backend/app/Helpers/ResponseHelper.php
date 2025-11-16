<?php

namespace App\Helpers;

class ResponseHelper {
    public static function make($code, $status, $message, $data = null) {
        return response()->json([
            'code' => $code,
            'status' => $status,
            'message' => $message,
            'data' => $data
        ]);
    }
}