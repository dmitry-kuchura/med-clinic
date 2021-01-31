<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function returnResponse(array $response, $status_code = 200, array $headers = [])
    {
        $response['success'] = true;

        return response()->json($response, $status_code, $headers, JSON_NUMERIC_CHECK);
    }
}
