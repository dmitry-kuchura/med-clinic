<?php

namespace App\Repositories;

use App\Models\PatientsMessages;

class PatientsMessagesRepository implements Repository
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
        $model = new PatientsMessages();

        $model->message_id = $data['message_id'];
        $model->patient_id = $data['patient_id'];

        $model->save();
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
