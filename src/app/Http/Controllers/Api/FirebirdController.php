<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\TurboSMS\Service;

class FirebirdController extends Controller
{
    /** @var Service */
    private Service $smsSender;

    public function __construct(Service $smsSender)
    {
        $this->smsSender = $smsSender;
    }

    public function data()
    {
        $response = $this->smsSender->getMessageStatus(['fac40793-6081-c1aa-1dd1-a4550b8c849f']);

        dd($response->getResponseResult());
    }
}
