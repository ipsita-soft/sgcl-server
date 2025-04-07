<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ApiResponseController extends Controller
{
    public function successResponse($message=null,$status=200,$data=null)
    {
        return response()->json([
            'message' => $message ?? __("Successful"),
            'status' => true,
            'data' => $data,
        ], $status);
    }

    public function errorResponse($message=null,$status=400,$data=null)
    {
        return response()->json([
            'message' => $message ?? __("Somthing Went Wrong"),
            'status' => false,
            'data' => $data,
        ], $status);
    }

    public function dataNotFoundResponse($message=null,$status=404,$data=null)
    {
        return $this->errorResponse(
            status:$status ?? 404,
            message:$message ?? __("Data Not Found"),
            data:$data,
        );
    }
}
