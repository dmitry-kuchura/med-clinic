<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

class PatientsController extends Controller
{
    public function list()
    {
        return $this->returnResponse(['list' => []]);
    }
}
