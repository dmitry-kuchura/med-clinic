<?php

namespace App\Services;

use App\Models\Settings;
use App\Repositories\SettingsRepository;

class SettingsService
{
    /** @var SettingsRepository */
    private SettingsRepository $settingsRepository;

    public function __construct(SettingsRepository $settingsRepository)
    {
        $this->settingsRepository = $settingsRepository;
    }

    public function list()
    {
        return $this->settingsRepository->paginate(self::RECORDS_AT_PAGE);
    }

    public function find(int $id): ?Settings
    {
        return $this->settingsRepository->get($id);
    }

    public function create(array $data): void
    {
        $this->settingsRepository->store($data);
    }

    public function update(array $data): void
    {
        $this->settingsRepository->update($data, $data['id']);
    }
}
