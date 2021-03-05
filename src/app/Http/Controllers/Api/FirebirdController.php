<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Firebird\Queue;

class FirebirdController extends Controller
{
    public function list()
    {
        $data = [];

        $users = Queue::select('*')->get();

        foreach ($users as $user) {
            $data[] = $user;
        }

        return $this->returnResponse([
            'success' => true,
            'data' => $data
        ]);
    }
}
