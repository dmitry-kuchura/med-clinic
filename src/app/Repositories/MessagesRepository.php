<?php

namespace App\Repositories;

use App\Models\Messages;

class MessagesRepository implements Repository
{
    public function get(int $id)
    {
        // TODO: Implement get() method.
    }

    public function all()
    {
        // TODO: Implement all() method.
    }

    public function store(array $data): Messages
    {
        $model = new Messages();

        $model->recipient = $data['recipient'];
        $model->text = $data['text'];
        $model->message_id = $data['message_id'];
        $model->response_code = $data['response_code'];
        $model->response_status = $data['response_status'];

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
