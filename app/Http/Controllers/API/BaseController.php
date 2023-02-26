<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;

class BaseController extends Controller
{
    public function postSuccess($data, $message = 'Thêm mới thành công!', $code = 201)
    {
        if($data == null)
        {
            return response()->json([
                'code' => $code,
                'message' => $message
            ], $code);
        }
        else
        {
            return response()->json([
                'code' => $code,
                'message' => $message,
                'data' => $data,
            ], $code);
        }
    }

    public function validatorFails($errors, $code = 422)
    {
        return response()->json([
            'code' => $code,
            'errors' => $errors
        ], $code);
    }

    public function getData($data, $code = 200)
    {
        if(count($data) === 0) return response()->json(["message" => "Không có dữ liệu!"]);
        return response()->json([
            'code' => $code,
            'data' => $data
        ], $code);
    }
}
