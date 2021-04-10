<?php

namespace App\Repositories;

use App\Models\MessageTemplate;

class MessagesTemplatesRepository implements Repository
{
    public function paginate(int $offset)
    {
        return MessageTemplate::orderBy('id', 'desc')->paginate($offset);
    }

    public function get(int $id): ?MessageTemplate
    {
        return MessageTemplate::find($id);
    }

    public function all()
    {
        // TODO: Implement all() method.
    }

    public function store(array $data)
    {
        // TODO: Implement store() method.
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
