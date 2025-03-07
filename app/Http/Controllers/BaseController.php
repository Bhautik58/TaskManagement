<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BaseController extends Controller
{
    public function sendError(bool $status, $error_messages = [], $statusCode = 500){
        return response()->json([
            'status' => $status,
            'message' => $error_messages
        ], $statusCode);
    }

    public function sendResponse(bool $status, $message, $data, $statusCode = 200){
        return response()->json([
            'status' => $status,
            'message' => $message,
            'data' => $data
        ], $statusCode);
    }
}
