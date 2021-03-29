<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\SettingsRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class SettingsController extends Controller
{
    /** @var SettingsRepository */
    private SettingsRepository $settingsRepository;

    public function __construct(SettingsRepository $settingsRepository)
    {
        $this->settingsRepository = $settingsRepository;
    }

    public function list(): JsonResponse
    {
        $result = $this->settingsRepository->all();

        return $this->returnResponse(['settings' => $result], Response::HTTP_OK);
    }
}
