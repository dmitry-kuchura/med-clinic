<?php

namespace App\Repositories;

use App\Models\Enum\MessageStatus;
use App\Models\Message;
use Illuminate\Database\Eloquent\Collection;

class MessagesRepository implements Repository
{
    public function get(int $id)
    {
        // TODO: Implement get() method.
    }

    public function messagesForUpdateStatus(): ?Collection
    {
        return Message::whereIn('status', [MessageStatus::QUEUED, MessageStatus::ACCEPTED, MessageStatus::SENT])
            ->limit(25)
            ->get();
    }

    public function all()
    {
        // TODO: Implement all() method.
    }

    public function store(array $data): Message
    {
        $model = new Message();

        $model->recipient = $data['recipient'];
        $model->text = $data['text'];
        $model->message_id = $data['message_id'];
        $model->response_code = $data['response_code'];
        $model->response_status = $data['response_status'];

        $model->save();

        return $model;
    }

    public function updateMessage(array $data)
    {
        return Message::where('message_id', $data['message_id'])->update($data);
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
