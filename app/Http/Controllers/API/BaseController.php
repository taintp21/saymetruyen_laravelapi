<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;

class BaseController extends Controller
{
    public function postSuccess($data, $message = 'Thêm mới thành công!', $code = 200)
    {
        if($data == null)
        {
            return response()->json([
                'message' => $message
            ], $code);
        }
        else
        {
            return response()->json([
                'data' => $data,
                'message' => $message
            ], $code);
        }
    }

    public function validatorFails($errors, $code = 400)
    {
        return response()->json([
            'errors' => $errors
        ], $code);
    }

    public function getData($data, $code = 200)
    {
        return response()->json($data, $code);
    }

    public function validateImageOrString($hasFile = false)
    {
        if(file_exists($hasFile)) return 'image|mimes:jpg,jpeg,png|mimetypes:image/jpeg,image/png';
        else return 'string';
    }
}
