<?php

namespace App\Repositories;

use App\Models\Settings;
use Illuminate\Database\Eloquent\Collection;

class SettingsRepository implements Repository
{
    public function get(int $id)
    {
        // TODO: Implement get() method.
    }

    public function all(): Collection
    {
        return Settings::all();
    }

    public function store(array $data): Settings
    {
        $model = new Settings();

        $model->name = $data['name'];
        $model->type = $data['type'];
        $model->value = $data['value'];
        $model->key = $data['key'];

        $model->save();

        return $model;
    }

    public function update(array $data, int $id)
    {
        // TODO: Implement update() method.
    }

    public function destroy(int $id)
    {
        // TODO: Implement destroy() method.
    }
}
