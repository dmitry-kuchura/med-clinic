<?php

namespace App\Repositories;

use App\Models\PasswordResets;

class PasswordResetsRepository implements Repository
{
    public function get(int $id)
    {
        // TODO: Implement get() method.
    }

    public function all()
    {
        // TODO: Implement all() method.
    }

    public function store(array $data)
    {
        $model = new PasswordResets();

        $model->token = $data['token'];
        $model->email = $data['email'];

        $model->save();
    }

    public function update(array $data)
    {
        // TODO: Implement update() method.
    }

    public function destroy(int $id)
    {
        // TODO: Implement destroy() method.
    }
}
