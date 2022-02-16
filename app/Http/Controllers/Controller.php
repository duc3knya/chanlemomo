<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function validateParameterKeys($paramKeys, $data)
    {
        $validate = true;

        foreach ($paramKeys as $key) {
            if (!isset($data[$key])) {
                $validate = false;
                break;
            }
        }

        return $validate;
    }

    public function responseMissingParameters()
    {
        return [
            'status'  => 2,
            'message' => 'Thiếu dữ liệu gửi lên',
        ];
    }

    public function responseSuccess($data = [], $message = "")
    {
        return [
            'status'  => 1,
            'message' => $message,
            'data' => $data,
        ];
    }

    public function responseError($data = [], $message = "")
    {
        return [
            'status'  => 2,
            'message' => $message,
            'data' => $data,
        ];
    }

}
