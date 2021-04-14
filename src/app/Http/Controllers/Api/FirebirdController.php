<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\VisitsService;

class FirebirdController extends Controller
{
    /** @var VisitsService */
    private VisitsService $visitsService;

    public function __construct(
        VisitsService $visitsService
    )
    {
        $this->visitsService = $visitsService;
    }

    public function data()
    {
        $data = $this->visitsService->getRemoteVisits();

        $this->visitsService->sync($data);
    }

}
